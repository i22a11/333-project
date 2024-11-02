<?php 

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $db = new PDO("mysql:host=127.0.0.1;dbname=csDB", "cs-user", "xqyCsCu");

    $query = "SELECT * FROM Rooms";
    $stmt = $db->query($query);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rooms);
}
