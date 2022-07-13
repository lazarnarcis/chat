<?php
  session_start();
  require "config/config.php";

  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php?redirect_link=showTicket.php");
    exit;
  } 
  if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];
    $queryString = "SELECT * FROM tickets WHERE id=$id"; 
    $result = mysqli_query($link, $queryString);
    $row = mysqli_fetch_assoc($result);
    
    $ticketid = $row['id'];
    $created_at = $row['created_at'];
    $text = $row['text'];
    $user_id = $row['userid'];
    $closed = $row['closed'];

    $sql = "SELECT * FROM users WHERE id=$user_id";
    $newResult = mysqli_query($link, $sql);
    $newRow = mysqli_fetch_assoc($newResult);
    $ticket_username = $newRow['username'];
    $email = $newRow['email'];
  }

  $err_message = "";
  if (!empty($_GET['err_message'])) {
    $err_message = $_GET['err_message'];
  }
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
      <h2 id="err_message"><?php echo $err_message; ?></h2>
      <div class="main-div">
      <?php 
        if ($_SESSION['admin'] == 0 && $_SESSION['id'] != $user_id) {
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
            <div id='opened' style='background-color: #874c57;'>
              <span>Closed</span>
            </div>
          ";
        }
        ?>
      </div>
      <div class="text">
        <div><span style="color:white; font-weight: bold; font-style: italic;"><?php echo $text ?></span><br></div>
        <div><span style="color:white">Email: <?php echo $email ?></span><br></div>
        <div><span style="color:white">Username: <a href="profile.php?id=<?php echo $user_id ?>"><?php echo $ticket_username ?></a></span><br></div>
        <div><span style="color:white">User ID: <?php echo $user_id; ?></span><br></div>
      </div>
      <?php 
        if ($closed == 0) {
          echo '
            <form action="actions.php?action=send_ticket_message" method="post" id="form">
              <textarea type="text" name="message" class="user-input" placeholder="Reply as '.$_SESSION["username"].'..." autofocus></textarea>
              <input type="text" name="text" id="text" value="'.$ticketid.'" style="display: none">
              <input type="submit" class="user-button" value="Reply">
            </form>
          ';
        }
        if ($_SESSION['admin'] == 1) {
          if ($closed == 0) {
            echo "<p><a href='actions.php?action=close_ticket&id=$ticketid' style='text-decoration: none'>Close Ticket</a></p>";
          } else {
            echo "<br><p><a href='actions.php?action=open_ticket&id=$ticketid' style='text-decoration: none'>Open Ticket</a> <span style='color: white; font-style: italic; text-decoration: underline;'>You can't add comments until someone opens the ticket!</span></p>";
          }
        } else {
          if ($closed == 1) {
            echo "<br><p style='color: white; font-style: italic; text-decoration: underline;'>You can't add comments until someone opens the ticket!</p>";
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
            $comment_user_id = $row['userid'];
            $text = $row['text'];
            $created_at = $row['created_at'];

            $sql = "SELECT * FROM users WHERE id=$comment_user_id";
            $newResult = mysqli_query($link, $sql);
            $newRow = mysqli_fetch_assoc($newResult);
            $comment_username = $newRow['username'];
            $comment_admin = $newRow['admin'];
            $comment_file = $newRow['file'];

            echo "
              <div id='comment'>
                <div id='name'>
                <img src='$comment_file' alt='Profile Picture' id='profilePicture'>
                <span style='margin-top: 7px; margin-left: 10px; color: white;'><b><a href='profile.php?id=$comment_user_id'>$comment_username</a></b>
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
              <small style='float: right; color: white; font-style: italic; text-shadow: 1px 1px 1px white;'>$created_at</small>
            </div>";
          }
        } else {
          echo "<div id='comment'><p style='color: white;'>No Comments.</p></div>";
        } 
      echo "
      </div>
      <p id='data-sended'>The ticket was created at: $created_at</p>
      ";
      }
      ?>
      </div>
    </div>
    <script>
      setTimeout(() => {
        let p = document.getElementById("err_message");
        
        if (p.innerHTML != "") {
          p.innerHTML = "";
        }
      }, 5000);
    </script>
  </body>
</html>