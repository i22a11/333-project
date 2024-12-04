<?php
    require '../db_connection.php';
    $pdo = db_connect();

    // Check if the user is logged in, if not then redirect him to login page
    /*if (!isset($_SESSION['user_id'])) {
        header("location: login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit;
    } else {
        echo cancelBooking($_SESSION['user_id'], $_POST['booking_id']);
    }*/

    echo cancelBooking(1, 1);

    function cancelBooking($bookingId, $userId) {
        global $pdo;

        $stmt = $pdo->prepare("
            UPDATE Bookings 
            SET status = 'cancelled' 
            WHERE booking_id = :booking_id AND user_id = :user_id AND status = 'confirmed'
        ");
        $stmt->execute([
            ':booking_id' => $bookingId,
            ':user_id' => $userId,
        ]);

        return json_encode(['success' => $stmt->rowCount() > 0, 'message' => $stmt->rowCount() > 0 ? 'Booking cancelled successfully.' : 'Cancellation failed.']);
    }
?>