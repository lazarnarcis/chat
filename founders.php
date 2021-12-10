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
	<title>Founders</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="css/founders.css">
</head>
<body>
	 <?php include_once("header.php"); ?>
   <div style="margin: 20px;">
    <h1>Founders</h1>
        <?php 
        $button = 0;
          if(isset($_SESSION['username'])) { 
            $usern = $_SESSION['id']; 
            $sql="SELECT * FROM `users` WHERE founder=1"; 
            $query = mysqli_query($link,$sql);
            if (mysqli_num_rows($query) > 0) {
                while ($row= mysqli_fetch_assoc($query)) {
        ?>
        <div class="boxes">
          <span style="color:black;"><img id="image" src="<?php echo $row['file']; ?>" alt="Profile"></span><br id="for-phone" />
          <a id="link-profile" href="profile.php?id=<?php echo $row['id']; ?>"><?php echo $row['username']; ?></a>
        </div>
        <br>
    <?php
    $button ++;
      }
    } else {
?>
<div class="post-by-user"><p>There are no founders.</p></div>
<?php
  } 
}
echo "Total founders: $button";
?>
</body>
</html>