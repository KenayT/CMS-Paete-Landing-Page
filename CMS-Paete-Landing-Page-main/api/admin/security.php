<?php

/**
 * api/admin/security.php
 * ------------------------------------------------------------------
 * The ONLY file that touches the database for the "security" section.
 * Pure logic in, pure JSON out — no HTML, no <script>, ever.
 *
 * IMPORTANT — Paete's data shape:
 * src/data/security.json (and most other sections) is a JSON ARRAY
 * that wraps a single settings object:
 *
 *   [ { "id": 1, "icon": "...", "items": [...], "cta": {...}, ... } ]
 *
 * setup.sql seeds page_sections.content with that exact shape, so
 * getSectionContent() returns a list with ONE element. Every field
 * (icon, items, cta, etc.) lives inside $section[0], not $section
 * itself. That's the one real difference from the flat-object
 * "navbar" example in the task doc — everything else follows the
 * same GET/POST/PATCH/DELETE pattern.
 */

declare(strict_types=1);

const SECTION = 'security';

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';

header('Content-Type: application/json');

session_start();
requireApiLogin(); // 401 JSON if there's no admin session — never a redirect

$pdo = getPDO();

// Unwrap the single settings object out of the seeded array shape.
$section = getSectionContent($pdo, SECTION); // e.g. [ { ...fields... } ]
$content = $section[0] ?? [];

// Which top-level fields are lists? Only those get Create/Delete.
// e.g. for security.json this returns ['items'].
$listFields = getFieldPrefix($content);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // ---- GET — read the current section content -------------------
    case 'GET':
        jsonResponse($content);
        break;

    // ---- PATCH — update a field (object/scalar), or one array item -
    case 'PATCH':
        $body  = getRequestBody();
        $field = $body['field'] ?? null;
        $value = $body['value'] ?? null;
        $index = $body['index'] ?? null; // only set when editing items[N]

        if (!$field || !array_key_exists($field, $content)) {
            jsonResponse(['error' => "Field '{$field}' does not exist."], 404);
        }

        if ($index !== null) {
            // Editing a single item inside a list field, e.g. items[1]
            if (!in_array($field, $listFields, true) || !isset($content[$field][$index])) {
                jsonResponse(['error' => "Invalid index for field '{$field}'."], 422);
            }
            $content[$field][$index] = sanitizeArray($value);
        } else {
            // Replacing an object field (cta) or a plain scalar field
            $content[$field] = sanitizeArray($value);
        }

        $section[0] = $content;
        saveContent($pdo, SECTION, $section);

        jsonResponse([
            'message' => 'Updated.',
            'field'   => $field,
            'value'   => $content[$field],
        ]);
        break;

    // ---- POST — add a new item to an array field only --------------
    case 'POST':
        $body  = getRequestBody();
        $field = $body['field'] ?? null;
        $item  = $body['item']  ?? null;

        if (!in_array($field, $listFields, true)) {
            jsonResponse([
                'error' => "'{$field}' is not an array field. Only array fields support Create.",
            ], 422);
        }

        $content[$field][] = sanitizeArray($item);

        $section[0] = $content;
        saveContent($pdo, SECTION, $section);

        jsonResponse([
            'message' => 'Item added.',
            'field'   => $field,
            'items'   => $content[$field], // updated array so JS can re-render
        ]);
        break;

    // ---- DELETE — remove one item from an array field ---------------
    // Query params: ?field=items&index=2
    case 'DELETE':
        $field = sanitize($_GET['field'] ?? '');
        $index = $_GET['index'] ?? null;

        if ($field === '' || $index === null) {
            jsonResponse(['error' => '"field" and "index" query params are required.'], 400);
        }

        if (!in_array($field, $listFields, true) || !isset($content[$field][(int) $index])) {
            jsonResponse([
                'error' => "'{$field}' is not an array field, or index is out of range. Only array fields support Delete.",
            ], 422);
        }

        array_splice($content[$field], (int) $index, 1);

        $section[0] = $content;
        saveContent($pdo, SECTION, $section);

        jsonResponse([
            'message' => 'Item deleted.',
            'field'   => $field,
            'items'   => $content[$field],
        ]);
        break;

    default:
        jsonResponse(['error' => "Method '{$method}' not allowed."], 405);
}
