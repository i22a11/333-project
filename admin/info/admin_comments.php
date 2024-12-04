<?php
session_start();
require_once 'db.php'; 

// Fetch all comments for the specific room
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

// Function to respond to a comment
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
?>
