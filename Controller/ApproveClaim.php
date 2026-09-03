<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logged_in'])) {
    header(
        "Location: ../View/AdminLogin.php?" .
        "error=login_required"
    );

    exit;
}

require_once __DIR__ . '/../Model/DatabaseConnection.php';

function returnToDashboard(
    string $parameter,
    string $value
): void {
    header(
        "Location: ../View/AdminDashboard.php?" .
        $parameter . '=' .
        urlencode($value)
    );

    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    returnToDashboard('error', 'invalid_request');
}

$claimId = filter_input(
    INPUT_POST,
    'claim_id',
    FILTER_VALIDATE_INT
);

if (!$claimId) {
    returnToDashboard('error', 'invalid_claim');
}

$database = new DatabaseConnection();
$connection = $database->openConnection();

try {
    $connection->begin_transaction();

    /*
    |--------------------------------------------------------------------------
    | Find the selected claim
    |--------------------------------------------------------------------------
    */

    $findSql = "
        SELECT
            item_id,
            status
        FROM claims
        WHERE id = ?
        LIMIT 1
        FOR UPDATE
    ";

    $findStmt = $connection->prepare($findSql);

    if (!$findStmt) {
        throw new Exception(
            'Unable to prepare claim query.'
        );
    }

    $findStmt->bind_param("i", $claimId);
    $findStmt->execute();

    $itemId = 0;
    $currentStatus = '';

    $findStmt->bind_result(
        $itemId,
        $currentStatus
    );

    $claimFound = $findStmt->fetch();

    $findStmt->close();

    if ($claimFound !== true) {
        throw new Exception('Claim not found.');
    }

    if ($currentStatus !== 'Pending') {
        throw new Exception(
            'This claim has already been reviewed.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approve selected claim
    |--------------------------------------------------------------------------
    */

    $approveSql = "
        UPDATE claims
        SET
            status = 'Approved',
            admin_note = 'Ownership verified and item returned.',
            reviewed_at = CURRENT_TIMESTAMP
        WHERE id = ?
          AND status = 'Pending'
    ";

    $approveStmt = $connection->prepare(
        $approveSql
    );

    if (!$approveStmt) {
        throw new Exception(
            'Unable to prepare approval query.'
        );
    }

    $approveStmt->bind_param("i", $claimId);
    $approveStmt->execute();

    if ($approveStmt->affected_rows !== 1) {
        $approveStmt->close();

        throw new Exception(
            'Claim could not be approved.'
        );
    }

    $approveStmt->close();

    /*
    |--------------------------------------------------------------------------
    | Reject other pending claims for the same item
    |--------------------------------------------------------------------------
    */

    $rejectSql = "
        UPDATE claims
        SET
            status = 'Rejected',
            admin_note = 'Another claimant was approved.',
            reviewed_at = CURRENT_TIMESTAMP
        WHERE item_id = ?
          AND id != ?
          AND status = 'Pending'
    ";

    $rejectStmt = $connection->prepare(
        $rejectSql
    );

    if (!$rejectStmt) {
        throw new Exception(
            'Unable to prepare rejection query.'
        );
    }

    $rejectStmt->bind_param(
        "ii",
        $itemId,
        $claimId
    );

    $rejectStmt->execute();
    $rejectStmt->close();

    /*
    |--------------------------------------------------------------------------
    | Mark the item as resolved
    |--------------------------------------------------------------------------
    */

    $itemSql = "
        UPDATE found_items
        SET status = 'Resolved'
        WHERE id = ?
    ";

    $itemStmt = $connection->prepare(
        $itemSql
    );

    if (!$itemStmt) {
        throw new Exception(
            'Unable to prepare item update.'
        );
    }

    $itemStmt->bind_param("i", $itemId);
    $itemStmt->execute();

    if ($itemStmt->affected_rows < 1) {
        $itemStmt->close();

        throw new Exception(
            'Item could not be resolved.'
        );
    }

    $itemStmt->close();

    $connection->commit();
    $connection->close();

    returnToDashboard(
        'message',
        'claim_approved'
    );
} catch (Throwable $exception) {
    $connection->rollback();

    error_log(
        'Approve claim failed: ' .
        $exception->getMessage()
    );

    $connection->close();

    returnToDashboard(
        'error',
        'claim_approval_failed'
    );
}