<?php
require 'db_connection.php';

/*// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	header("location: login.php");
	exit;
}*/

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$userID = $_SESSION['id'];
	$roomID = $_POST['room_id'];
	$date = $_POST['date'];
	$startTime = $_POST['start_time'];
	$endTime = $_POST['end_time'];

	//$result = bookRoom($_SESSION['id'], $roomID, $date, $startTime, $endTime);

	echo json_encode($result);
	exit;
}
// Function to book a room
function bookRoom($userID, $roomID, $date , $startTime, $sendTime){
    global $pdo;

	// Step 1: Check if the room is available
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS conflict_count 
        FROM bookings 
        WHERE room_id = :room_id 
          AND booking_date = :booking_date 
          AND status = 'confirmed'
          AND (
              (start_time < :end_time AND end_time > :start_time)
          )
    ");
	$stmt->execute([
		'room_id' => $roomID,
		'booking_date' => $date,
		'start_time' => $startTime,
		'end_time' => $endTime
	]);

	$conflictCount = $stmt->fetch(PDO::FETCH_ASSOC)['conflict_count'];

    if ($conflictCount > 0) {
        return ['success' => false, 'message' => 'Room is already booked during the selected timeslot.'];
    }

	// Step 2: Book the room if it is available

	$stmt = $pdo->prepare("
		INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time, status)
		VALUES (:user_id, :room_id, :booking_date, :start_time, :end_time,)"
	);

	$stmt->execute([
		'user_id' => $userID,
		'room_id' => $roomID,
		'booking_date' => $date,
		'start_time' => $startTime,
		'end_time' => $endTime
	]);

	return ['success' => true, 'message' => 'Room booked successfully.'];
}

// Function to cancel a booking
function cancelBooking($bookingID){
	global $pdo;

	$stmt = $pdo->prepare("
		UPDATE bookings
		SET status = 'cancelled'
		WHERE id = :id
	");

	$stmt->execute([
		'id' => $bookingID
	]);
	return ['success' => true, 'message' => 'Booking cancelled successfully.'];
}

?>
