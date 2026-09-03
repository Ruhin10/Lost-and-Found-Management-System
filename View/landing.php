<?php

include_once __DIR__ . '/../Controller/Home.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>


    <title>Lost &amp; Found</title>

    <link rel="stylesheet" href="Style/hstyle.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Special+Elite&amp;family=IBM+Plex+Mono:wght@400;500;600&amp;family=Public+Sans:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet">
</head>

<body>

<nav class="navbar">

    <a href="landing.php" class="navbar-logo">

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

        <a href="landing.php" class="nav-link active">
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

    <a href="AdminLogin.php" class="admin-login-btn">
        Admin Login
    </a>

</nav>

    <main class="container">

        <!-- Hero section -->
        <section class="hero">

            <div class="hero-content">

                <div class="small-title">
                    NO SIGN-UP NEEDED
                </div>

                <h1>
                    Lost something?<br>
                    Someone may have<br>
                    already turned it in.
                </h1>

                <p>
                    Post what you found in under a minute, or search what
                    others have already handed in. No account, no password—
                    just a quick, honest report.
                </p>

                <!-- Navigation buttons -->
                <div class="buttons">

                    <button
                        type="button"
                        id="postFoundBtn"
                        class="primary-btn">

                        Post a Found Item

                    </button>

                    <button
                        type="button"
                        id="browseFoundBtn"
                        class="secondary-btn">

                        Browse Found Items

                    </button>

                </div>

                <p
                    id="pageMessage"
                    class="page-message"
                    aria-live="polite">
                </p>

            </div>

        </section>

        <!-- Database statistics -->
        <section class="stats">

            <table>

                <tr>

                    <td class="stat-card">

                        <p class="stat-number">

                            <?= number_format(
                                $itemsPosted
                            ) ?>

                        </p>

                        <p class="stat-label">
                            ITEMS POSTED
                        </p>

                    </td>

                    <td class="stat-card">

                        <p class="stat-number">

                            <?= number_format(
                                $successfullyReturned
                            ) ?>

                        </p>

                        <p class="stat-label">
                            SUCCESSFULLY RETURNED
                        </p>

                    </td>

                    <td class="stat-card">

                        <p class="stat-number">

                            <?= number_format(
                                $awaitingClaimReview
                            ) ?>

                        </p>

                        <p class="stat-label">
                            AWAITING CLAIM REVIEW
                        </p>

                    </td>

                </tr>

            </table>

        </section>

        <!-- How it works -->
        <section class="howitworks">

            <h2>How it works</h2>

            <div class="line"></div>

            <table class="steps">

                <tr>

                    <td class="step">

                        <p class="step-number">
                            01
                        </p>

                        <h3>
                            Someone posts a find
                        </h3>

                        <p>
                            No login is required. Enter the item details,
                            where and when it was found, and an optional
                            photo.
                        </p>

                    </td>

                    <td class="step">

                        <p class="step-number">
                            02
                        </p>

                        <h3>
                            Owner spots it and claims
                        </h3>

                        <p>
                            The possible owner describes a unique detail
                            only the real owner would know and provides
                            contact information.
                        </p>

                    </td>

                    <td class="step">

                        <p class="step-number">
                            03
                        </p>

                        <h3>
                            Admin verifies and returns it
                        </h3>

                        <p>
                            The admin reviews the claim. After the item is
                            returned, the case is marked as resolved.
                        </p>

                    </td>

                </tr>

            </table>

        </section>

    </main>

    <script src="Script/home.js"></script>

</body>

</html>