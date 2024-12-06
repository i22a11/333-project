<?php
require_once 'db_connection.php';

try {
    $conn = db_connect();
    $stmt = $conn->prepare("
        SELECT 
            b.booking_id,
            b.date,
            b.time,
            b.status,
            r.room_name,
            r.capacity,
            u.name as user_name
        FROM Bookings b 
        JOIN Rooms r ON b.room_id = r.room_id 
        JOIN Users u ON b.user_id = u.user_id 
        ORDER BY b.date DESC, b.time DESC 
        LIMIT 10
    ");
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>333 Project Website!</title>
    <link rel="stylesheet" href="output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-zinc-900 text-zinc-100">
    <!-- Navigation -->
    <nav class="bg-zinc-800 border-b border-zinc-700">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-xl font-bold text-zinc-100">333 Project Website!</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Page header -->
        <div class="px-4 py-6 sm:px-0">
            <h1 class="text-3xl font-bold text-zinc-100">Dashboard</h1>
            <p class="mt-2 text-sm text-zinc-400">Welcome to the 333 Project Website!</p>
        </div>

        <!-- Main content -->
        <div class="px-4 py-6 sm:px-0">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Your Content -->
                <div class="bg-zinc-800 shadow-lg rounded-lg overflow-hidden border border-zinc-700">
                    <div class="px-6 py-4 border-b border-zinc-700">
                        <h2 class="text-xl font-semibold text-zinc-100">Your Content</h2>
                    </div>
                    <div class="p-6">
                        <h1>333 Project Website!</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>