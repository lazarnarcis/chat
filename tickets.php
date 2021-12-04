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
	<title>Tickets</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
  <style type="text/css">
    .tooltipssa {
      display: flex;
      flex-direction: column-reverse;
    }
    a:hover {
      color: blue;
    }
    .post-by-user {
      border: 2.5px solid lightblue;
      border-radius: 5px;
      padding: 20px;
    }
    body {
      font-size: 18px;
    }
    a {
      text-decoration: none;
      color: blue;
    }
    a:hover {
      text-decoration: underline;
    }
    #opened {
      float: right;
      border-radius: 10px;
      background-color: #05822f;
      padding: 7.5px;
      color: white;
    }
    p {
      margin: 0;
    }
    @media only screen and (max-width: 1000px) {
      * {
        text-align:center;
      }
    }
  </style>
</head>
<body>
	 <?php include_once("header.php"); ?>
   <div  style="margin: 20px;">
    <h1>Tickets</h1>
    <?php
      if ($_SESSION['admin'] == 0) {
        echo '<span class="help-block">Nu ai rolul de administrator!</span>';
        return;
      } else {
    ?>
    <div class="tooltipssa">
    <?php
      $sql = "SELECT * FROM `tickets` ORDER BY closed DESC";
      $query = mysqli_query($link,$sql);
      if (mysqli_num_rows($query) > 0) {
        while ($row= mysqli_fetch_assoc($query)) {
    ?>
      <div class="post-by-user">
        <p>Username: <a href="profile.php?id=<?php echo $row['userid']; ?>"><?php echo $row['username']; ?></a></p>
        <p>The ticket was created at: <?php echo $row['created_at']; ?> </p>
        <p><a href="showTicket.php?id=<?php echo $row['id']; ?>">View Ticket (<?php echo $row['id']; ?>)</a></p>
        <?php
          if ($row['closed'] == 0) {
            ?>
              <div id="opened">
                <span>Opened</span>
              </div>
            <?php
          } else if ($row['closed'] == 1) {
            ?>
              <div id="opened" style="background-color: #611b0f;">
                <span>Closed</span>
              </div>
            <?php
          }
        ?>
      </div>
      <br>
    <?php
      }
      } else {
    ?>
    <div class="post-by-user"><p>No Tickets.</p></div>
    <?php
      } 
    ?>
    </div>
    <?php
      }
    ?>
  </div>
</body>
</html>