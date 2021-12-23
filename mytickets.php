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
  <link rel="stylesheet" href="css/mytickets.css?v=<?php echo time(); ?>">
</head>
<body>
	 <?php include_once("header.php"); ?>
   <div  style="margin: 20px;">
    <h1>Your Tickets</h1>
    <div class="main-div">
    <?php
      $userid = $_SESSION['id'];
      $sql = "SELECT * FROM `tickets` WHERE userid=$userid ORDER BY closed DESC";
      $query = mysqli_query($link,$sql);
      if (mysqli_num_rows($query) > 0) {
        while ($row= mysqli_fetch_assoc($query)) {
    ?>
      <div class="secondary-div">
        <div>
          <p>Username: <a href="profile.php?id=<?php echo $row['userid']; ?>"><?php echo $row['username']; ?></a></p>
          <p>The ticket was created at: <?php echo $row['created_at']; ?> </p>
          <p><a href="showTicket.php?id=<?php echo $row['id']; ?>">View Ticket (<?php echo $row['id']; ?>)</a></p>
        </div>
        <div>
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
      </div>
      <br>
    <?php
      }
      } else {
    ?>
    <div class="secondary-div"><p>No Tickets.</p></div>
    <?php
      } 
    ?>
    </div>
  </div>
</body>
</html>