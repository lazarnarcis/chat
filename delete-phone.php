<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      header("location: login.php");
      exit;
  }
  require "config.php" ;
  $confirm_err = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_SESSION['username'];
    if (!isset($_POST['delete'])) {
      $confirm_err = 'Please confirm by pressing the checkbox. </br>';
    }
    if (empty($confirm_err)) {
      $sql = "UPDATE users SET phone='0' WHERE username='$name'";
      $query = mysqli_query($link,$sql);
      $sqls = "INSERT INTO notifications (texts,userid) VALUES ('(".$_SESSION['username'].") Your phone number (".$_SESSION['phone'].") has been deleted.', '".$_SESSION['id']."')";
      $querys = mysqli_query($link,$sqls);
      if ($query && $querys) {
        $_SESSION['phone'] = 0;
        $id = $_SESSION['id'];
        header('location: profile.php?id='.$id.'');
      } else {
        $confirm_err = "Something went wrong";
      }
    }
  }
?> 
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
	<title>Delete Phone</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
	<link rel="stylesheet" href="css/delete-phone.css">
</head>
<body>
	 <?php include_once("header.php"); ?>
    <div style="margin:20px;"><h1>Are you sure you want to delete your phone?</h1>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
          <div class="form-check <?php echo (!empty($confirm_err)) ? 'has-error' : ''; ?>">
            <input class="form-check-input" type="checkbox" value="delete" id="delete" name="delete">
            <label class="form-check-label" for="delete">
              Yes, I want to delete my phone.
            </label>
          </div>
          <span class="help-block"><?php echo $confirm_err; ?></span>
          <br>
        <button class="btn btn-primary" type="submit">Delete phone</button>
      </form>
    </div>
</body>
</html>