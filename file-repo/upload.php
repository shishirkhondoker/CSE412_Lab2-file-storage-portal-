
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
include 'db.php';

$user_id = $_SESSION['user_id'];
$filename = $_POST['filename'];
$file = $_FILES['file'];

$target_dir = "uploads/";
if (!is_dir($target_dir)) {
    mkdir($target_dir);
}
$filepath = $target_dir . basename($file["name"]);
move_uploaded_file($file["tmp_name"], $filepath);

$sql = "INSERT INTO files (user_id, filename, filepath) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $filename, $filepath);
$stmt->execute();

header("Location: dashboard.php");
?>
