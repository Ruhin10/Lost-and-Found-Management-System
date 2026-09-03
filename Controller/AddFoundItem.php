<?php

require_once __DIR__ . '/../Model/DatabaseConnection.php';

function redirectWithError($errorCode)
{
    header(
        "Location: ../View/PostItem.php?error=" .
        urlencode($errorCode)
    );

    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../View/PostItem.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Receive form data
|--------------------------------------------------------------------------
*/

$itemTitle = trim(
    $_POST['item_title'] ?? ''
);

$category = trim(
    $_POST['category'] ?? ''
);

$dateFound = trim(
    $_POST['date_found'] ?? ''
);

$description = trim(
    $_POST['description'] ?? ''
);

$locationFound = trim(
    $_POST['location_found'] ?? ''
);

$finderName = trim(
    $_POST['finder_name'] ?? ''
);

$contact = trim(
    $_POST['contact'] ?? ''
);

/*
|--------------------------------------------------------------------------
| Validate category
|--------------------------------------------------------------------------
*/

$allowedCategories = [
    'Electronics',
    'Bags',
    'Documents',
    'Accessories',
    'Clothing',
    'Personal Items',
    'Stationery'
];

if (!in_array($category, $allowedCategories, true)) {
    redirectWithError('invalid_data');
}

/*
|--------------------------------------------------------------------------
| Validate fields
|--------------------------------------------------------------------------
*/

$dateObject = DateTime::createFromFormat(
    'Y-m-d',
    $dateFound
);

$isValidDate =
    $dateObject !== false &&
    $dateObject->format('Y-m-d') === $dateFound;

if (
    strlen($itemTitle) < 3 ||
    strlen($itemTitle) > 150 ||
    !$isValidDate ||
    $dateFound > date('Y-m-d') ||
    strlen($description) < 10 ||
    strlen($locationFound) < 2 ||
    strlen($locationFound) > 255 ||
    strlen($finderName) < 3 ||
    strlen($finderName) > 100 ||
    strlen($contact) < 5 ||
    strlen($contact) > 150
) {
    redirectWithError('invalid_data');
}

/*
|--------------------------------------------------------------------------
| Upload photo
|--------------------------------------------------------------------------
*/

$photoFileName = null;
$uploadedPhotoPath = null;

if (
    isset($_FILES['photo']) &&
    $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE
) {
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        redirectWithError('upload_failed');
    }

    $maximumFileSize = 5 * 1024 * 1024;

    if ($_FILES['photo']['size'] > $maximumFileSize) {
        redirectWithError('invalid_photo');
    }

    $fileInformation = new finfo(FILEINFO_MIME_TYPE);

    $mimeType = $fileInformation->file(
        $_FILES['photo']['tmp_name']
    );

    $allowedImageTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($allowedImageTypes[$mimeType])) {
        redirectWithError('invalid_photo');
    }

    $uploadDirectory =
        __DIR__ . '/../uploads/';

    if (!is_dir($uploadDirectory)) {
        if (
            !mkdir(
                $uploadDirectory,
                0755,
                true
            )
        ) {
            redirectWithError('upload_failed');
        }
    }

    $extension = $allowedImageTypes[$mimeType];

    try {
        $photoFileName =
            bin2hex(random_bytes(16)) .
            '.' .
            $extension;
    } catch (Exception $exception) {
        redirectWithError('upload_failed');
    }

    $uploadedPhotoPath =
        $uploadDirectory .
        $photoFileName;

    if (
        !move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            $uploadedPhotoPath
        )
    ) {
        redirectWithError('upload_failed');
    }
}

/*
|--------------------------------------------------------------------------
| Save item to database
|--------------------------------------------------------------------------
*/

$database = new DatabaseConnection();
$connection = $database->openConnection();

$sql = "
    INSERT INTO found_items (
        item_title,
        category,
        date_found,
        description,
        location_found,
        photo,
        contact,
        finder_name,
        status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
";

$stmt = $connection->prepare($sql);

if (!$stmt) {
    if (
        $uploadedPhotoPath !== null &&
        file_exists($uploadedPhotoPath)
    ) {
        unlink($uploadedPhotoPath);
    }

    error_log($connection->error);
    $connection->close();

    redirectWithError('database_error');
}

$stmt->bind_param(
    "ssssssss",
    $itemTitle,
    $category,
    $dateFound,
    $description,
    $locationFound,
    $photoFileName,
    $contact,
    $finderName
);

if (!$stmt->execute()) {
    if (
        $uploadedPhotoPath !== null &&
        file_exists($uploadedPhotoPath)
    ) {
        unlink($uploadedPhotoPath);
    }

    error_log($stmt->error);

    $stmt->close();
    $connection->close();

    redirectWithError('database_error');
}

$stmt->close();
$connection->close();

header(
    "Location: ../View/PostItem.php?" .
    "message=item_posted"
);

exit;