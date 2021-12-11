<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      header("location: login.php");
      exit;
  }
  require "config.php";
  $msg = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION['id'];
    $image = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    if(!file_exists($file_tmp)) {
      $msg = "File is not set!";
    } else {
      if ($file_type == "image/png" OR $file_type == "image/jpeg" OR $file_type == "image/JPEG" OR $file_type == "image/PNG") {
        if ($file_size > 2097152) {
          $msg = 'File size must be excately 2 MB!';
        }
        list($width, $height) = getimagesize($file_tmp);
        if ($width > "1000" || $height > "1000") {
          $msg = "Error: Image size must be max 1000 x 1000 pixels.";
        }
        $data = file_get_contents($file_tmp);
        $base64 = 'data:' . $file_type . ';base64,' . base64_encode($data);
        if (empty($msg)) {
          $sql = "UPDATE users SET file='$base64' WHERE id='$id'";
          mysqli_query($link, $sql);
          $sqlx = "UPDATE comments SET file='$base64' WHERE userid='$id'";
          mysqli_query($link, $sqlx);
          $sqlz = "UPDATE chat SET file = ? WHERE userid = ?";
          if ($stmx = mysqli_prepare($link, $sqlz)) {
              mysqli_stmt_bind_param($stmx, "si", $base64, $user_id);
              $user_id = $_SESSION['id'];
              if (mysqli_stmt_execute($stmx)) {
                  $lastname = $_SESSION['username'];
                  $sqlx = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname changed his profile picture.')";
                  $queryx = mysqli_query($link,$sqlx);
                  $_SESSION['file'] = $base64;
                  header('location: profile.php?id='.$id.'');
              } else {
                  $msg = "Oops! Something went wrong. Please try again later.";
              }
          }
        }
      } else {
        $msg = "Please insert a JPEG or PNG image.";
      }
    }
  }
?> 
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
	<title>Change Profile Photo</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="css/change-photo.css?v=<?php echo time(); ?>">
</head>
<body>
  <?php include_once("header.php"); ?>
  <div  style="margin: 20px;">
    <h1>Change Profile Photo</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
      <label class="custom-file-upload">
        <input type="file" name="image" />
        Press here to choose file
      </label>
      <br>
      <span class="help-block"><?php echo $msg; ?></span>
      <br>
      <button type="submit" class="btn-primary">Change photo</button>
    </form>
  </div>
</body>
</html>