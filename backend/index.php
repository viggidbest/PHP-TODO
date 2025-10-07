<?php
// backend/index.php
declare(strict_types=1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

require_once __DIR__ . '/TodoRepository.php';

$repo = new TodoRepository(__DIR__ . '/todo.sqlite3');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

function json_out($data, int $code = 200) {
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

if (strpos($path, '/api/todos') !== 0) {
    // Serve a friendly message
    json_out(['message' => 'Todo API running', 'endpoints' => ['/api/todos']], 200);
}

$id = null;
$parts = explode('/', trim($path, '/'));
if (count($parts) === 3 && is_numeric($parts[2])) {
    $id = (int)$parts[2];
}

$body = file_get_contents('php://input');
$payload = $body ? json_decode($body, true) : [];

try {
    switch ($method) {
        case 'GET':
            if ($id !== null) {
                $todo = $repo->get($id);
                $todo ? json_out($todo) : json_out(['error' => 'Not found'], 404);
            } else {
                json_out($repo->all());
            }
            break;
        case 'POST':
            if (!isset($payload['title']) || trim($payload['title']) === '') {
                json_out(['error' => 'title is required'], 422);
            }
            $todo = $repo->create($payload['title']);
            json_out($todo, 201);
            break;
        case 'PUT':
            if ($id === null) json_out(['error' => 'id required'], 400);
            $todo = $repo->update($id, [
                'title' => $payload['title'] ?? null,
                'done' => isset($payload['done']) ? (bool)$payload['done'] : null
            ]);
            $todo ? json_out($todo) : json_out(['error' => 'Not found'], 404);
            break;
        case 'DELETE':
            if ($id === null) json_out(['error' => 'id required'], 400);
            $ok = $repo->delete($id);
            json_out(['deleted' => $ok]);
            break;
        default:
            json_out(['error' => 'Method not allowed'], 405);
    }
} catch (Throwable $e) {
    json_out(['error' => 'Server error', 'detail' => $e->getMessage()], 500);
}
