<?php
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

session_start();
requireLogin();

header('Content-Type: application/json');

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT content FROM page_sections WHERE section = 'footer'");
    $stmt->execute();
    $row = $stmt->fetch();

    if ($row) {
        echo $row['content'];
    } else {
        echo json_encode([
            "brandName" => "Rural Bank of Paete, Inc.",
            "copyright" => "© 2026 Rural Bank of Paete, Inc. All rights reserved.",
            "links" => [
                ["label" => "Home", "url" => "index.html"],
                ["label" => "About Us", "url" => "about.html"],
                ["label" => "Privacy Policy", "url" => "#"],
                ["label" => "Terms of Service", "url" => "#"]
            ]
        ]);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!is_array($data) || !isset($data['brandName'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid data payload.']);
        exit();
    }

    $jsonPayload = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $stmt = $pdo->prepare("
        INSERT INTO page_sections (section, content, updated_at)
        VALUES ('footer', :content, NOW())
        ON DUPLICATE KEY UPDATE content = VALUES(content), updated_at = NOW()
    ");

    $success = $stmt->execute([':content' => $jsonPayload]);

    echo json_encode(['success' => $success]);
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method Not Allowed']);