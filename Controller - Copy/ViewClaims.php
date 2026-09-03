<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    exit('<div class="popup-error">Please log in as admin.</div>');
}

require_once __DIR__ . '/../Model/DatabaseConnection.php';

$itemId = filter_input(
    INPUT_GET,
    'item_id',
    FILTER_VALIDATE_INT
);

if (!$itemId) {
    exit('<div class="popup-error">Invalid item selected.</div>');
}

$database = new DatabaseConnection();
$connection = $database->openConnection();

$sql = "
    SELECT
        id,
        item_id,
        claimant_name,
        claimant_contact,
        claim_description,
        status,
        admin_note,
        created_at,
        reviewed_at
    FROM claims
    WHERE item_id = ?
    ORDER BY created_at DESC
";

$stmt = $connection->prepare($sql);

if (!$stmt) {
    $connection->close();
    exit('<div class="popup-error">Unable to load claims.</div>');
}

$stmt->bind_param("i", $itemId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="no-claims">No claims were submitted for this item.</div>';

    $stmt->close();
    $connection->close();
    exit;
}

while ($claim = $result->fetch_assoc()) {
    $claimId = (int) $claim['id'];

    $claimantName = htmlspecialchars(
        (string) $claim['claimant_name'],
        ENT_QUOTES,
        'UTF-8'
    );

    $claimantContact = htmlspecialchars(
        (string) $claim['claimant_contact'],
        ENT_QUOTES,
        'UTF-8'
    );

    $claimDescription = htmlspecialchars(
        (string) $claim['claim_description'],
        ENT_QUOTES,
        'UTF-8'
    );

    $claimStatus = htmlspecialchars(
        (string) $claim['status'],
        ENT_QUOTES,
        'UTF-8'
    );

    $submittedDate = date(
        "M d, Y",
        strtotime((string) $claim['created_at'])
    );

    $statusClass = strtolower(
        (string) $claim['status']
    );

    ?>

    <div class="claim-card">

        <div class="claim-card-header">

            <div>
                <h3><?= $claimantName ?></h3>

                <span class="claim-date">
                    Submitted <?= $submittedDate ?>
                </span>
            </div>

            <span class="claim-status <?= $statusClass ?>">
                <?= $claimStatus ?>
            </span>

        </div>

        <div class="claim-information">

            <div class="claim-field">

                <span class="claim-label">
                    CONTACT
                </span>

                <span class="claim-value">
                    <?= $claimantContact ?>
                </span>

            </div>

            <div class="claim-field">

                <span class="claim-label">
                    OWNERSHIP PROOF
                </span>

                <p class="claim-proof">
                    <?= nl2br($claimDescription) ?>
                </p>

            </div>

        </div>

        <?php if ($claim['status'] === 'Pending'): ?>

            <form
                action="../Controller/ApproveClaim.php"
                method="POST"
                class="approve-claim-form"
                onsubmit="return confirm('Approve this claimant and mark the item as solved?');">

                <input
                    type="hidden"
                    name="claim_id"
                    value="<?= $claimId ?>">

                <button
                    type="submit"
                    class="approve-claim-btn">
                    Approve &amp; Solve
                </button>

            </form>

        <?php elseif ($claim['status'] === 'Approved'): ?>

            <div class="approved-message">
                This person received the item.
            </div>

        <?php else: ?>

            <div class="rejected-message">
                This claim was rejected.
            </div>

        <?php endif; ?>

    </div>

    <?php
}

$stmt->close();
$connection->close();