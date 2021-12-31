<?php
  session_start();
  require "config/config.php";

  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];
  }
  $user_name = $_SESSION['username'];
  $user_id = $_SESSION['id'];
  
  $queryString = "SELECT * FROM tickets WHERE id='$id'"; 
  $result = mysqli_query($link, $queryString);
  $row = mysqli_fetch_assoc($result);
  
  $ticketid = $row['id'];
  $created_at = $row['created_at'];
  $text = $row['text'];
  $userid = $row['userid'];
  $closed = $row['closed'];

  $sql = "SELECT * FROM users WHERE id=$userid";
  $newResult = mysqli_query($link, $sql);
  $newRow = mysqli_fetch_assoc($newResult);
  $ticket_username = $newRow['username'];
  $email = $newRow['email'];
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Tickets</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/showTicket.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin: 20px;">
      <div class="main-div">
      <?php 
          if ($_SESSION['admin'] == 0 && $_SESSION['id'] != $userid) {
            echo '<span class="user-error">You don\'t have access!</span>';
            return;
          } else {
            echo "<div class='secondary-div'>";
      ?>
      <div class='ticket-info'>
        <h1>Ticket #<?php echo $ticketid; ?></h1>
        <?php 
        if ($closed == 0) {
          echo "
            <div id='opened'>
              <span>Opened</span>
            </div>
          ";
        } else {
          echo "
            <div id='opened' style='background-color: #611b0f;'>
              <span>Closed</span>
            </div>
          ";
        }
        ?>
      </div>
      <div class="text">
        <div><span><?php echo $text ?></span><br></div>
        <div><span style="color:lightgrey">Email: <?php echo $email ?></span><br></div>
        <div><span style="color:lightgrey">Username: <a href="profile.php?id=<?php echo $userid ?>"><?php echo $ticket_username ?></a></span><br></div>
        <div><span style="color:lightgrey">User ID: <?php echo $userid; ?></span><br></div>
      </div>
      <form action="sendMessageFromTicket.php" method="post" id="form">
        <textarea type="text" name="message" class="user-input" placeholder="Reply as <?php echo $_SESSION["username"]; ?>..." autofocus 
          <?php
            if ($closed == 1) {
              echo "disabled";
            } else {
              echo "";
            }
          ?>
        ></textarea>
        <input type="text" name="text" id="text" value="<?php echo $ticketid; ?>" style="display: none"><br>
        <input type="submit" class="user-button" value="Reply"
          <?php
            if ($closed == 1) {
              echo "disabled";
            } else {
              echo "";
            }
          ?>
        >
      </form>
      <?php
        if ($_SESSION['admin'] == 1) {
          if ($closed == 0) {
            echo "<p><a href='closeTicket.php?id=$ticketid'>Close Ticket</a></p>";
          } else {
            echo "<p><a href='openTicket.php?id=$ticketid'>Open Ticket</a> (You can't add comments until someone opens the ticket!)</p>";
          }
        } else {
          if ($closed == 1) {
            echo "<p>You can't add comments until someone opens the ticket!</p>";
          }
        }
      ?>
      <h3>Comments:</h3>
      <div style="display: flex; flex-direction: column-reverse;">
      <?php
        $sql = "SELECT * FROM `comments` WHERE forTicket=$id";
        $querys = mysqli_query($link, $sql);
        if (mysqli_num_rows($querys) > 0) {
          while ($row = mysqli_fetch_assoc($querys)) {
            $userid = $row['userid'];
            $text = $row['text'];
            $created_at = $row['created_at'];

            $sql = "SELECT * FROM users WHERE id=$userid";
            $newResult = mysqli_query($link, $sql);
            $newRow = mysqli_fetch_assoc($newResult);
            $comment_username = $newRow['username'];
            $comment_admin = $newRow['admin'];
            $comment_file = $newRow['file'];

            echo "
              <div id='comment'>
                <div id='name'>
                <img src='$comment_file' alt='Profile Picture' id='profilePicture'>
                <span style='margin-top: 7px; margin-left: 10px; color: white;'><b><a href='profile.php?id=$userid'>$comment_username</a></b>
            ";
            if ($comment_admin == 1) {
              echo "*technical support*";
            }
            echo "
                </span>
              </div>
              <div id='message'>
                <span style='white-space: pre-wrap;'>$text</span>
              </div>
              <small style='float: right;'>$created_at</small>
            </div>";
          }
        } else {
          echo "<div id='comment'><p>No Comments.</p></div>";
        } 
      echo "
      </div>
      <p id='data-sended'>The ticket was created at: $created_at</p>
      ";
          }
      ?>
      </div>
    </div>
  </body>
</html>