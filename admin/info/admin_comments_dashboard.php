<?php
include('db.php');
include('CommentController.php');

// Fetch all comments
$comments = getAllComments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Comments Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard - Comments</h1>
    <h2>All Comments</h2>

    <?php foreach ($comments as $comment) { ?>
        <div class="comment">
            <p><strong>User:</strong> <?php echo $comment['user_name']; ?></p>
            <p><strong>Comment:</strong> <?php echo $comment['comment']; ?></p>

            <?php if ($comment['admin_response']) { ?>
                <p><strong>Admin Response:</strong> <?php echo $comment['admin_response']; ?></p>
            <?php } else { ?>
                <!-- If no admin response, show the response form -->
                <form action="../controllers/CommentController.php" method="POST">
                    <textarea name="response" placeholder="Write your response..." required></textarea>
                    <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>" />
                    <button type="submit">Submit Response</button>
                </form>
            <?php } ?>
        </div>
    <?php } ?>

</body>
</html>
