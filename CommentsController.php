<?php
session_start();
require_once '../models/db.php'; 
require_once '../models/comment.php'; 

class CommentsController {

    // Show all comments for a specific room 
    public function showComments($room_id) {
        // Fetch comments for the specific room
        $comments = getCommentsForRoom($room_id);

        // Load the comments view and pass the comments data
        require_once 'comments_view.php'; 
    }

    // Add a new comment
    public function addComment($room_id) {
        // Check if the comment form is submitted
        if (isset($_POST['submit_comment'])) {
            $comment = $_POST['comment'];
            $user_id = $_SESSION['user_id']; // Assuming the user is logged in

            
            if (!empty($comment)) {
                // Add the comment to the database
                $result = addCommentToRoom($user_id, $room_id, $comment);

                // Redirect 
                if ($result) {
                    header("Location: room_page.php?room_id=" . $room_id);
                    exit();
                } else {
                    // Handle failure
                    echo "Error: Could not add the comment.";
                }
            } else {
                echo "Comment cannot be empty.";
            }
        }
    }

    // Respond to a comment 
    public function respondToComment($comment_id) {
        // Check if the admin response form is submitted
        if (isset($_POST['submit_response'])) {
            $response = $_POST['response'];
            $admin_id = $_SESSION['user_id']; // Assuming the admin is logged in

        
            if (!empty($response)) {
                // Respond 
                $result = respondToComment($comment_id, $admin_id, $response);

                // Redirect 
                if ($result) {
                    header("Location: admin_comments_page.php?room_id=" . $_GET['room_id']);
                    exit();
                } else {
                    // Handle failure
                    echo "Error: Could not respond to the comment.";
                }
            } else {
                echo "Response cannot be empty.";
            }
        }
    }

    // Fetch comments for the admin panel 
    public function getCommentsForAdmin($room_id) {
        // Fetch all comments for the room 
        $comments = getCommentsForAdmin($room_id);

        // Load the admin comments view to show all comments with response options
        require_once '../views/admin_comments_view.php'; 
    }
}
