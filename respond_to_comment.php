<?php
session_start();
require_once 'comment.php';

if (isset($_POST['submit'])) {
    $comment_id = $_POST['comment_id'];
    $admin_response = $_POST['response'];
    $admin_id = $_SESSION['user_id']; // Assuming admin is logged in

    // Respond and send notification
    respondToComment($comment_id, $admin_id, $admin_response);

    // Redirect to the admin page
    header("Location: admin_comments_page.php?room_id=" . $_GET['room_id']);
    exit();
}
?>
