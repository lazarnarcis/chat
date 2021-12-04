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
	<title>Admins</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
  <style type="text/css">
    #image {
        float: left;
        width: 50px;
        margin-right: 10px;
        height: 50px;
        border-radius: 25px;
    }
    .send-message {
      border: 0px;
      background-color: #4295f5;
      color: white;
      padding: 5px;
      font-family: Arial, Helvetica, sans-serif;
    }
    .send-message:hover {
      background: #3238a8;
    }
    .boxes {
        background-color: #fff;
        padding:10px;
        color:#080d4d;
        transition: .3s;
        box-shadow: inset 0px 0px 10px lightgrey;
        border-radius: 10px;
        display: flex;
    }
    .boxes a {
      margin-top: 7.5px;
    }
    body {
      font-size: 18px;
    }
    #for-phone {
      display: none;
    }
    #link-profile {
      text-decoration: none;
      color: lightblue;
      font-size: 30px;
    }
    #link-profile:hover {
      text-decoration: underline;
      color: #34b1eb;
    }
    @media only screen and (max-width: 1000px) {
      * {
        text-align: center;
      }
      #image {
        float: none;
        margin-right: 0px;
      }
      #for-phone {
        display: inline;
      }
      .boxes {
        align-items: center;
        justify-content: center;
      }
      .boxes a {
        margin-top: -7.5px;
        margin-left: 7.5px;
      }
    }
  </style>
</head>
<body>
	 <?php include_once("header.php"); ?>
   <div  style="margin: 20px;">
    <h1>Admins</h1>
        <?php 
        $button = 0;
          if(isset($_SESSION['username'])) { 
            $usern = $_SESSION['id']; 
            $sql="SELECT * FROM `users` WHERE admin=1"; 
            $query = mysqli_query($link,$sql);
            if (mysqli_num_rows($query) > 0) {
                while ($row= mysqli_fetch_assoc($query)) {
        ?>
        <div class="boxes">
          <span style="color:black;"><img id="image" src="images/<?php echo $row['file']; ?>" alt="Profile"></span><br id="for-phone" />
          <a id="link-profile" href="profile.php?id=<?php echo $row['id']; ?>"><?php echo $row['username']; ?></a>
        </div>
        <br>
    <?php
    $button ++;
      }
    } else {
?>
<div class="post-by-user"><p>There are no administrators.</p></div>
<?php
  } 
}
echo "Total admins: $button";
?>
</body>
</html>