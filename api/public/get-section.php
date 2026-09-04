<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

try {
    require_once dirname(__DIR__, 2) . '/config/database.php';
    require_once dirname(__DIR__, 2) . '/includes/functions.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed.']);
        exit;
    }

    $section = $_GET['section'] ?? '';
    if (empty($section)) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing ?section= parameter.']);
        exit;
    }

    $pdo = getPDO();
    $cleanSection = sanitize($section);
    $content = getSectionContent($pdo, $cleanSection);

    echo json_encode($content);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server Error',
        'message' => $e->getMessage()
    ]);
    exit;
}