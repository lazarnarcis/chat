<?php
  session_start();
  require 'config.php';
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];
  }
  $queryString = "SELECT id, created_at, userid, texts FROM notifications WHERE userid='$id' ORDER BY id DESC"; 
  $query = $link->prepare($queryString);
  $query->execute();
  $query->store_result();
  $query->bind_result($notifid, $created_at, $userid, $texts);
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
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
  <title>Notifications</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="css/notifications.css?v=<?php echo time(); ?>">
</head>
<body>
	 <?php include_once("header.php"); ?>
    <div style="margin: 20px;">
      <h1>Notifications</h1>
      <div class="main-div">
        <?php while ($query->fetch()) : ?>
          <div class="post-by-user">
            <span><?php echo $texts; ?></span>
            <span id="user-text-x"><?php echo $created_at; ?> </span>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
</body>
</html>