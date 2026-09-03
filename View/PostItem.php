<?php

$successMessage = '';
$errorMessage = '';

if (
    isset($_GET['message']) &&
    $_GET['message'] === 'item_posted'
) {
    $successMessage = 'Found item posted successfully.';
}

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'invalid_data') {
        $errorMessage = 'Please complete all fields correctly.';
    } elseif ($_GET['error'] === 'invalid_photo') {
        $errorMessage = 'Please select a valid JPG, PNG or WEBP image.';
    } elseif ($_GET['error'] === 'upload_failed') {
        $errorMessage = 'The image could not be uploaded.';
    } else {
        $errorMessage = 'Unable to post the found item.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Post Found Item</title>

    <link rel="stylesheet" href="Style/postItem.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Special+Elite&amp;family=IBM+Plex+Mono:wght@400;500;600&amp;family=Public+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar">

        <a href="landing.html" class="navbar-logo">

            <span class="logo-icon">
                LF
            </span>

            <span class="logo-text">

                <span class="logo-title">
                    Lost &amp; Found
                </span>

                <span class="logo-subtitle">
                    MANAGEMENT SYSTEM
                </span>

            </span>

        </a>

        <div class="nav-links">

            <a href="landing.html" class="nav-link">
                Home
            </a>

            <a href="ShowList.php" class="nav-link">
                Found Items
            </a>

            <a href="PostItem.php" class="nav-link active">
                Post Found Item
            </a>

            <a href="SolvedCase.php" class="nav-link">
                Solved Cases
            </a>

        </div>

        <a href="AdminLogin.php" class="admin-login-btn">
            Admin Login
        </a>

    </nav>

    <main class="post-container">

        <div class="post-header">

            <h1>Post a Found Item</h1>

            <p>
                Enter information about the item you found.
            </p>

        </div>

        <?php if ($successMessage !== ''): ?>

            <div class="message success-message">
                <?= htmlspecialchars(
                    $successMessage,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </div>

        <?php endif; ?>

        <?php if ($errorMessage !== ''): ?>

            <div class="message error-message">
                <?= htmlspecialchars(
                    $errorMessage,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </div>

        <?php endif; ?>

        <form
            id="postItemForm"
            action="../Controller/AddFoundItem.php"
            method="POST"
            enctype="multipart/form-data">

            <div class="form-row">

                <div class="form-group">

                    <label for="itemTitle">
                        ITEM TITLE
                    </label>

                    <input
                        type="text"
                        id="itemTitle"
                        name="item_title"
                        placeholder="Example: Black Samsung phone"
                        maxlength="150"
                        required>

                </div>

                <div class="form-group">

                    <label for="category">
                        CATEGORY
                    </label>

                    <select
                        id="category"
                        name="category"
                        required>

                        <option value="">
                            Select category
                        </option>

                        <option value="Electronics">
                            Electronics
                        </option>

                        <option value="Bags">
                            Bags
                        </option>

                        <option value="Documents">
                            Documents
                        </option>

                        <option value="Accessories">
                            Accessories
                        </option>

                        <option value="Clothing">
                            Clothing
                        </option>

                        <option value="Personal Items">
                            Personal Items
                        </option>

                        <option value="Stationery">
                            Stationery
                        </option>

                    </select>

                </div>

            </div>

            <div class="form-row">

                <div class="form-group">

                    <label for="dateFound">
                        DATE FOUND
                    </label>

                    <input
                        type="date"
                        id="dateFound"
                        name="date_found"
                        required>

                </div>

                <div class="form-group">

                    <label for="locationFound">
                        LOCATION FOUND
                    </label>

                    <input
                        type="text"
                        id="locationFound"
                        name="location_found"
                        placeholder="Example: DS011"
                        maxlength="255"
                        required>

                </div>

            </div>

            <div class="form-row">

                <div class="form-group">

                    <label for="finderName">
                        FINDER NAME
                    </label>

                    <input
                        type="text"
                        id="finderName"
                        name="finder_name"
                        placeholder="Your full name"
                        maxlength="100"
                        required>

                </div>

                <div class="form-group">

                    <label for="contact">
                        CONTACT INFORMATION
                    </label>

                    <input
                        type="text"
                        id="contact"
                        name="contact"
                        placeholder="Phone number or email"
                        maxlength="150"
                        required>

                </div>

            </div>

            <div class="form-group">

                <label for="description">
                    ITEM DESCRIPTION
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    maxlength="2000"
                    placeholder="Describe the item, including its color, condition and visible details."
                    required></textarea>

            </div>

            <div class="form-group">

                <label for="photo">
                    ITEM PHOTO
                </label>

                <input
                    type="file"
                    id="photo"
                    name="photo"
                    accept=".jpg,.jpeg,.png,.webp">

                <p class="photo-information">
                    JPG, PNG or WEBP. Maximum file size: 5 MB.
                </p>

                <div
                    id="imagePreviewContainer"
                    class="image-preview-container">

                    <img
                        id="imagePreview"
                        src=""
                        alt="Selected item preview">

                </div>

            </div>

            <div
                id="formError"
                class="message error-message"
                style="display: none;">
            </div>

            <div class="form-actions">

                <a href="ShowList.php" class="cancel-button">
                    Cancel
                </a>

                <button type="submit" class="submit-button">
                    Post Found Item
                </button>

            </div>

        </form>

    </main>

    <script src="../Script/postItem.js"></script>

</body>

</html>