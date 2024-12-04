<?php
$room_id = $_GET['room_id']; 
$comments = getCommentsForAdmin($room_id);
?>

<div class="comments-section mt-8">
    <h2 class="text-2xl font-semibold">Manage Comments for Room</h2>

    <!-- Display all comments -->
    <div class="comments-list space-y-6 mt-6">
        <?php foreach ($comments as $comment): ?>
            <div class="comment p-4 border rounded-lg shadow-md bg-white">
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
                    <!-- Form to reply to the comment -->
                    <form action="respond_to_comment.php" method="POST" class="mt-4">
                        <textarea name="response" placeholder="Write your response..." class="w-full p-4 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>" />
                        <button type="submit" name="submit" class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Respond</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
