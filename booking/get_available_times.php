<?php

require '../db_connection.php';
$pdo = db_connect();

$roomID = 1;
$date = '2021-07-01';

echo getAvailableTimeSlots($roomID, $date);

/*echo getAvailableTimeSlots($_POST['room_id'], $_POST['date']);*/

function getAvailableTimeSlots($roomID, $date){
    global $pdo;
    
    $availableSlots = [];
    // Set the time slots intrvals (we'll use it to get the available time slots)
    $start_time = strtotime('07:00 AM'); // This function converts the string to a timestamp in 24-hour format
    $end_time = strtotime('05:00 PM');
    $interval = 60 * 60; // 60 minutes interval

    // Get all the booked times for the room on the given date
    $stmt = $pdo->prepare("SELECT time FROM Bookings WHERE room_id = :room_id AND date = :date");
    $stmt->execute(['room_id' => $roomID, 'date' => $date]);

    $bookedTimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Get only the time from the result and convert it to an array
    $bookedTimes = array_column($bookedTimes, 'time');

    // Loop through the time slots and check if the time slot is available and add it 
    for($time = $start_time; $time <= $end_time; $time += $interval){
        $timeSlot = date('H:i', $time); // Format the time to hour:minute
        if(!in_array($timeSlot, $bookedTimes)){
            $availableSlots[] = $timeSlot;
        }
    }

    // Return the available time slots with a 10 minutes break in between example: 07:00 - 07:50
    for($i=0; $i<count($availableSlots); $i++){
        $startTime = strtotime($availableSlots[$i]);
        $endTime = $startTime + (50 * 60); // Start time + 50 minutes

        $availableSlots[$i] = date('g:i A', $startTime) . ' - ' . date('g:i A', $endTime); // The date('g:i A') convert time to am and pm (g=12-hour format, i=minutes, A= am or pm)
    }

    //If no time slots are available, return an error message
    if(empty($availableSlots)){
        return json_encode(array("error"=> true, "message" => "No time slots available for the selected date"));
    }
    
    return json_encode($availableSlots);
}
?>