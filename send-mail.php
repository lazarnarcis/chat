<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  }
  require "config/config.php" ;
  $confirm_err = "";
  $message = "";
  if (!empty($_GET['err_message'])) {
    $confirm_err = $_GET['err_message'];
  }
  if (!empty($_GET['message'])) {
    $message = $_GET['message'];
  }
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Send Mail</title>
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
            <h1><span style='color: red'>Attention:</span> Send an email to all users of the site with a single click!</h1>
            <form action='actions.php?action=send_mail' method='post'>
              <div class='input'>
                <textarea type='text' name='message' class='user-input' placeholder='Message'>$message</textarea>
              </div>   
              <div class='form-check'>
                <input class='form-check-input' type='checkbox' id='send_mail' name='send_mail'>
                <label class='form-check-label' for='send_mail'>
                  Yes, I want to send this mail to all users.
                </label>
              </div>
              <span class='user-error'>$confirm_err</span>
              <br>
              <button class='user-button' type='submit'>Send Mail to All</button>
            </form>
          ";
        } 
      ?>
    </div>
  </body>
</html>