<?php
    require 'db_connection.php';

    // Check if the user is logged in, if not then redirect him to login page
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        echo json_encode(getUserBookings($userId));
    } else {
        header("location: login.php");
        exit;
    }

    // Handle the form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['id'];
        $roomID = $_POST['room_id'];
        $date = $_POST['date'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];

        $result = bookRoom($_SESSION['id'], $roomID, $date, $startTime, $endTime);

        echo json_encode($result);
        exit;
    }

    // Function to book a room
    function bookRoom($userID, $roomID , $startTime, $sendTime){
        global $pdo;

        // Step 1: Check if the room is available
        $stmt = $pdo->prepare("
            SELECT * FROM Bookings 
            WHERE room_id = :room_id 
            AND status = 'confirmed' 
            AND (
                (start_time <= :end_time AND end_time >= :start_time)
            )
        ");

        $stmt->execute([
            ':room_id' => $roomId,
            ':start_time' => $startTime,
            ':end_time' => $endTime,
        ]);

        // Check if the room is already booked for the selected time
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Room is already booked for the selected time.'];
        }

        // Step 2: Book the room if it is available
        $insert = $pdo->prepare("
            INSERT INTO Bookings (user_id, room_id, start_time, end_time, status) 
            VALUES (:user_id, :room_id, :start_time, :end_time, 'pending')
        ");
        $insert->execute([
            ':user_id' => $userId,
            ':room_id' => $roomId,
            ':start_time' => $startTime,
            ':end_time' => $endTime,
        ]);

        return ['success' => true, 'message' => 'Booking request submitted.'];
    }
?>
