<?php
require_once '../db_connection.php';
session_start();

// Fetch all rooms from the database
$conn = db_connect();
$stmt = $conn->prepare("SELECT * FROM Rooms");
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to fetch comments for a room
function getCommentsForRoom($roomId) {
    $conn = db_connect();
    $stmt = $conn->prepare("
        SELECT c.comment_id, c.comment, c.created_at, c.admin_response, c.is_resolved,
               u.name as user_name,
               a.name as admin_name
        FROM Comments c 
        JOIN Users u ON c.user_id = u.user_id 
        LEFT JOIN Users a ON c.admin_id = a.user_id 
        WHERE c.room_id = ? 
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$roomId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_comment') {
    if (!isset($_SESSION['user_id'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Please login to comment']);
        exit;
    }

    $roomId = $_POST['room_id'];
    $comment = trim($_POST['comment']);
    
    if (empty($comment)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Comment cannot be empty']);
        exit;
    }

    try {
        $stmt = $conn->prepare("
            INSERT INTO Comments (user_id, room_id, comment) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $roomId, $comment]);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to add comment']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../output.css">
    <title>Browse Rooms</title>
</head>

<body class="bg-zinc-900 text-zinc-100">
    <?php include '../components/navbar.php'; ?>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-zinc-100 mb-6">Available Rooms</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($rooms as $room): ?>
                <div class="bg-zinc-800 rounded-lg shadow-lg overflow-hidden border border-zinc-700 transition-transform duration-300 hover:scale-105">
                    <?php if ($room['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($room['image_url']); ?>"
                            class="w-full h-48 object-cover"
                            alt="<?php echo htmlspecialchars($room['room_name']); ?>">
                    <?php else: ?>
                        <img src="../assets/default-room.jpg"
                            class="w-full h-48 object-cover"
                            alt="Default Room Image">
                    <?php endif; ?>

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-zinc-100 mb-2">
                            <?php echo htmlspecialchars($room['room_name']); ?>
                        </h3>

                        <div class="flex items-center text-zinc-400 mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Capacity: <?php echo htmlspecialchars($room['capacity']); ?> people</span>
                        </div>

                        <?php if ($room['equipment']): ?>
                            <div class="flex items-center text-zinc-400 mb-4">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Equipment: <?php echo htmlspecialchars($room['equipment']); ?></span>
                            </div>
                        <?php endif; ?>

                        <!-- Comments Section -->
                        <div class="mt-6 bg-zinc-700 rounded-lg shadow-md p-4">
                            <h4 class="text-lg font-bold text-zinc-100 mb-4">Comments</h4>

                            <!-- Comment Form -->
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form class="comment-form mb-4" data-room-id="<?php echo $room['room_id']; ?>">
                                    <textarea 
                                        class="w-full p-2 rounded bg-zinc-800 text-zinc-100 border border-zinc-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"
                                        placeholder="Add your comment..."
                                        rows="2"
                                        required
                                        resize="none"
                                    ></textarea>
                                    <button type="submit" 
                                        class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                        Add Comment
                                    </button>
                                </form>
                            <?php else: ?>
                                <p class="text-zinc-400 mb-4">Please <a href="../login.php" class="text-blue-400 hover:underline">login</a> to add comments.</p>
                            <?php endif; ?>

                            <!-- Display Comments -->
                            <div class="comments-container space-y-3" id="comments-<?php echo $room['room_id']; ?>">
                                <?php 
                                $comments = getCommentsForRoom($room['room_id']);
                                $recentComments = array_slice($comments, 0, 2); // Show only 2 most recent comments initially
                                $olderComments = array_slice($comments, 2); // Store remaining comments
                                
                                foreach ($recentComments as $comment): 
                                ?>
                                    <div class="comment bg-zinc-800 p-3 rounded">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="font-semibold text-zinc-200"><?php echo htmlspecialchars($comment['user_name']); ?></span>
                                            <span class="text-sm text-zinc-400"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></span>
                                        </div>
                                        <p class="text-zinc-300"><?php echo htmlspecialchars($comment['comment']); ?></p>
                                        <?php if ($comment['admin_response']): ?>
                                            <div class="mt-2 pl-4 border-l-2 border-blue-500">
                                                <p class="text-sm text-zinc-400">Admin Response (<?php echo htmlspecialchars($comment['admin_name']); ?>):</p>
                                                <p class="text-zinc-300"><?php echo htmlspecialchars($comment['admin_response']); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>

                                <?php if (!empty($olderComments)): ?>
                                    <div class="older-comments hidden space-y-3" id="older-comments-<?php echo $room['room_id']; ?>">
                                        <?php foreach ($olderComments as $comment): ?>
                                            <div class="comment bg-zinc-800 p-3 rounded">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="font-semibold text-zinc-200"><?php echo htmlspecialchars($comment['user_name']); ?></span>
                                                    <span class="text-sm text-zinc-400"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></span>
                                                </div>
                                                <p class="text-zinc-300"><?php echo htmlspecialchars($comment['comment']); ?></p>
                                                <?php if ($comment['admin_response']): ?>
                                                    <div class="mt-2 pl-4 border-l-2 border-blue-500">
                                                        <p class="text-sm text-zinc-400">Admin Response (<?php echo htmlspecialchars($comment['admin_name']); ?>):</p>
                                                        <p class="text-zinc-300"><?php echo htmlspecialchars($comment['admin_response']); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button 
                                        class="view-more-comments w-full py-2 px-4 bg-zinc-700 hover:bg-zinc-600 text-zinc-300 rounded transition-colors text-sm"
                                        data-room-id="<?php echo $room['room_id']; ?>"
                                        onclick="toggleOlderComments(this)"
                                    >
                                        View Previous Comments (<?php echo count($olderComments); ?>)
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a href="/booking/"
                            class="inline-block w-full text-center bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-300 mt-4">
                            Book Room
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="js/rooms.js"></script>
</body>

</html>