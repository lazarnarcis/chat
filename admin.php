<?php
  session_start();
  require "config.php";
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
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
	<title>Admin</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
</head>
<body>
  <?php include_once("header.php"); ?>
  <div style="margin: 20px;">
    <h1>Admin</h1>
    <?php
      if ($_SESSION['admin'] == 0) {
        echo '<span class="user-error">Nu ai rolul de administrator!</span>';
        return;
      } else {
        ?>
          <button class="user-button" onclick='window.location.href="tickets.php"'>Tickets</button>
          <button class="user-button" onclick='window.location.href="banned.php"'>Banned users</button>
          <button class="user-button" onclick='window.location.href="admins.php"'>Admins</button>
          <button class="user-button" onclick='window.location.href="founders.php"'>Founders</button>
          <button class="user-button" onclick='window.location.href="delete-chat.php"'>Delete Chat</button>
        <?php
      }
    ?>
  </div>
</body>
</html>