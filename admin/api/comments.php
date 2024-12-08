<?php
require_once '../../db_connection.php';
require_once '../../base.php';

// Start session and check admin privileges
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get all comments with user and room information
$query = "
    SELECT 
        c.comment_id,
        c.comment,
        c.admin_response,
        c.is_resolved,
        c.created_at,
        u.name as user_name,
        r.room_name,
        CASE 
            WHEN c.admin_id IS NOT NULL THEN admin.name 
            ELSE NULL 
        END as admin_name
    FROM Comments c
    JOIN Users u ON c.user_id = u.user_id
    JOIN Rooms r ON c.room_id = r.room_id
    LEFT JOIN Users admin ON c.admin_id = admin.user_id
    ORDER BY c.created_at DESC
";

try {
    $pdo = db_connect();
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($comments);
} catch (PDOException $e) {
    error_log("Database error in comments.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
