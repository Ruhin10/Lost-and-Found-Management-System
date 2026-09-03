<?php

include_once __DIR__ . '/../Controller/SolvedCase.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Solved Cases</title>

    <link rel="stylesheet" href="Style/solved.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Special+Elite&family=IBM+Plex+Mono:wght@400;500;600&family=Public+Sans:wght@400;500;600;700&display=swap">
</head>

<body>

    <!-- Navigation bar -->
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

            <a href="landing.php" class="nav-link">
                Home
            </a>

            <a href="ShowList.php" class="nav-link">
                Found Items
            </a>

            <a href="PostItem.php" class="nav-link">
                Post Found Item
            </a>

            <a href="SolvedCase.php" class="nav-link active">
                Solved Cases
            </a>

        </div>

        <a href="AdminLogin.php" class="admin-login-btn">
            Admin Login
        </a>

    </nav>

    <!-- Page content -->
    <main class="page">

        <div class="page-heading">

            <div>

                <p class="small-heading">
                    SUCCESSFULLY RETURNED
                </p>

                <h1 class="header-title">
                    Solved Cases
                </h1>

            </div>

            <div class="solved-count">

                <?= count($solvedItems) ?>

                <?= count($solvedItems) === 1
                    ? 'CASE'
                    : 'CASES' ?>

            </div>

        </div>

        <hr class="header-line">

        <?php if (empty($solvedItems)): ?>

            <div class="no-cases">

                <div class="no-cases-icon">
                    ✓
                </div>

                <div class="no-cases-title">
                    No solved cases found
                </div>

                <p>
                    Resolved items will appear on this page.
                </p>

            </div>

        <?php else: ?>

            <table class="cases-table">

                <tbody>

                    <?php foreach ($solvedItems as $item): ?>

                        <?php

                        $resolvedDate = 'Date unavailable';

                        if (!empty($item['resolved_date'])) {
                            $resolvedTimestamp = strtotime(
                                (string) $item['resolved_date']
                            );

                            if ($resolvedTimestamp !== false) {
                                $resolvedDate = date(
                                    "M d, Y",
                                    $resolvedTimestamp
                                );
                            }
                        }

                        $claimantName = trim(
                            (string) (
                                $item['claimant_name'] ?? ''
                            )
                        );

                        if ($claimantName === '') {
                            $claimantName =
                                'Claimant not recorded';
                        }

                        ?>

                        <tr class="case-row">

                            <!-- Status -->
                            <td class="badge-cell">

                                <span class="badge">

                                    <?= htmlspecialchars(
                                        (string) $item['status'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </td>

                            <!-- Case information -->
                            <td class="case-information">

                                <div class="case-title">

                                    <?= htmlspecialchars(
                                        (string) $item['item_title'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </div>

                                <div class="case-meta">

                                    <span>

                                        <?= htmlspecialchars(
                                            (string) $item['category'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                    <span class="separator">
                                        •
                                    </span>

                                    <span>

                                        <?= htmlspecialchars(
                                            (string) $item['location_found'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                    <span class="separator">
                                        •
                                    </span>

                                    <span>
                                        Resolved
                                        <?= htmlspecialchars(
                                            $resolvedDate,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>

                                </div>

                                <!-- Person who took the item -->
                                <div class="received-by">

                                    <span class="received-label">
                                        RETURNED TO:
                                    </span>

                                    <span class="receiver-name">

                                        <?= htmlspecialchars(
                                            $claimantName,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </main>

</body>

</html>