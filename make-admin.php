<?php
  session_start();
  require 'config/config.php';
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else if($_SESSION['founder'] == 0){
    header("location: home.php");
  } else {
    $id = $_GET['id'];
    $queryString = "SELECT id, username FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
    $query = $link->prepare($queryString);
    $query->execute();
    $query->store_result();
    $query->bind_result($message_id, $username);
  }
  while ($query->fetch()):
    $sql = "UPDATE users SET admin=1 WHERE id='$message_id'";
    $query = mysqli_query($link,$sql);
    $sqls = "INSERT INTO notifications (texts,userid) VALUES ('(".$username.") ".$_SESSION['username']." made you admin.', '".$message_id."')";
    $querys = mysqli_query($link,$sqls);
    $sqls = "INSERT INTO notifications (texts,userid) VALUES ('(".$_SESSION['username'].") You made ".$username." admin.', '".$_SESSION['id']."')";
    $querys = mysqli_query($link,$sqls);
    $lastname = $_SESSION['username'];
    $sqlx = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname set $username as administrator.')";
    $queryx = mysqli_query($link,$sqlx);
    header('location: profile.php?id='.$message_id.'');
  endwhile; 
?> 