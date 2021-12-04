<?php
  session_start();
  require "config.php";
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      header("location: login.php");
      exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];
  }
  $user_name = $_SESSION['username'];
  $user_id = $_SESSION['id'];
  $queryString = "SELECT id,created_at, texts, email, username, userid, subject, closed FROM tickets WHERE id='$id' ORDER BY username DESC LIMIT 1"; 
  $query = $link->prepare($queryString);
  $query->execute();
  $query->store_result();
  $query->bind_result($ticketid, $created_at, $texts, $email, $username, $userid, $subject, $closed);
?> 
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
	<title>Tickets</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <style type="text/css">
    .tooltipssa {
      display: flex;
      flex-direction: column-reverse;
    }
    a:hover {
      color: blue;
    }
    .post-by-user {
      border: 1px solid #c8cfdb;
      border-radius: 5px;
      padding: 20px;
      background-color: white;
      box-shadow: inset 0px 0px 10px lightgrey;
    }
    body {
      font-size: 18px;
    }
    #data-sended {
      color: #565d69;
    }
    a {
      text-decoration: underline;
      color: #25abb3;
    }
    #name {
      display: flex;
      align-items: left;
      justify-content:
    }
    p {
      margin: 0;
    }
    #message {
      margin-top: 10px;
      padding: 5px;
      background: #9ca5b5;
      color: white;
      border-radius: 10px;
    }
    a:hover {
      text-decoration: none;
    }
    #opened {
      float: right;
      border-radius: 10px;
      background-color: #05822f;
      padding: 7.5px;
      color: white;
    }
    #comment {
      border: 0px solid grey;
      border-radius: 20px;
      padding: 20px;
      box-shadow: inset 0px 0px 15px lightgrey;
      vertical-align: middle;
      margin-bottom: 20px;
      padding-bottom: 20px;
    }
    .btn-primary {
      margin: 0;
      margin-top:10px;
    }
    #form {
      margin-top: 15px;
      margin-bottom: 15px;
      align-items: center;
      justify-content: center;
    }
    #profilePicture {
      height: 35px;
      width: 35px;
      border-radius: 50%;
    }
    #unique-button-id {
      color: white;
    }
    #unique-button-id:hover {
      color: black;
      text-decoration: underline;
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
    <div class="tooltipssa">
    <?php while ($query->fetch()) {
      if ($_SESSION['admin'] == 0 && $_SESSION['id'] != $userid) {
        echo '<span class="help-block">You don\'t have access!</span>';
        return;
      } else {
        ?>
      <div class="post-by-user">
        <?php
          if ($closed == 0) {
            ?>
              <div id="opened">
                <span>Opened</span>
              </div>
            <?php
          } else {
            ?>
              <div id="opened" style="background-color: #611b0f;">
                <span>Closed</span>
              </div>
            <?php
          }
        ?>
        <h1>Ticket #<?php echo $ticketid ?></h1>
        <span style="color:#3f5475">Subject: <?php echo $subject ?></span><br>
        <span style="color:#3f5475">Message: <?php echo $texts ?></span><br>
        <span style="color:#3f5475">Email: <?php echo $email ?></span><br>
        <span style="color:#3f5475">Username: <a href="profile.php?id=<?php echo $userid ?>"><?php echo $username ?></a></span><br>
        <span style="color:#3f5475">User ID: <?php echo $userid; ?></span><br>
        <form action="sendMessageFromTicket.php" method="post" id="form">
          <textarea type="text" name="message" class="form-controls" placeholder="Reply as <?php echo $_SESSION["username"]; ?>..." autofocus <?php
            if ($closed == 1) {
              echo "disabled";
            } else {
              echo "";
            }
          ?>></textarea>
          <input type="text" name="text" id="text" value="<?php echo $ticketid; ?>" style="display: none"><br/>
          <input type="submit" class="btn btn-primary" value="Reply" <?php
            if ($closed == 1) {
              echo "disabled";
            } else {
              echo "";
            }
          ?>>
        </form>
        <?php
          if ($_SESSION['admin'] == 1) {
            if ($closed == 0) {
              ?>
                <p><a href="closeTicket.php?id=<?php echo $ticketid; ?>">Close Ticket</a></p>
              <?php
            } else {
              ?>
                <p><a href="openTicket.php?id=<?php echo $ticketid; ?>">Open Ticket</a> (You can't add comments until someone opens the ticket!)</p>
              <?php
            }
          } else {
            if ($closed == 1) {
              ?>
                <p>You can't add comments until someone opens the ticket!</p>
              <?php
            }
          }
        ?>
        <h3>Comments:</h3>
        <div style="display: flex; flex-direction: column-reverse;">
          <?php
      $sql = "SELECT * FROM `comments` WHERE forTicket=$id";
      $querys = mysqli_query($link,$sql);
      if (mysqli_num_rows($querys) > 0) {
        while ($row= mysqli_fetch_assoc($querys)) {
    ?>
      <div id="comment">
          <div id="name">
            <img src="images/<?php echo $row['file']; ?>" alt="Profile Picture" srcset="" id="profilePicture">
            <span style="margin-top: 7px; margin-left: 10px;"><b><a href="profile.php?id=<?php echo $row['userid']; ?>"><?php echo $row['username']; ?></a></b>
          <?php
            if ($row['admin'] == 1) {
              echo "*technical support*";
            }
          ?>
          </span>
          </div>
          <div id="message">
            <span style="white-space: pre-wrap;"><?php echo $row['text']; ?></span>
          </div>
          <small style="float: right;"><?php echo $row['created_at']; ?></small>
        </div>
    <?php
      }
      } else {
    ?>
    <div id="comment"><p>No Comments.</p></div>
    <?php
      } 
    ?>
        </div>
    <p id="data-sended">The ticket was created at: <?php echo $created_at ?></p>
    <?php
     }
    }
    ?>
      </div>
  </div>
</body>
</html>