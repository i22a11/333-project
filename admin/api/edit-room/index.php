<?php

include "../../../db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Decode JSON input
    $input = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['capacity']) || !isset($input['equipment'])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "All fields (id, name, capacity, equipment) are required."
        ]);
        return;
    }

    try {
        $db = db_connect();
        
        $stmt = $db->prepare("UPDATE Rooms SET room_name = ?, capacity = ?, equipment = ? WHERE room_id = ?");
        $stmt->execute([
            $input['name'],
            $input['capacity'],
            $input['equipment'],
            $input['id']
        ]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Room not found."
            ]);
            return;
        }

        echo json_encode([
            "success" => true,
            "message" => "Room updated successfully."
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}
