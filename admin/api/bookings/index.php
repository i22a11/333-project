<?php
require_once __DIR__ . '/../../../db_connection.php';
header('Content-Type: application/json');

try {
    $db = db_connect();
    
    $stmt = $db->prepare("
        SELECT 
            b.id,
            b.user_id,
            b.room_id,
            b.check_in_date,
            b.check_out_date,
            b.status,
            b.created_at,
            r.name as room_name,
            u.name as user_name,
            u.email as user_email
        FROM bookings b
        JOIN rooms r ON b.room_id = r.id
        JOIN users u ON b.user_id = u.id
        ORDER BY b.created_at DESC
    ");
    
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $bookings
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch bookings'
    ]);
}
