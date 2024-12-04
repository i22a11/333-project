<?php
$user_id = $_SESSION['user_id']; 
$notifications = getUserNotifications($user_id);
?>

<div class="notifications mt-8">
    <h2 class="text-2xl font-semibold">Notifications</h2>

    <div class="notification-list space-y-6 mt-6">
        <?php foreach ($notifications as $notification): ?>
            <div class="notification p-4 border rounded-lg shadow-md bg-white <?php echo $notification['is_read'] ? 'bg-gray-200' : ''; ?>">
                <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($notification['message'])); ?></p>
                <span class="text-sm text-gray-400"><?php echo date('F j, Y, g:i a', strtotime($notification['created_at'])); ?></span>
                <form action="mark_as_read.php" method="POST" class="mt-2">
                    <input type="hidden" name="notification_id" value="<?php echo $notification['notification_id']; ?>" />
                    <button type="submit" class="text-blue-500 hover:underline">Mark as Read</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
