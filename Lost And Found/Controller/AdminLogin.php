<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Model/DatabaseConnection.php';

function redirectToLogin(string $errorCode): void
{
    header(
        "Location: ../View/AdminLogin.php?error=" .
        urlencode($errorCode)
    );

    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/AdminLogin.php");
    exit;
}

$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    redirectToLogin('empty_fields');
}

$database = new DatabaseConnection();
$connection = $database->openConnection();

$sql = "
    SELECT
        id,
        username,
        password,
        full_name
    FROM admins
    WHERE username = ?
    LIMIT 1
";

$stmt = $connection->prepare($sql);

if (!$stmt) {
    error_log($connection->error);
    $connection->close();

    redirectToLogin('database_error');
}

$stmt->bind_param("s", $username);
$stmt->execute();

$adminId = 0;
$databaseUsername = '';
$passwordHash = '';
$adminFullName = '';

$stmt->bind_result(
    $adminId,
    $databaseUsername,
    $passwordHash,
    $adminFullName
);

$adminFound = $stmt->fetch();

$stmt->close();

if (
    $adminFound !== true ||
    $passwordHash === '' ||
    !password_verify($password, $passwordHash)
) {
    $connection->close();
    redirectToLogin('invalid_credentials');
}

session_regenerate_id(true);

$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_id'] = (int) $adminId;
$_SESSION['admin_username'] = $databaseUsername;
$_SESSION['admin_name'] = $adminFullName;

/* Update last login date */
$updateSql = "
    UPDATE admins
    SET last_login = CURRENT_TIMESTAMP
    WHERE id = ?
";

$updateStmt = $connection->prepare($updateSql);

if ($updateStmt) {
    $updateStmt->bind_param("i", $adminId);
    $updateStmt->execute();
    $updateStmt->close();
}

$connection->close();

header("Location: ../View/AdminDashboard.php");
exit;