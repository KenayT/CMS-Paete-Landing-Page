<?php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

session_start();
requireLogin();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$pdo = getPDO();

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT content FROM page_sections WHERE section = 'info-boxes'");
    $stmt->execute();
    $row = $stmt->fetch();
    echo $row ? $row['content'] : json_encode([]);
    exit;
}

if ($method === 'PUT' || $method === 'POST') {
    $rawInput = file_get_contents('php://input');
    $decoded = json_decode($rawInput, true);

    if ($decoded === null) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE page_sections SET content = :content, updated_at = NOW() WHERE section = 'info-boxes'");
    $stmt->execute([':content' => json_encode($decoded)]);

    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);