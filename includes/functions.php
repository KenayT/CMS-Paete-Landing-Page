<?php

function jsonResponse(mixed $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function sanitize(string $input): string
{
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function getFieldPrefix(array $content): array
{
    return array_keys(
        array_filter($content, fn($v) => is_array($v) && array_is_list($v))
    );
}

function getRequestBody(): array
{
    $raw = file_get_contents('php://input');

    if (empty($raw)) {
        jsonResponse(['error' => 'Request body is empty.'], 400);
    }

    $data = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        jsonResponse(['error' => 'Invalid JSON: ' . json_last_error_msg()], 400);
    }

    return $data;
}

/**
 * Like sanitize(), but walks into arrays/objects so nested values
 * (e.g. a "cta": {text,url} object, or an array of {title,text} items)
 * get every string leaf cleaned, not just top-level scalars.
 */
function sanitizeArray(mixed $value): mixed
{
    if (is_string($value)) {
        return sanitize($value);
    }
    if (is_array($value)) {
        return array_map('sanitizeArray', $value);
    }
    return $value; // numbers, bools, null pass through unchanged
}

function getSectionContent(PDO $pdo, string $section): array
{
    $stmt = $pdo->prepare("SELECT content FROM page_sections WHERE section = ?");
    $stmt->execute([$section]);
    $raw = $stmt->fetchColumn();

    if ($raw === false) {
        jsonResponse(['error' => "Section '{$section}' not found."], 404);
    }

    return json_decode($raw, true);
}

function saveContent(PDO $pdo, string $section, array $content): void
{
    $stmt = $pdo->prepare("
        UPDATE page_sections SET content = ? WHERE section = ?
    ");

    $stmt->execute([
        json_encode($content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        $section
    ]);
}