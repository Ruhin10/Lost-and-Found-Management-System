<?php

require_once __DIR__ . '/../Model/DatabaseConnection.php';

$solvedItems = [];

$database = new DatabaseConnection();
$connection = $database->openConnection();

/*
|--------------------------------------------------------------------------
| Get resolved items and approved claimant
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        f.id,
        f.item_title,
        f.category,
        f.location_found,
        f.status,

        c.claimant_name,

        COALESCE(
            c.reviewed_at,
            c.created_at,
            f.created_at
        ) AS resolved_date

    FROM found_items AS f

    LEFT JOIN claims AS c
        ON c.id = (
            SELECT c2.id
            FROM claims AS c2

            WHERE c2.item_id = f.id
              AND c2.status = 'Approved'

            ORDER BY
                COALESCE(
                    c2.reviewed_at,
                    c2.created_at
                ) DESC,
                c2.id DESC

            LIMIT 1
        )

    WHERE f.status = 'Resolved'

    ORDER BY resolved_date DESC
";

$result = $connection->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $solvedItems[] = $row;
    }
} else {
    error_log(
        "Solved cases query failed: " .
        $connection->error
    );
}

$connection->close();