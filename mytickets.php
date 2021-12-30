<?php
  session_start();
  require "config/config.php";
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
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Your Tickets</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/tickets.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin: 20px;">
      <h1>Your Tickets</h1>
      <div class="main-div">
      <?php
        $sql = "SELECT * FROM `tickets` WHERE userid=$userid ORDER BY closed DESC";
        $query = mysqli_query($link, $sql);
        if (mysqli_num_rows($query) > 0) {
          while ($row = mysqli_fetch_assoc($query)) {
            $userid = $row['userid'];
            $username = $row['username'];
            $created_at = $row['created_at'];
            $ticket_id = $row['id'];
            echo "
              <div class='secondary-div'>
                <div>
                  <p>Username: <a href='profile.php?id=$userid'>$username</a></p>
                  <p>The ticket was created at: $created_at </p>
                  <p><a href='showTicket.php?id=$ticket_id'>View Ticket ($ticket_id)</a></p>
                </div>
              <div>
            ";
            if ($row['closed'] == 0) {
              echo "
                <div id='opened'>
                  <span>Opened</span>
                </div>
              ";
            } else if ($row['closed'] == 1) {
              echo "
                <div id='opened' style='background-color: #611b0f;'>
                  <span>Closed</span>
                </div>
              ";
            }
            echo "
              </div>
              </div>
              <br>
            ";
          }
        } else {
          echo "<div class='secondary-div'><p>No Tickets.</p></div>";
        } 
      ?>
      </div>
    </div>
  </body>
</html>