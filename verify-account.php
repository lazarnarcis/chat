<?php
    session_start();
    require "config/config.php";
    
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      header("location: login.php");
      exit;
    }

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
    <title>Send email verification</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin:20px;">
      <h1>Are you sure we want to send you an account verification email?</h1>
      <form action="actions.php?action=verify_account" method="post"> 
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="email-verification" id="email-verification" name="email-verification">
          <label class="form-check-label" for="email-verification">
            Yes, I am.<br>
            My is my email: <?php echo $_SESSION['email']; ?><br>
            After clicking the "Send email verification" button you will have to go to the email and search for the email. Please also check the "Spam" section.
          </label>
        </div>
        <span class="user-error"><?php echo $confirm_err; ?></span>
        <br>
        <button class="user-button" type="submit">Send email verification</button>
      </form>
    </div>
  </body>
</html>