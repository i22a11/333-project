<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("location: ../auth/login.php");
    exit;
}

include 'past_upcoming_bookings.php';

$pastBookings = getPastUpcomingBookings("past");
$upcomingBookings = getPastUpcomingBookings("upcoming");

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>333 Project Website!</title>
    <link rel="stylesheet" href="../output.css">
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
            <h1 class="text-3xl font-bold text-zinc-100">Reporting & Analytics</h1>
            <p class="mt-2 text-sm text-zinc-400">Here you can view our room usage and your past and upcoming bookings!</p>
        </div>

        <!-- Main content -->
        <div class="px-4 py-6 sm:px-0 space-y-4">
            <div>
                <!-- Room Usage and Popularity -->
                <div class="bg-zinc-800 shadow-lg rounded-lg overflow-hidden border border-zinc-700">
                    <div class="px-6 py-4 border-b border-zinc-700">
                        <h2 class="text-xl font-semibold text-zinc-100">Room Usage and Popularity</h2>
                    </div>
                    <div class="p-6">
                        <h1>_________________________</h1>
                    </div>
                </div>
            </div>
            <div>
                <!-- Your Content -->
                <div class="bg-zinc-800 shadow-lg rounded-lg overflow-hidden border border-zinc-700">
                    <div class="px-6 py-4 border-b border-zinc-700">
                        <h2 class="text-xl font-semibold text-zinc-100">Upcoming Bookings</h2>
                    </div>
                    <div class="p-6">
                        <?php
                            foreach($upcomingBookings as $row => $booking){
                                echo '<div class="flex justify-between items-center mb-4">';
                                echo '<div>';
                                echo '<h3 class="text-lg font-semibold">' . $booking['room_name'] . '</h3>';
                                echo '<p class="text-sm text-zinc-400">' . $booking['date'] . ' ' . $booking['time'] . '</p>';
                                echo '</div>';
                                echo '<div>';
                                /*echo '<a href="bookingDetails.php?booking_id=' . $booking['booking_id'] . '" class="text-blue-500 hover:text-blue-700">View Details</a>';*/
                                echo '</div>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div>
                <!-- Your Content -->
                <div class="bg-zinc-800 shadow-lg rounded-lg overflow-hidden border border-zinc-700">
                    <div class="px-6 py-4 border-b border-zinc-700">
                        <h2 class="text-xl font-semibold text-zinc-100">Past Bookings</h2>
                    </div>
                    <div class="p-6">
                        <?php
                            foreach($pastBookings as $row => $booking){
                                echo '<div class="flex justify-between items-center mb-4">';
                                echo '<div>';
                                echo '<h3 class="text-lg font-semibold">' . $booking['room_name'] . '</h3>';
                                echo '<p class="text-sm text-zinc-400">' . $booking['date'] . ' ' . $booking['time'] . '</p>';
                                echo '</div>';
                                echo '<div>';
                                /*echo '<a href="bookingDetails.php?booking_id=' . $booking['booking_id'] . '" class="text-blue-500 hover:text-blue-700">View Details</a>';*/
                                echo '</div>';
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>