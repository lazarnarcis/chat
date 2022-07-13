<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php?redirect_link=change-photo.php");
    exit;
  }
  require "config/config.php";
  $new_photo_err = "";
  if (!empty($_GET['new_photo_err'])) {
    $new_photo_err = $_GET['new_photo_err'];
  }
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Change Profile Photo</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/change-photo.css?v=<?php echo time(); ?>">
    <script src="jquery/jquery.js"></script>
    <script>
      $(document).ready(function() {
        $('#image').change(function() {
          var i = $(this).prev('label').clone();
          var file = $('#image')[0].files[0].name;
          if (file.length > 25) file = file.substring(0, 25) + "...";
          $(this).prev('label').text(file);
        });
      });
    </script>
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin: 20px;">
      <h1>Change Profile Photo</h1>
      <form method="POST" action="actions.php?action=change_photo" enctype="multipart/form-data">
        <div>
          <label for="image" class="custom-file-upload">
            Click here to add a profile picture
          </label>
          <input id="image" name="image" type="file" style="display:none;">
          <br>
          <span class="user-error"><?php echo $new_photo_err; ?></span>
        </div>
        <br>
        <button type="submit" class="user-button">Change photo</button>
      </form>
    </div>
  </body>
</html>