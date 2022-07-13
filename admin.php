<?php
  session_start();
  require "config/config.php";
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php?redirect_link=admin.php");
    exit;
  }
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Admin</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin: 20px;">
      <h1>Admin</h1>
      <?php
        if ($_SESSION['admin'] == 0) {
          echo '<span class="user-error">You do not have the role of administrator!</span>';
          return;
        } else {
          echo "
            <p>Below you can see the options of an administrator:</p>
            <div class='options'>
              <a href='tickets.php'><p class='user-button'>Tickets</p></a>
              <a href='banned.php'><p class='user-button'>Banned users</p></a>
              <a href='admins.php'><p class='user-button'>Admins</p></a>
              <a href='founders.php'><p class='user-button'>Founders</p></a>
              <a href='delete-chat.php'><p class='user-button'>Delete Chat</p></a>
              <a href='send-mail.php'><p class='user-button'>Send Mail</p></a>
              <a href='emails.php'><p class='user-button'>Emails</p></a>
            </div>
          ";
        }
      ?>
    </div>
  </body>
</html>