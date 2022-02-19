<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  }
  require "config/config.php" ;
  $confirm_err = "";
  if (!empty($_GET['err_message'])) {
    $confirm_err = $_GET['err_message'];
  }
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Delete Chat</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin:20px;">
      <?php
        if ($_SESSION['admin'] == 0) {
          echo '<span class="user-error">You do not have the role of administrator!</span>';
          return;
        } else {
          echo "
            <h1>Are you sure you want to delete the chat permanently?</h1>
            <form action='actions.php?action=delete_chat' method='post'> 
              <div class='form-check'>
                <input class='form-check-input' type='checkbox' value='delete' id='delete' name='delete'>
                <label class='form-check-label' for='delete'>
                  Yes, I want to delete the chat.
                </label>
              </div>
              <span class='user-error'>$confirm_err</span>
              <br>
              <button class='user-button' type='submit'>Delete chat</button>
            </form>
          ";
        } 
      ?>
    </div>
  </body>
</html>