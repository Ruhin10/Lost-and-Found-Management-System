<?php

require_once __DIR__ . '/../Model/DatabaseConnection.php';

/*
|--------------------------------------------------------------------------
| Allow only POST requests
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/ShowList.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Receive form values
|--------------------------------------------------------------------------
*/

$itemId = filter_input(
    INPUT_POST,
    'item_id',
    FILTER_VALIDATE_INT
);

$claimantName = trim(
    $_POST['claimant_name'] ?? ''
);

$claimantContact = trim(
    $_POST['claimant_contact'] ?? ''
);

$claimDescription = trim(
    $_POST['claim_description'] ?? ''
);

/*
|--------------------------------------------------------------------------
| Validate form values
|--------------------------------------------------------------------------
*/

if (
    !$itemId ||
    strlen($claimantName) < 3 ||
    strlen($claimantContact) < 5 ||
    strlen($claimDescription) < 10
) {
    header(
        "Location: ../View/ClaimForm.php?" .
        "item_id=" . (int) $itemId .
        "&error=invalid_data"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| Open database connection
|--------------------------------------------------------------------------
*/

$database = new DatabaseConnection();
$connection = $database->openConnection();

/*
|--------------------------------------------------------------------------
| Check whether the item exists and is available
|--------------------------------------------------------------------------
*/

$checkSql = "
    SELECT id
    FROM found_items
    WHERE id = ?
      AND status != 'Resolved'
";

$checkStmt = $connection->prepare($checkSql);

if (!$checkStmt) {
    error_log($connection->error);
    $connection->close();

    header(
        "Location: ../View/ShowList.php?" .
        "error=database_error"
    );

    exit;
}

$checkStmt->bind_param("i", $itemId);

if (!$checkStmt->execute()) {
    error_log($checkStmt->error);

    $checkStmt->close();
    $connection->close();

    header(
        "Location: ../View/ShowList.php?" .
        "error=database_error"
    );

    exit;
}

$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $checkResult->free();
    $checkStmt->close();
    $connection->close();

    header(
        "Location: ../View/ShowList.php?" .
        "error=item_not_available"
    );

    exit;
}

$checkResult->free();
$checkStmt->close();

/*
|--------------------------------------------------------------------------
| Insert claim into the database
|--------------------------------------------------------------------------
*/

$insertSql = "
    INSERT INTO claims (
        item_id,
        claimant_name,
        claimant_contact,
        claim_description,
        status
    )
    VALUES (?, ?, ?, ?, 'Pending')
";

$stmt = $connection->prepare($insertSql);

if (!$stmt) {
    error_log($connection->error);
    $connection->close();

    header(
        "Location: ../View/ClaimForm.php?" .
        "item_id=" . $itemId .
        "&error=database_error"
    );

    exit;
}

$stmt->bind_param(
    "isss",
    $itemId,
    $claimantName,
    $claimantContact,
    $claimDescription
);

/*
|--------------------------------------------------------------------------
| Execute and redirect
|--------------------------------------------------------------------------
*/

if ($stmt->execute()) {
    $stmt->close();
    $connection->close();

    header(
        "Location: ../View/ShowList.php?" .
        "message=claim_submitted"
    );

    exit;
}

error_log($stmt->error);

$stmt->close();
$connection->close();

header(
    "Location: ../View/ClaimForm.php?" .
    "item_id=" . $itemId .
    "&error=save_failed"
);

exit;