<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  }
  require "config/config.php";
  $confirm_err = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_SESSION['username'];
    if (!isset($_POST['delete'])) {
      $confirm_err = 'Please confirm by pressing the checkbox.';
    }
    if (empty($confirm_err)) {
      $sql = "UPDATE users SET bio='' WHERE username='$name'";
      mysqli_query($link, $sql);
      $sql = "INSERT INTO notifications (text, userid) VALUES ('Your bio <b>".$_SESSION['bio']."</b> has been deleted.', '".$_SESSION['id']."')";
      mysqli_query($link, $sql);
      $_SESSION['bio'] = "";
      $id = $_SESSION['id'];
      header('location: profile.php?id='.$id.'');
    }
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
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="delete" id="delete" name="delete">
          <label class="form-check-label" for="delete">
            Yes, I want to delete my bio.
          </label>
        </div>
        <span class="user-error"><?php echo $confirm_err; ?></span>
        <br>
        <button class="user-button" type="submit">Delete Bio</button>
      </form>
    </div>
  </body>
</html>