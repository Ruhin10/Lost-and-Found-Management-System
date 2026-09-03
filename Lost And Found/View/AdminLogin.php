<?php

$successMessage = '';

if (
    isset($_GET['message']) &&
    $_GET['message'] === 'logged_out'
) {
    $successMessage = 'You have successfully logged out.';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Redirect an already logged-in admin */
if (!empty($_SESSION['admin_logged_in'])) {
    header("Location: AdminDashboard.php");
    exit;
}

/* Error messages received from the controller */
$errorMessages = [
    'empty_fields' => 'Please enter your username and password.',
    'invalid_credentials' => 'Incorrect username or password.',
    'database_error' => 'A database error occurred. Please try again.',
    'login_required' => 'Please log in to access the admin dashboard.'
];

$errorCode = (string) ($_GET['error'] ?? '');

$errorMessage = $errorMessages[$errorCode] ?? '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Admin Login</title>

    <link rel="stylesheet" href="Style/adminLogin.css">

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

            <a href="SolvedCase.php" class="nav-link">
                Solved Cases
            </a>

        </div>

        <a
            href="AdminLogin.php"
            class="admin-login-btn active-admin">
            Admin Login
        </a>

    </nav>

    <?php if ($successMessage !== ''): ?>

    <div class="logout-success">

        <?= htmlspecialchars(
            $successMessage,
            ENT_QUOTES,
            'UTF-8'
        ) ?>

    </div>

<?php endif; ?>

    <!-- Login page -->
    <main class="login-page">

        <div class="login-box">

            <div class="login-icon">
                LF
            </div>

            <div class="login-heading">
                ADMIN PORTAL
            </div>

            <h1>
                Welcome Back
            </h1>

            <p class="login-description">
                Sign in to manage found items, claims and solved cases.
            </p>

            <!-- PHP error message -->
            <?php if ($errorMessage !== ''): ?>

                <div class="server-error">

                    <?= htmlspecialchars(
                        $errorMessage,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </div>

            <?php endif; ?>

            <!-- JavaScript error message -->
            <div
                id="loginError"
                class="client-error"
                aria-live="polite">
            </div>

            <form
                id="adminLoginForm"
                action="../Controller/AdminLogin.php"
                method="POST"
                novalidate>

                <!-- Username -->
                <div class="form-group">

                    <label for="username">
                        USERNAME
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username"
                        maxlength="50"
                        autocomplete="username"
                        required>

                </div>

                <!-- Password -->
                <div class="form-group">

                    <label for="password">
                        PASSWORD
                    </label>

                    <div class="password-field">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            maxlength="255"
                            autocomplete="current-password"
                            required>

                        <button
                            type="button"
                            id="togglePassword"
                            class="password-toggle">
                            Show
                        </button>

                    </div>

                </div>

                <!-- Login button -->
                <button
                    type="submit"
                    class="login-submit">

                    <span>Log In</span>

                    <span class="button-arrow">
                        &rarr;
                    </span>

                </button>

            </form>

            <div class="login-divider"></div>

            <p class="login-note">
                Admin access only. Public users never need to log in.
            </p>

        </div>

    </main>

    <script src="../Script/adminLogin.js"></script>

</body>

</html>