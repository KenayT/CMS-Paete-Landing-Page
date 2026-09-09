<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

session_start();
requireLogin();

$pdo = getPDO();
$section = 'header';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $content = getSectionContent($pdo, $section);
        $content['_arrayFields'] = getFieldPrefix($content);
        jsonResponse($content);
        break;

    case 'POST':
        $body = getRequestBody();
        $field = sanitize($body['field'] ?? '');
        $item = $body['item'] ?? null;

        if (empty($field) || $item === null) {
            jsonResponse(['error' => '"field" and "item" are required.'], 400);
        }

        $content = getSectionContent($pdo, $section);

        if (!isset($content[$field]) || !is_array($content[$field]) || !array_is_list($content[$field])) {
            jsonResponse(['error' => "'{$field}' is not an array field."], 422);
        }

        if (is_array($item)) {
            $item = array_map(fn($v) => is_string($v) ? sanitize($v) : $v, $item);
        } else {
            $item = sanitize((string)$item);
        }

        $content[$field][] = $item;
        saveContent($pdo, $section, $content);

        jsonResponse([
            'message' => 'Item added.',
            'field'   => $field,
            'items'   => $content[$field]
        ], 201);
        break;

    case 'PATCH':
        $body = getRequestBody();
        $field = sanitize($body['field'] ?? '');
        $value = $body['value'] ?? null;
        $index = isset($body['index']) ? (int)$body['index'] : null;

        if (empty($field) || $value === null) {
            jsonResponse(['error' => '"field" and "value" are required.'], 400);
        }

        $content = getSectionContent($pdo, $section);

        // Handle flat top-level logo & bank info fields
        if ($field === 'logo' || $field === 'bankInfo') {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $cleanKey = sanitize($k);
                    $content[$cleanKey] = is_string($v) ? sanitize($v) : $v;
                }
                saveContent($pdo, $section, $content);
                jsonResponse(['message' => 'Bank Info updated.', 'value' => $content]);
                exit;
            }
        }

        if (!array_key_exists($field, $content)) {
            jsonResponse(['error' => "Field '{$field}' does not exist."], 404);
        }

        if (array_is_list($content[$field] ?? [])) {
            if ($index === null || !isset($content[$field][$index])) {
                jsonResponse(['error' => 'Valid index is required for array fields.'], 400);
            }
            $cleanValue = is_array($value)
                ? array_map(fn($v) => is_string($v) ? sanitize($v) : $v, $value)
                : sanitize((string)$value);
            $content[$field][$index] = $cleanValue;
        } else {
            $cleanValue = is_array($value)
                ? array_map(fn($v) => is_string($v) ? sanitize($v) : $v, $value)
                : sanitize((string)$value);
            $content[$field] = is_array($content[$field])
                ? array_merge($content[$field], $cleanValue)
                : $cleanValue;
        }

        saveContent($pdo, $section, $content);

        jsonResponse([
            'message' => 'Updated.',
            'field'   => $field,
            'value'   => $content[$field]
        ]);
        break;

    case 'DELETE':
        $field = sanitize($_GET['field'] ?? '');
        $index = isset($_GET['index']) ? (int)$_GET['index'] : null;

        if (empty($field) || $index === null) {
            jsonResponse(['error' => '"field" and "index" query params are required.'], 400);
        }

        $content = getSectionContent($pdo, $section);

        if (!isset($content[$field]) || !array_is_list($content[$field])) {
            jsonResponse(['error' => "'{$field}' is not an array field."], 422);
        }

        if (!isset($content[$field][$index])) {
            jsonResponse(['error' => 'Index out of range.'], 404);
        }

        array_splice($content[$field], $index, 1);
        saveContent($pdo, $section, $content);

        jsonResponse([
            'message' => 'Item deleted.',
            'field'   => $field,
            'items'   => $content[$field]
        ]);
        break;

    default:
        jsonResponse(['error' => "Method '{$method}' not allowed."], 405);
}