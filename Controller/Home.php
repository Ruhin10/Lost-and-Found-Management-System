<?php

require_once __DIR__ . '/../Model/DatabaseConnection.php';

$database = new DatabaseConnection();
$connection = $database->openConnection();

/*
|--------------------------------------------------------------------------
| Landing-page statistics
|--------------------------------------------------------------------------
|
| 1. Total posted items
| 2. Successfully returned items
| 3. Pending claims awaiting admin review
|
*/

$sql = "
    SELECT
        (
            SELECT COUNT(*)
            FROM found_items
        ) AS items_posted,

        (
            SELECT COUNT(*)
            FROM found_items
            WHERE status = 'Resolved'
        ) AS successfully_returned,

        (
            SELECT COUNT(*)
            FROM claims
            WHERE status = 'Pending'
        ) AS awaiting_claim_review
";

$result = $connection->query($sql);

if (!$result) {
    error_log(
        "Home statistics query failed: " .
        $connection->error
    );

    $itemsPosted = 0;
    $successfullyReturned = 0;
    $awaitingClaimReview = 0;
} else {
    $statistics = $result->fetch_assoc();

    $itemsPosted =
        (int) $statistics['items_posted'];

    $successfullyReturned =
        (int) $statistics['successfully_returned'];

    $awaitingClaimReview =
        (int) $statistics['awaiting_claim_review'];

    $result->free();
}

$connection->close();