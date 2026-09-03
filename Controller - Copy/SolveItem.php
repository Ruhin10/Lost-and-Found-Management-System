<?php

require_once __DIR__ . '/../Model/DatabaseConnection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: AdminDashboardController.php');
    exit;
}

$itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);

if (!$itemId) {
    header('Location: AdminDashboardController.php?error=invalid_id');
    exit;
}

$db = new DatabaseConnection();
$connection = $db->openConnection();

$stmt = $connection->prepare("
    UPDATE found_items
    SET status = 'Resolved'
    WHERE id = ?
");

$stmt->bind_param("i", $itemId);
$stmt->execute();

$stmt->close();
$connection->close();

header('Location: ../View/AdminDashboard.php');
exit;