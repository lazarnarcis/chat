<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php?redirect_link=delete-bio.php");
    exit;
  }
  require "config/config.php";
  $err_message = "";
  if (!empty($_GET['err_message'])) {
    $err_message = $_GET['err_message'];
  }
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Delete Bio</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin:20px;">
      <h1>Are you sure you want to delete your bio?</h1>
      <form action="actions.php?action=delete_bio" method="post"> 
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="delete" id="delete" name="delete">
          <label class="form-check-label" for="delete">
            Yes, I want to delete my bio.
          </label>
        </div>
        <span class="user-error"><?php echo $err_message; ?></span>
        <br>
        <button class="user-button" type="submit">Delete Bio</button>
      </form>
    </div>
  </body>
</html>