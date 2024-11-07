<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$config = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'Yug@1jindal', // Ensure this is correct
    'database' => 'test'
];

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Get the request body
$requestData = json_decode(file_get_contents('php://input'), true);

if (!isset($requestData['query'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No query provided']);
    exit;
}

try {
    // Create PDO connection
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset=utf8",
        $config['user'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Prepare and execute the query
    $stmt = $pdo->prepare($requestData['query']);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll();

    // Send response
    echo json_encode([
        'success' => true,
        'data' => $results
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
