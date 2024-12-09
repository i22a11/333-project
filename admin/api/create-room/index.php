<?php

include "../../../db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);

    // Required fields
    $name = trim($input["name"] ?? '');
    $capacity = trim($input["capacity"] ?? '');
    $equipment = trim($input["equipment"] ?? '');
    
    // Optional field
    $image_url = isset($input["image_url"]) ? trim($input["image_url"]) : null;

    if ($name === '' || $capacity === '' || $equipment === '') {
        echo json_encode([
            "success" => false,
            "message" => "Please fill in all required fields (name, capacity, and equipment)."
        ]);
        exit;
    }

    try {
        $db = db_connect();
        $query = "INSERT INTO Rooms (room_name, capacity, equipment, image_url) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$name, $capacity, $equipment, $image_url]);

        http_response_code(201);

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
