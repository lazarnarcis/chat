<?php
  session_start();
  require "config/config.php";
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php?redirect_link=mytickets.php");
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
    <?php 
      require_once("header.php");
      $number_notif = mysqli_query($link, "SELECT COUNT(*) FROM tickets WHERE userid=$userid AND closed=0");
      $total_notif = mysqli_fetch_row($number_notif)[0];
    ?>
    <div style="margin: 20px;">
      <h1>Your Tickets (#<?php echo $total_notif; ?> opened)</h1>
      <div class="main-div">
      <?php
        $sql = "SELECT * FROM `tickets` WHERE userid=$userid ORDER BY closed DESC";
        $query = mysqli_query($link, $sql);
        if (mysqli_num_rows($query) > 0) {
          while ($row = mysqli_fetch_assoc($query)) {
            $userid = $row['userid'];
            $created_at = $row['created_at'];
            $ticket_id = $row['id'];

            $sql = "SELECT * FROM users WHERE id=$userid";
            $newResult = mysqli_query($link, $sql);
            $newRow = mysqli_fetch_assoc($newResult);
            $ticket_username = $newRow['username'];
            echo "
              <a class='secondary-div' href='show-ticket.php?id=".$ticket_id."'>
                <div>
                  <h3 id='ticket_id'>Ticket (#".$ticket_id.")</h3>
                </div>
                <div>
                  <p>Username: <b>".$ticket_username."</b></p>
                  <p>The ticket was created at: <b>".$created_at."</b></p>
                </div>
            ";
            if ($row['closed'] == 0) {
              echo "
                <div id='opened'>
                  <span>Opened</span>
                </div>
              ";
            } else if ($row['closed'] == 1) {
              echo "
                <div id='opened' style='background-color: #874c57;'>
                  <span>Closed</span>
                </div>
              ";
            }
            echo "</a>";
          }
        } else {
          echo "<div class='secondary-div'><p>No Tickets.</p></div>";
        } 
      ?>
    </div>
    </div>
  </body>
</html>