<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
include 'db.php';
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f9fa;
      padding: 20px;
    }
    .container {
      max-width: 800px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .logout-btn {
      background: #dc3545;
      color: white;
      padding: 8px 14px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
    }
    .logout-btn:hover {
      background: #c82333;
    }
    h2, h3 {
      text-align: center;
      color: #333;
    }
    form {
      margin-bottom: 30px;
      text-align: center;
    }
    input[type="text"], input[type="file"] {
      padding: 10px;
      margin: 10px 0;
      width: 80%;
    }
    button {
      padding: 10px 20px;
      background: #28a745;
      border: none;
      color: white;
      cursor: pointer;
      border-radius: 5px;
    }
    button:hover {
      background: #218838;
    }
    .file-list {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    .file-item {
      border: 1px solid #ccc;
      padding: 10px;
      width: 200px;
      text-align: center;
      border-radius: 8px;
      background: #fafafa;
    }
    .file-item img {
      max-width: 100%;
      max-height: 150px;
      border-radius: 5px;
    }
    .file-item a {
      display: block;
      margin-top: 10px;
      color: #007bff;
      text-decoration: none;
    }
    .file-item a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="top-bar">
      <h2>Upload File</h2>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="filename" placeholder="Enter File Name" required><br>
      <input type="file" name="file" required><br>
      <button type="submit">Upload</button>
    </form>

    <h3>Your Uploaded Files</h3>
    <div class="file-list">
    <?php
      $sql = "SELECT * FROM files WHERE user_id=? ORDER BY uploaded_at DESC";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
          echo "<div class='file-item'>";
          $filePath = htmlspecialchars($row['filepath']);
          $fileName = htmlspecialchars($row['filename']);
          $fileExt = pathinfo($filePath, PATHINFO_EXTENSION);
          if (in_array(strtolower($fileExt), ['jpg','jpeg','png','gif','webp'])) {
              echo "<img src='{$filePath}' alt='{$fileName}'>";
          } else {
              echo "<p>{$fileName}</p>";
          }
          echo "<a href='{$filePath}' download>Download</a>";
          echo "<a href='delete.php?id={$row['id']}' style='color:red;'>Delete</a>";
          echo "</div>";
      }
    ?>
    </div>
  </div>
</body>
</html>
