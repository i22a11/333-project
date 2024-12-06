<?php
session_start();
require_once 'room.php';  
require_once 'comment.php'; 

// Handle creating a new room
if (isset($_POST['create_room'])) {
    createRoom($_POST['room_name'], $_POST['capacity'], $_POST['equipment']);
    header("Location: admin_dashboard.php");  // Redirect after room creation
    exit();
}

// Handle editing an existing room
if (isset($_POST['edit_room'])) {
    updateRoom($_POST['room_id'], $_POST['room_name'], $_POST['capacity'], $_POST['equipment']);
    header("Location: admin_dashboard.php"); 
    exit();
}

// Handle deleting a room
if (isset($_GET['delete_room_id'])) {
    deleteRoom($_GET['delete_room_id']);
    header("Location: admin_dashboard.php");  
    exit();
}

// Get all rooms
function getAllRooms() {
    return getRooms();  // Fetch all rooms from the model
}

// Get a specific room
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    $room = getRoomById($room_id);  // Fetch specific room details
    $comments = getCommentsForRoom($room_id);  // Get comments for this room if needed
}

?>

