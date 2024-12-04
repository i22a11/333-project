<?php
    require 'db_connection.php';

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

        return ['success' => $stmt->rowCount() > 0, 'message' => $stmt->rowCount() > 0 ? 'Booking cancelled successfully.' : 'Cancellation failed or not allowed.'];
    }

    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'];
        $bookingId = $data['booking_id'] ?? null;
    
        if ($bookingId) {
            echo json_encode(cancelBooking($bookingId, $userId));
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid booking ID.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    }
?>