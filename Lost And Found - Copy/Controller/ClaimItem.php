<?php

require_once __DIR__ . '/../Model/DatabaseConnection.php';

$itemId = filter_input(
    INPUT_GET,
    'item_id',
    FILTER_VALIDATE_INT
);

if (!$itemId) {
    header("Location: ../View/ShowList.php?error=invalid_item");
    exit;
}

$database = new DatabaseConnection();
$connection = $database->openConnection();

$sql = "
    SELECT
        id,
        item_title,
        category,
        description,
        location_found,
        date_found,
        photo,
        status
    FROM found_items
    WHERE id = ?
      AND status != 'Resolved'
";

$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $itemId);
$stmt->execute();

$result = $stmt->get_result();
$item = $result->fetch_assoc();

$stmt->close();
$connection->close();

if (!$item) {
    header("Location: ../View/ShowList.php?error=item_not_found");
    exit;
}