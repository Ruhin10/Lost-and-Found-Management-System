<?php

require_once __DIR__ . '/DatabaseConnection.php';

class FoundItemModel
{
    private mysqli $connection;

    public function __construct()
    {
        $database = new DatabaseConnection();

        $this->connection =
            $database->openConnection();
    }

    public function searchItems(
        string $keyword = '',
        string $category = 'all'
    ): array {
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

              AND (
                    ? = ''
                    OR f.item_title LIKE ?
                    OR f.description LIKE ?
                    OR f.location_found LIKE ?
              )

              AND (
                    ? = 'all'
                    OR LOWER(f.category) = LOWER(?)
              )

            GROUP BY
                f.id,
                f.item_title,
                f.category,
                f.date_found,
                f.description,
                f.location_found,
                f.photo,
                f.status

            ORDER BY
                f.date_found DESC,
                f.id DESC
        ";

        $stmt = $this->connection->prepare($sql);

        if (!$stmt) {
            throw new Exception(
                'Unable to prepare search query.'
            );
        }

        $searchValue = '%' . $keyword . '%';

        $stmt->bind_param(
            "ssssss",
            $keyword,
            $searchValue,
            $searchValue,
            $searchValue,
            $category,
            $category
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $items = [];

        while ($row = $result->fetch_assoc()) {
            $row['id'] = (int) $row['id'];
            $row['claim_count'] =
                (int) $row['claim_count'];

            $items[] = $row;
        }

        $stmt->close();

        return $items;
    }

    public function closeConnection(): void
    {
        $this->connection->close();
    }
}