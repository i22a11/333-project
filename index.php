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
    <title>Room Bookings</title>
    <link rel="stylesheet" href="output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-xl font-bold text-gray-800">Room Booking System</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="profile.php" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow-lg px-8 py-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Recent Bookings</h2>
                    <a href="book.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        <i class="fas fa-plus mr-2"></i>New Booking
                    </a>
                </div>

                <!-- Bookings Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-8 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($bookings)): ?>
                                <?php foreach ($bookings as $booking): ?>
                                <tr class="hover:bg-gray-50 h-[100px]">
                                    <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($booking['user_name']); ?>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($booking['room_name']); ?>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo htmlspecialchars($booking['capacity']); ?> people
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo date('F j, Y', strtotime($booking['date'])); ?>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo date('g:i A', strtotime($booking['time'])); ?>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full 
                                            <?php echo $booking['status'] === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                            ($booking['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo ucfirst(htmlspecialchars($booking['status'])); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="px-8 py-6 text-center text-sm text-gray-500">
                                        No bookings found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>