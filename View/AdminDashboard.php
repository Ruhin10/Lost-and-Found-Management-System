<?php

// Load dashboard data from the controller.
include_once __DIR__ . '/../Controller/AdminDashboard.php';

?>

<?php

session_start();

if (empty($_SESSION['admin_logged_in'])) {
    header("Location: AdminLogin.php?error=login_required");
    exit;
}

?>



<!DOCTYPE html>
<html lang="en">

<head>


    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="Style/adminStyle.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Special+Elite&family=IBM+Plex+Mono:wght@400;500;600&family=Public+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
<script src="Script/claimPopup.js" defer></script>
</head>

<body class="body">

    <!-- Navigation -->
    <nav class="navbar">

        <div class="logo"></div>

        <div class="nav-links">

            <a href="landing.php" class="nav-link">
                Home
            </a>

            <a href="ShowList.php" class="nav-link">
                Found Items
            </a>

            <a href="PostItem.php" class="nav-link">
                Post Found Item
            </a>

            <a href="SolvedCase.php" class="nav-link">
                Solved Cases
            </a>

        </div>

        

    </nav>

    <!-- Dashboard header -->
    <div class="head">

        <h2 class="title">
            Admin Dashboard
        </h2>

        <form
    action="../Controller/Logout.php"
    method="POST"
    class="logout-form">

    <button type="submit" class="logout-btn">
        Logout
    </button>

</form>

    </div>

    <!-- Success message -->
    <?php if (isset($_GET['message'])): ?>

        <div class="dashboard-message success">

            <?php

            if ($_GET['message'] === 'solved') {
                echo "The item was marked as solved.";
            } elseif ($_GET['message'] === 'deleted') {
                echo "The item was deleted successfully.";
            } elseif ($_GET['message'] === 'updated') {
                echo "The item was updated successfully.";
            }

            ?>

        </div>

    <?php endif; ?>

    <!-- Error message -->
    <?php if (isset($_GET['error'])): ?>

        <div class="dashboard-message error">
            The requested action could not be completed.
        </div>

    <?php endif; ?>

    <!-- Statistics -->
    <div class="stats-container">

        <div class="stat-card">

            <div class="stat-number">
                <?= (int) $totalPosted ?>
            </div>

            <div class="stat-label">
                TOTAL POSTED
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-number">
                <?= (int) $pendingClaims ?>
            </div>

            <div class="stat-label">
                PENDING CLAIMS
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-number">
                <?= (int) $solved ?>
            </div>

            <div class="stat-label">
                SOLVED
            </div>

        </div>

        <div class="stat-card">

            <div class="stat-number">
                <?= (int) $unclaimed ?>
            </div>

            <div class="stat-label">
                STILL UNCLAIMED
            </div>

        </div>

    </div>

    <!-- Found-items table -->
    <div class="table-container">

        <table>

            <thead>

                <tr>
                    <th>ITEM</th>
                    <th>LOCATION</th>
                    <th>POSTED</th>
                    <th>CLAIMS</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>

            </thead>

            <tbody>

                <?php if (!empty($items)): ?>

                    <?php foreach ($items as $item): ?>

                        <?php

                        $itemId = (int) $item['id'];

                        $formattedDate = date(
                            "M d, Y",
                            strtotime($item['date_found'])
                        );

                        if ($item['status'] === 'Pending') {

                            $statusClass = "available";
                            $statusText = "Available";
                        } elseif ($item['status'] === 'Claimed') {

                            $statusClass = "pending";
                            $statusText = "Claimed";
                        } elseif ($item['status'] === 'Resolved') {

                            $statusClass = "resolved";
                            $statusText = "Resolved";
                        } else {

                            $statusClass = "available";
                            $statusText = $item['status'];
                        }

                        ?>

                        <tr>

                            <!-- Item name -->
                            <td>
                                <?= htmlspecialchars(
                                    $item['item_title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <!-- Location -->
                            <td>
                                <?= htmlspecialchars(
                                    $item['location_found'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <!-- Date -->
                            <td>
                                <?= htmlspecialchars(
                                    $formattedDate,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <!-- Claim count -->
                            <td>
                                <?= (int) $item['claim_count'] ?>
                            </td>

                            <!-- Status -->
                            <td>

                                <span class="status <?= $statusClass ?>">

                                    <?= htmlspecialchars(
                                        $statusText,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </td>

                            <!-- Actions -->
                            <td>

                                <div class="actions">

                                    <button
                                        type="button"
                                        onclick="viewClaims(<?= $itemId ?>)">
                                        View Claims
                                    </button>

                                    <button
                                        type="button"
                                        onclick="openEditModal(<?= $itemId ?>)">
                                        Edit
                                    </button>

                            

                                    <!-- Delete -->
                                    <form
                                        action="../Controller/DeleteItem.php"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this item?');">

                                        <input
                                            type="hidden"
                                            name="item_id"
                                            value="<?= $itemId ?>">

                                        <button type="submit">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="6" style="text-align: center;">
                            No found items available.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- Claims modal -->
    <!-- View Claims Popup -->
<div id="claimsModal" class="popup-overlay">

    <div class="popup-box">

        <button
            type="button"
            class="popup-close"
            onclick="closeClaimsModal()">
            &times;
        </button>

        <h2>Item Claims</h2>

        <div id="claimsContent">
            Loading claims...
        </div>

    </div>

</div>


<!-- Edit Item Popup -->
<div id="editModal" class="popup-overlay">

    <div class="popup-box edit-popup">

        <button
            type="button"
            class="popup-close"
            onclick="closeEditModal()">
            &times;
        </button>

        <h2>Edit Found Item</h2>

        <form
            id="editItemForm"
            action="../Controller/UpdateItem.php"
            method="POST">

            <input
                type="hidden"
                id="editItemId"
                name="item_id">

            <div class="edit-form-grid">

                <div class="form-group">
                    <label for="editItemTitle">Item Title</label>

                    <input
                        type="text"
                        id="editItemTitle"
                        name="item_title"
                        required>
                </div>

                <div class="form-group">
                    <label for="editCategory">Category</label>

                    <input
                        type="text"
                        id="editCategory"
                        name="category"
                        required>
                </div>

                <div class="form-group">
                    <label for="editDateFound">Date Found</label>

                    <input
                        type="date"
                        id="editDateFound"
                        name="date_found"
                        required>
                </div>

                <div class="form-group">
                    <label for="editLocation">Location Found</label>

                    <input
                        type="text"
                        id="editLocation"
                        name="location_found"
                        required>
                </div>

                <div class="form-group">
                    <label for="editFinderName">Finder Name</label>

                    <input
                        type="text"
                        id="editFinderName"
                        name="finder_name"
                        required>
                </div>

                <div class="form-group">
                    <label for="editContact">Contact</label>

                    <input
                        type="text"
                        id="editContact"
                        name="contact"
                        required>
                </div>

                <div class="form-group">
                    <label for="editStatus">Status</label>

                    <select
                        id="editStatus"
                        name="status"
                        required>

                        <option value="Pending">Pending</option>
                        <option value="Claimed">Claimed</option>
                        <option value="Resolved">Resolved</option>

                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="editDescription">Description</label>

                    <textarea
                        id="editDescription"
                        name="description"
                        rows="5"
                        required></textarea>
                </div>

            </div>

            <div class="edit-buttons">

                <button
                    type="button"
                    class="cancel-button"
                    onclick="closeEditModal()">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="save-button">
                    Update Item
                </button>

            </div>

        </form>

    </div>

</div>



</body>

</html>