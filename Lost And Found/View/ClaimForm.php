<?php

include_once __DIR__ . '/../Controller/ClaimItem.php';


$itemId = filter_input(
    INPUT_GET,
    'item_id',
    FILTER_VALIDATE_INT
);

if (!$itemId) {
    header("Location: ShowList.php?error=invalid_item");
    exit;
}

include_once __DIR__ . '/../Controller/ClaimItem.php';

if (!isset($item) || !is_array($item)) {
    header("Location: found-items.php?error=item_not_found");
    exit;
}

$errorMessage = '';

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid_data') {
        $errorMessage = 'Please complete all fields correctly.';
    } elseif ($_GET['error'] === 'item_not_available') {
        $errorMessage = 'This item is no longer available.';
    } else {
        $errorMessage = 'Unable to submit your claim.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Claim <?= htmlspecialchars(
            $item['item_title'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </title>

    <link rel="stylesheet" href="Style/claimStyle.css">
</head>

<body>

    <div class="claim-box">

        <div class="claim-header">

            <h1>
                Claim:
                <?= htmlspecialchars(
                    $item['item_title'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h1>

           <a href="ShowList.php" class="close">
    &times;
</a>

        </div>

        <?php if ($errorMessage !== ''): ?>

            <div class="error-message">

                <?= htmlspecialchars(
                    $errorMessage,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </div>

        <?php endif; ?>

        <form
            id="claimForm"
            action="../Controller/SubmitClaim.php"
            method="POST">

            <input
                type="hidden"
                name="item_id"
                value="<?= (int) $item['id'] ?>">

            <div class="form-group">

                <label for="claimantName">
                    YOUR NAME
                </label>

                <input
                    type="text"
                    id="claimantName"
                    name="claimant_name"
                    placeholder="Enter your full name"
                    maxlength="100"
                    required>

            </div>

            <div class="form-group">

                <label for="claimantContact">
                    CONTACT INFO
                </label>

                <input
                    type="text"
                    id="claimantContact"
                    name="claimant_contact"
                    placeholder="Enter your phone number or email"
                    maxlength="150"
                    required>

            </div>

            <div class="form-group">

                <label for="claimDescription">
                    PROVE IT'S YOURS
                </label>

                <textarea
                    id="claimDescription"
                    name="claim_description"
                    placeholder="Describe a scratch, sticker, item inside or another detail only the owner would know."
                    maxlength="1000"
                    rows="6"
                    required></textarea>

            </div>

            <div
                id="clientError"
                class="error-message"
                style="display: none;">
            </div>

            <button type="submit">
                Submit Claim
            </button>

        </form>

    </div>

    <script src="../Script/claimValidation.js"></script>

</body>

</html>