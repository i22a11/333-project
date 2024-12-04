<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("location: ./demo_login/demo_login.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Responsive Sidebar Menu</title>
    <link rel="stylesheet" href="sidebar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-title">
            <div class="sidebar-title-name"></div>
            <i class='bx bx-menu' id="sidebar-button"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="?page=profile">
                    <i class='bx bx-user'></i>
                    <span class="navbar-item-name">User Profile</span>
                </a>
                <span class="tooltip">User Profile</span>
            </li>
            <li>
                <a href="?page=room-browsing">
                    <i class='bx bx-search-alt-2'></i>
                    <span class="navbar-item-name">Room Browsing</span>
                </a>
                <span class="tooltip">Room Browsing</span>
            </li>
            <li>
                <a href="?page=room-booking">
                    <i class='bx bx-calendar'></i>
                    <span class="navbar-item-name">Room Booking</span>
                </a>
                <span class="tooltip">Room Booking</span>
            </li>
            <li>
                <a href="?page=analytics">
                    <i class='bx bx-pie-chart-alt-2'></i>
                    <span class="navbar-item-name">Reporting & Analytics</span>
                </a>
                <span class="tooltip">Reporting & Analytics</span>
            </li>
            <li>
                <a href="?page=feedback">
                    <i class='bx bx-chat'></i>
                    <span class="navbar-item-name">Feedback</span>
                </a>
                <span class="tooltip">Feedback</span>
            </li>
            <li class="profile">
                <span class="text-white text-center">LOGOUT</span>
                <i class='bx bx-log-out' id="logout"></i>
            </li>
        </ul>
    </div>

    <section class="home-section" class="h-full w-full">
        <header class="">
            <!-- Navbar -->
            <nav class="shadow-md py-3 px-3 ">
                <div class="max-w-6xl px-4 py-3 flex justify-between items-center">
                    <!-- Project Name (Logo) -->
                    <div class="text-2xl font-semibold">
                        <a href="#">ProjectName</a>
                    </div>
                </div>
            </nav>
        </header>

        <div class="h-screen bg-white">
            <?php
            // Check the page parameter in the URL
            if (isset($_GET['page'])) {
                $page = $_GET['page'];

                // Include different content based on the page value
                switch ($page) {
                    case 'profile':
                        include('profile.php');
                        break;
                    case 'room-browsing':
                        include('room-browsing.php');
                        break;
                    case 'room-booking':
                        include('booking/room-booking.html');
                        break;
                    case 'analytics':
                        include('analytics.php');
                        break;
                    case 'feedback':
                        include('feedback.php');
                        break;
                    default:
                        include('home.php');
                        break;
                }
            } /*else {
                include('home.php'); // Default page content
            }*/
            ?>
        </div>
    </section>

</body>

</html>

<script src="sidebar.js"></script>
