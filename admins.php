<?php
  session_start();
  require "config/config.php";
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  }
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Admins</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/general2.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin: 20px;">
      <h1>Admins</h1>
      <?php 
        $admins = 0;
        if (isset($_SESSION['username'])) {
          $sql="SELECT * FROM `users` WHERE admin=1"; 
          $query = mysqli_query($link, $sql);
          if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
              $file = $row['file'];
              $id = $row['id'];
              $username = $row['username'];
              echo "
                <div class='boxes'>
                  <span style='color:black;'><img id='image' src='$file' alt='Profile'></span><br id='for-phone' />
                  <a id='link-profile' href='profile.php?id=$id'>$username</a>
                </div><br>
              ";
              $admins ++;
            }
          }
        }
        echo "<div class='boxes'><span style='color: black;'>Total admins: $admins</span></div>";
      ?>
    </div>
  </body>
</html>