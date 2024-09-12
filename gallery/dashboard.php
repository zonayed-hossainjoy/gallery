<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['user'];
$uploadDir = 'uploads/' . $username . '/';
$imagesFile = $uploadDir . 'images.txt';

// Ensure the upload directory exists for the user
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $imageName = $_FILES['image']['name'];
    $targetFile = $uploadDir . basename($imageName);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        file_put_contents($imagesFile, $targetFile . "\n", FILE_APPEND);
    }
}

// Read user's uploaded images
$images = [];
if (file_exists($imagesFile)) {
    $images = file($imagesFile, FILE_IGNORE_NEW_LINES);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <h3>Upload Image</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" required>
            <button type="submit">Upload</button>
        </form>

        <h3>Your Uploaded Images</h3>
        <div class="image-gallery">
            <?php foreach ($images as $image): ?>
                <img src="<?php echo $image; ?>" alt="Uploaded Image" width="100px" height="100px">
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
