<?php

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$file = 'data.json';

// Read the JSON file
$data = json_decode(file_get_contents($file), true);

switch ($method) {
    case 'GET':
        // Read
        echo json_encode($data);
        break;

    case 'POST':
        // Create
        $input = json_decode(file_get_contents('php://input'), true);
        $data[] = $input;
        file_put_contents($file, json_encode($data));
        echo json_encode(['message' => 'Created']);
        break;

    case 'PUT':
        // Update
        $input = json_decode(file_get_contents('php://input'), true);
        foreach ($data as &$item) {
            if ($item['id'] === $input['id']) {
                $item = $input;
            }
        }
        file_put_contents($file, json_encode($data));
        echo json_encode(['message' => 'Updated']);
        break;

    case 'DELETE':
        // Delete
        $input = json_decode(file_get_contents('php://input'), true);
        $data = array_filter($data, function ($item) use ($input) {
            return $item['id'] !== $input['id'];
        });
        file_put_contents($file, json_encode($data));
        echo json_encode(['message' => 'Deleted']);
        break;

    default:
        echo json_encode(['message' => 'Method not allowed']);
        break;
}
