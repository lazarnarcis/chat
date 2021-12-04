<?php
  session_start();
  require 'config.php';
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else if ($_SESSION['founder'] == 0) {
    header("location: home.php");
  } else {
    $id = $_GET['id'];
    $queryString = "SELECT id, userid, message,name, founder FROM chat WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
    $query = $link->prepare($queryString);
    $query->execute();
    $query->store_result();
    $query->bind_result($message_id, $userid, $messS,$name, $founder);
    $user_name = $_SESSION['username'];
    $user__id = $_SESSION['id'];
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Delete Message</title>
</head>
<body>
  <div class="wrapper">
    <?php while ($query->fetch()) { ?>
        <?php
            $sql = "DELETE FROM chat WHERE id='$message_id'";
            $query = mysqli_query($link,$sql);
            $sqls = "INSERT INTO notifications (texts, userid) VALUES ('(".$name.") ".$user_name." deleted your message forever \'".$messS."\'.', '".$userid."')";
            $querys = mysqli_query($link,$sqls);
            $sqlsx = "INSERT INTO notifications (texts, userid) VALUES ('(".$user_name.") You deleted ".$name."''s message forever \'".$messS."\'.', '".$user__id."')";
            $querysx = mysqli_query($link,$sqlsx);
            header("location: home.php");
        ?>
    <?php } ?> 
  </div> 
</body>
</html>