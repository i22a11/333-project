<?php
// Session is already started in the main page
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<nav class="bg-zinc-800 border-b border-zinc-700">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <a href="../index.php" class="text-xl font-bold text-zinc-100">Room Booking System</a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-zinc-300">Welcome <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest, please login'); ?></span>
                <a href="../index.php" class="text-zinc-300 hover:text-zinc-100 px-3 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="/auth/logout.php" class="text-zinc-300 hover:text-zinc-100 px-3 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </div>
</nav>