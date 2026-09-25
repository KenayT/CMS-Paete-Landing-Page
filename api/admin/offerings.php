<?php
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';
require_once '../../config/database.php';

session_start();
requireLogin();

header('Content-Type: application/json');

$pdo = getPDO();
$method = $_SERVER['REQUEST_METHOD'];

// Load current section content
$stmt = $pdo->prepare("SELECT content FROM page_sections WHERE section = 'offerings'");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    jsonResponse(['error' => 'Section not found'], 404);
}

$content = json_decode($row['content'], true);

switch ($method) {
    case 'GET':
        jsonResponse($content);
        break;

    case 'PATCH':
        $body = getRequestBody();
        $field = $body['field'] ?? '';
        $value = $body['value'] ?? null;
        $index = $body['index'] ?? null;

        if (!isset($content[0])) {
            $content[0] = [];
        }

        if ($index !== null && isset($content[0][$field]) && is_array($content[0][$field])) {
            $content[0][$field][$index] = sanitize($value);
        } else {
            $content[0][$field] = is_array($value) ? $value : sanitize($value);
        }

        $stmt = $pdo->prepare("UPDATE page_sections SET content = ? WHERE section = 'offerings'");
        $stmt->execute([json_encode($content, JSON_UNESCAPED_UNICODE)]);

        jsonResponse([
            'message' => 'Updated successfully',
            'field' => $field,
            'value' => $content[0][$field] ?? $value
        ]);
        break;

    case 'POST':
        $body = getRequestBody();
        $field = $body['field'] ?? '';
        $item = $body['item'] ?? '';

        if (!isset($content[0][$field]) || !is_array($content[0][$field])) {
            jsonResponse(['error' => 'Field is not an array'], 422);
        }

        if (trim($item) === '') {
            jsonResponse(['error' => 'Item cannot be empty'], 400);
        }

        $sanitizedItem = is_array($item) ? $item : sanitize($item);
        $content[0][$field][] = $sanitizedItem;

        $stmt = $pdo->prepare("UPDATE page_sections SET content = ? WHERE section = 'offerings'");
        $stmt->execute([json_encode($content, JSON_UNESCAPED_UNICODE)]);

        $formattedItems = array_map(fn($val) => ['name' => $val], $content[0][$field]);

        jsonResponse([
            'message' => 'Item added',
            'field' => $field,
            'items' => $formattedItems
        ]);
        break;

    case 'DELETE':
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $field = $body['field'] ?? $_GET['field'] ?? '';
        $index = $body['index'] ?? $_GET['index'] ?? null;

        if ($index === null || !isset($content[0][$field]) || !is_array($content[0][$field])) {
            jsonResponse(['error' => 'Invalid delete request'], 422);
        }

        array_splice($content[0][$field], (int)$index, 1);

        $stmt = $pdo->prepare("UPDATE page_sections SET content = ? WHERE section = 'offerings'");
        $stmt->execute([json_encode($content, JSON_UNESCAPED_UNICODE)]);

        $formattedItems = array_map(fn($val) => ['name' => $val], $content[0][$field]);

        jsonResponse([
            'message' => 'Item deleted',
            'field' => $field,
            'items' => $formattedItems
        ]);
        break;

    default:
        jsonResponse(['error' => 'Method not allowed'], 405);
}