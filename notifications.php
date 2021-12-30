<?php
  session_start();
  require 'config/config.php';
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];
  }
  if ($_SESSION['admin'] == 0 && $_SESSION['id'] != $id) {
    header("location: home.php");
    return;
  }
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Notifications</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/notifications.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin: 20px;">
      <?php
        $sql = "SELECT username FROM users WHERE id=$id";
        $query1 = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($query1);
        $user = $row['username'];
        echo "<h1>$user's Notifications</h1>";
      ?>
      <div class="main-div">
        <?php 
        $sql = "SELECT * FROM notifications WHERE userid='$id' ORDER BY id DESC";
        $result = mysqli_query($link, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $text = $row['text'];
            $created_at = $row['created_at'];
            echo "
              <div class='secondary-div'>
                <span>$text</span>
                <span id='created_at'>$created_at</span>
              </div>
            ";
          }
        } else {
          echo "<div class='secondary-div'><span>No notifications!</span></div>";
        } 
        ?>
      </div>
    </div>
  </body>
</html>