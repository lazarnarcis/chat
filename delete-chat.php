<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  }
  require "config/config.php" ;
  $confirm_err = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_SESSION['username'];
    if (!isset($_POST['delete'])) {
      $confirm_err = 'Please confirm by pressing the checkbox. </br>';
    }
    if (empty($confirm_err)) {
      $sql = "DELETE FROM chat";
      mysqli_query($link, $sql);
      $sql = "INSERT INTO notifications (text, userid) VALUES ('You deleted the chat.', '".$_SESSION['id']."')";
      mysqli_query($link, $sql);
      $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$name deleted the chat.')";
      mysqli_query($link, $sql);
      header("location: login.php");
    }
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
          echo '<span class="user-error">Nu ai rolul de administrator!</span>';
          return;
        } else {
          $form_action = htmlspecialchars($_SERVER["PHP_SELF"]);
          echo "
            <h1>Are you sure you want to delete the chat permanently?</h1>
            <form action='$form_action' method='post'> 
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