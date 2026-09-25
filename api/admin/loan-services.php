<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

// Prevent session notice if already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Return 401 JSON instead of redirecting if session expired
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please log in again.']);
    exit;
}

$pdo = getPDO();
$section = 'loan-services';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $content = getSectionContent($pdo, $section);
        echo json_encode($content);
        exit;

    case 'POST':
        $input = file_get_contents('php://input');
        $body = json_decode($input, true);

        if (!$body) {
            http_response_code(400);
            echo json_encode(['error' => 'No valid data provided.']);
            exit;
        }

        // Wrap object back into array to preserve original schema
        $payload = (isset($body[0]) && is_array($body[0])) ? $body : [$body];

        saveContent($pdo, $section, $payload);
        echo json_encode(['message' => 'Loan Services updated successfully!', 'data' => $payload]);
        exit;

    default:
        http_response_code(405);
        echo json_encode(['error' => "Method '{$method}' not allowed."]);
        exit;
}