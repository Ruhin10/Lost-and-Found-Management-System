<?php

/*
 * File location: Controller/AdminDashboardController.php
 *
 * This controller prepares the dashboard data. It does not load the View,
 * because View/AdminDashboard.php includes this controller directly.
 */

require_once __DIR__ . '/../Model/DatabaseConnection.php';

$database = new DatabaseConnection();
$connection = $database->openConnection();

/* Dashboard statistics */
$statisticsQuery = "
    SELECT
        COUNT(*) AS total_posted,
        COALESCE(SUM(status = 'Resolved'), 0) AS solved,
        COALESCE(SUM(status = 'Pending'), 0) AS unclaimed
    FROM found_items
";

$statisticsResult = $connection->query($statisticsQuery);

if (!$statisticsResult) {
    die('Unable to load dashboard statistics: ' . $connection->error);
}

$statistics = $statisticsResult->fetch_assoc();

$totalPosted = (int) $statistics['total_posted'];
$solved = (int) $statistics['solved'];
$unclaimed = (int) $statistics['unclaimed'];

/* Pending claim count */
$pendingClaimsQuery = "
    SELECT COUNT(*) AS total
    FROM claims
    WHERE status = 'Pending'
";

$pendingClaimsResult = $connection->query($pendingClaimsQuery);

if (!$pendingClaimsResult) {
    die('Unable to load pending claims: ' . $connection->error);
}

$pendingClaims = (int) $pendingClaimsResult->fetch_assoc()['total'];

/* Found items and their claim counts */
$itemsQuery = "
    SELECT
        f.id,
        f.item_title,
        f.location_found,
        f.date_found,
        f.status,
        COUNT(c.id) AS claim_count
    FROM found_items AS f
    LEFT JOIN claims AS c
        ON f.id = c.item_id
    GROUP BY
        f.id,
        f.item_title,
        f.location_found,
        f.date_found,
        f.status,
        f.created_at
    ORDER BY f.created_at DESC
";

$itemsResult = $connection->query($itemsQuery);

if (!$itemsResult) {
    die('Unable to load found items: ' . $connection->error);
}

$items = $itemsResult->fetch_all(MYSQLI_ASSOC);

$statisticsResult->free();
$pendingClaimsResult->free();
$itemsResult->free();
$connection->close();

