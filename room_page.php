<?php
session_start();
require_once 'db.php';  // Assuming this is your database connection

$room_id = $_GET['room_id'];

// Fetch room details
function getRoomDetails($room_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT room_name, capacity, equipment FROM Rooms WHERE room_id = ?");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get all comments for the room (for admin)
function getCommentsForAdmin($room_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT c.comment_id, c.comment, c.created_at, u.username, c.admin_response, c.response_created_at 
                            FROM Comments c 
                            JOIN Users u ON c.user_id = u.user_id 
                            WHERE c.room_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get all comments for the room (for users)
function getComments($room_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT c.comment_id, c.comment, c.created_at, u.username 
                            FROM Comments c 
                            JOIN Users u ON c.user_id = u.user_id 
                            WHERE c.room_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Handle user comment submission
if (isset($_POST['submit_comment'])) {
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];
    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO Comments (user_id, room_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $room_id, $comment);
        $stmt->execute();
        header("Location: room_page.php?room_id=" . $room_id); // Redirect after posting the comment
        exit();
    }
}

// Handle admin response to a comment
if (isset($_POST['submit'])) {
    $comment_id = $_POST['comment_id'];
    $admin_response = $_POST['response'];
    $admin_id = $_SESSION['user_id']; // Assuming the admin is logged in

    respondToComment($comment_id, $admin_id, $admin_response);

    // Redirect to admin page after responding
    header("Location: admin_comments_page.php?room_id=" . $room_id);
    exit();
}

// Function to respond to a comment (admin's role)
function respondToComment($comment_id, $admin_id, $response) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Comments SET admin_response = ?, response_created_at = NOW() WHERE comment_id = ?");
    $stmt->bind_param("si", $response, $comment_id);
    $stmt->execute();

    // Send notification to the user
    $stmt2 = $conn->prepare("INSERT INTO Notifications (user_id, message) VALUES ((SELECT user_id FROM Comments WHERE comment_id = ?), ?)");
    $notification_message = "Your comment has been responded to by an admin.";
    $stmt2->bind_param("is", $comment_id, $notification_message);
    $stmt2->execute();
}

$room = getRoomDetails($room_id);
$comments = getCommentsForAdmin($room_id);  // This can be filtered depending on user type (admin/user)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($room['room_name']); ?> - Room Details</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Room details -->
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-lg mt-10">
        <h1 class="text-3xl font-semibold text-center"><?php echo htmlspecialchars($room['room_name']); ?></h1>
        <p class="text-center text-gray-600 mt-2">Capacity: <?php echo htmlspecialchars($room['capacity']); ?> people</p>
        <p class="text-center text-gray-600">Equipment: <?php echo htmlspecialchars($room['equipment']); ?></p>
    </div>

    <!-- Comment Form (User section) -->
    <div class="container mx-auto p-6 mt-8 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold">Leave a Comment</h2>
        <form action="room_page.php?room_id=<?php echo $room_id; ?>" method="POST" class="mt-4">
            <textarea name="comment" rows="4" class="w-full p-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write your comment..."></textarea>
            <button type="submit" name="submit_comment" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Post Comment</button>
        </form>
    </div>

    <!-- Display Comments -->
    <div class="container mx-auto p-6 mt-8 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold">User Comments</h2>
        <?php if (count($comments) > 0): ?>
            <div class="comments-list mt-4 space-y-6">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment p-4 border rounded-lg shadow-md bg-gray-50">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold"><?php echo htmlspecialchars($comment['username']); ?></span>
                            <span class="text-xs text-gray-400"><?php echo date('F j, Y, g:i a', strtotime($comment['created_at'])); ?></span>
                        </div>
                        <p class="mt-2 text-gray-700"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>

                        <!-- Admin response -->
                        <?php if ($comment['admin_response']): ?>
                            <div class="admin-response mt-4 bg-gray-100 p-4 rounded-md">
                                <p><strong>Admin Response:</strong></p>
                                <p class="text-gray-800"><?php echo nl2br(htmlspecialchars($comment['admin_response'])); ?></p>
                                <span class="text-sm text-gray-500">Responded on <?php echo date('F j, Y, g:i a', strtotime($comment['response_created_at'])); ?></span>
                            </div>
                        <?php else: ?>
                            <!-- Form for Admin to respond -->
                            <form action="room_page.php?room_id=<?php echo $room_id; ?>" method="POST" class="mt-4">
                                <textarea name="response" placeholder="Write your response..." class="w-full p-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>" />
                                <button type="submit" name="submit" class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Respond</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="mt-4 text-gray-500">No comments yet. Be the first to leave a comment!</p>
        <?php endif; ?>
    </div>
</body>
</html>
