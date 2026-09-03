<?php

header("Content-Type: application/json");

require_once __DIR__ . '/../Model/DatabaseConnection.php';

$itemId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$itemId) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid item ID."
    ]);

    exit;
}

$database = new DatabaseConnection();
$connection = $database->openConnection();

$query = "
    SELECT
        id,
        item_title,
        category,
        date_found,
        description,
        location_found,
        contact,
        finder_name,
        status
    FROM found_items
    WHERE id = ?
";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $itemId);
$stmt->execute();

$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "Item not found."
    ]);

    $stmt->close();
    $connection->close();
    exit;
}

echo json_encode([
    "success" => true,
    "item" => $item
]);

$stmt->close();
$connection->close();