<?php
    
    require '../db_connection.php';
    $pdo = db_connect();

    // This is just to test the function 
    
    /*$bookings = getUserBookings(1);
    echo $bookings;*/

    // Function to get all bookings of a user 
    function getUserBookings($userId) {
        global $pdo;

        $stmt = $pdo->prepare("
            SELECT b.booking_id, b.start_time, b.end_time, b.status, r.room_name
            FROM Bookings b
            JOIN Rooms r ON b.room_id = r.room_id
            WHERE b.user_id = :user_id
            ORDER BY b.start_time DESC
        ");
        $stmt->execute([':user_id' => $userId]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)){
            http_response_code(404);
            return json_encode(array("error"=> true, "message" => "No bookings found"));
        }

        return json_encode($result);
    }

?>