<?php

require_once "config/database.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$db = new Database;

$id = $_SESSION['user_id'];

$images = $db->listImages($id);

error_reporting(E_ALL);

ini_set('display_errors', 1);

if (isset($_POST['submit'])) {

    $file = $_FILES['image'];

    // File details
    $fileName = $file['name'];
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Get file extention

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Allowed Images

    $allowed = ['png', 'jpg', 'jpeg', 'gif'];

    if (in_array($fileExt, $allowed)) {

        if ($fileError === 0) {
            if ($fileSize < 3000000) {
                // Create file name
                $newFileName = uniqid("IMG_", true) . "." . $fileExt;

                // Upload location
                $uploadPath = "uploads/" . $newFileName;

                //Move file
                if (move_uploaded_file($fileTmp, $uploadPath)) {
                    // Insert into the database
                    $id = $_SESSION['user_id'];
                    $db->uploadImage($uploadPath, $id);
                } else {
                    echo "Failed to move image";
                }
            } else {
                echo "Image is too large";
            }
        } else {
            echo "There was an error";
        }
    } else {
        echo "Invalid file type";
    }
}
?>

<form method="POST" enctype="multipart/form-data">

    <input type="file" name="image" required>

    <button type="submit" name="submit">Upload</button>

</form>

<br><br>

<h3>All Images Uploaded by you</h3>

<?php
if ($images->num_rows > 0) {

    while ($image = $images->fetch_assoc()) { ?>

        <img src="<?php echo $image['path']; ?>" style="width:250px;height:250px;">

<?php }
} else {

    echo "You have not uploaded any image";
}
?>