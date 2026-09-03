<?php

require_once __DIR__ . '/../Model/DatabaseConnection.php';

$database = new DatabaseConnection();
$connection = $database->openConnection();

$sql = "
    SELECT
        f.id,
        f.item_title,
        f.category,
        f.date_found,
        f.description,
        f.location_found,
        f.photo,
        f.status,
        COUNT(c.id) AS claim_count
    FROM found_items AS f
    LEFT JOIN claims AS c
        ON f.id = c.item_id
    WHERE f.status != 'Resolved'
    GROUP BY
        f.id,
        f.item_title,
        f.category,
        f.date_found,
        f.description,
        f.location_found,
        f.photo,
        f.status,
        f.created_at
    ORDER BY f.date_found DESC
";

$result = $connection->query($sql);

if (!$result) {
    die("Unable to load found items: " . $connection->error);
}

$items = $result->fetch_all(MYSQLI_ASSOC);

$result->free();
$connection->close();