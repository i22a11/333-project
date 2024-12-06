<?php

include "../../../db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    $name = $input["name"] ?? null;
    $capacity = $input["capacity"] ?? null;
    $equipment = $input["equipment"] ?? null;

    if (empty($name) || empty($capacity) || empty($equipment)) {
        echo json_encode([
            "success" => false,
            "message" => "Please fill in all fields."
        ]);
        exit;
    }

    try {
        $db = db_connect();
        $query = "INSERT INTO Rooms (room_name, capacity, equipment) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$name, $capacity, $equipment]);

        echo json_encode([
            "success" => true,
            "message" => "Room created successfully!"
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}
