<?php

require_once __DIR__ . '/../../../../db_connection.php';
header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'success' => false, 
        'message' => 'Unauthorized access',
        'debug' => [
            'user_id' => isset($_SESSION['user_id']),
            'user_role' => $_SESSION['user_role'] ?? 'not set'
        ]
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$booking_id = $data['booking_id'] ?? null;
$status = $data['status'] ?? null;

if (!$booking_id || !$status || !in_array($status, ['pending', 'confirmed', 'cancelled'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid booking ID or status']);
    exit;
}

try {
    $pdo = db_connect();
    
    $stmt = $pdo->prepare("
        UPDATE Bookings 
        SET status = ?, 
            admin_id = ? 
        WHERE booking_id = ?
    ");
    $stmt->execute([$status, $_SESSION['user_id'], $booking_id]);
    
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Booking not found'
        ]);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Booking status updated successfully'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred',
        'debug' => [
            'error_code' => $e->getCode(),
            'error_message' => $e->getMessage()
        ]
    ]);
}
