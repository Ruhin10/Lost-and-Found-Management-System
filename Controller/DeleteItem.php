<?php

require_once __DIR__ . '/../Model/DatabaseConnection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: AdminDashboardController.php');
    exit;
}

$itemId = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);

if (!$itemId) {
    header('Location: AdminDashboard.php?error=invalid_id');
    exit;
}

$db = new DatabaseConnection();
$connection = $db->openConnection();

$stmt = $connection->prepare("
    DELETE FROM found_items
    WHERE id = ?
");

$stmt->bind_param("i", $itemId);
$stmt->execute();

$stmt->close();
$connection->close();

header('Location: ../View/AdminDashboard.php');
exit;