<?php
session_start();
require_once 'db.php';

function getUserNotifications($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function markNotificationAsRead($notification_id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Notifications SET is_read = 1 WHERE notification_id = ?");
    $stmt->bind_param("i", $notification_id);
    $stmt->execute();
}
?>
