<?php

require_once __DIR__ . '/../Model/DatabaseConnection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/AdminDashboard.php");
    exit;
}

$itemId = filter_input(
    INPUT_POST,
    'item_id',
    FILTER_VALIDATE_INT
);

$itemTitle = trim($_POST['item_title'] ?? '');
$category = trim($_POST['category'] ?? '');
$dateFound = trim($_POST['date_found'] ?? '');
$description = trim($_POST['description'] ?? '');
$locationFound = trim($_POST['location_found'] ?? '');
$finderName = trim($_POST['finder_name'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$status = trim($_POST['status'] ?? '');

$allowedStatuses = [
    'Pending',
    'Claimed',
    'Resolved'
];

if (
    !$itemId ||
    $itemTitle === '' ||
    $category === '' ||
    $dateFound === '' ||
    $description === '' ||
    $locationFound === '' ||
    $finderName === '' ||
    $contact === '' ||
    !in_array($status, $allowedStatuses, true)
) {
    header(
        "Location: ../View/AdminDashboard.php?error=invalid_data"
    );

    exit;
}

$database = new DatabaseConnection();
$connection = $database->openConnection();

$query = "
    UPDATE found_items
    SET
        item_title = ?,
        category = ?,
        date_found = ?,
        description = ?,
        location_found = ?,
        finder_name = ?,
        contact = ?,
        status = ?
    WHERE id = ?
";

$stmt = $connection->prepare($query);

$stmt->bind_param(
    "ssssssssi",
    $itemTitle,
    $category,
    $dateFound,
    $description,
    $locationFound,
    $finderName,
    $contact,
    $status,
    $itemId
);

$stmt->execute();

$stmt->close();
$connection->close();

header(
    "Location: ../View/AdminDashboard.php?message=updated"
);

exit;