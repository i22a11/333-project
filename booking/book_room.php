<?php
    require '../db_connection.php';
    $pdo = db_connect();

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
        $time = $_POST['time'];

        $result = bookRoom($userID, $roomID, $date, $time, $pdo);

        // Return the result as JSON to the javascript
        echo json_encode($result);
        exit;
    }

    // Function to book a room
    function bookRoom($userID, $roomID, $date, $time, $pdo) {
        try {
            // Step 1: Check if the room is available
            $stmt = $pdo->prepare("
                SELECT * FROM Bookings 
                WHERE room_id = :room_id 
                AND status = 'confirmed' 
                AND date = :date
                AND time = :time
            ");

            $stmt->execute([
                ':room_id' => $roomID,
                ':date' => $date,
                ':time' => $time,
            ]);

            // Check if the room is already booked for the selected time
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Room is already booked for the selected time.'];
            }

            // Step 2: Book the room if it is available
            $insert = $pdo->prepare("
                INSERT INTO Bookings (user_id, room_id, date, time, status) 
                VALUES (:user_id, :room_id, :date, :time, 'pending')
            "); // Status is set to pending by default
            $insert->execute([
                ':user_id' => $userID,
                ':room_id' => $roomID,
                ':date' => $date,
                ':time' => $time,
            ]);

            return ['success' => true, 'message' => 'Booking request submitted.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'An error occurred. Please try again.'];
        }
    }
?>
