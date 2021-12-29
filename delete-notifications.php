<?php
  session_start();
  require 'config/config.php';
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else if($_SESSION['admin'] == 0){
    header("location: home.php");
  }else {
    $id = $_GET['id'];
    $queryString = "SELECT id, username FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
    $query = $link->prepare($queryString);
    $query->execute();
    $query->store_result();
    $query->bind_result($message_id, $username);
  }
  while ($query->fetch()):
    $sql = "DELETE FROM notifications WHERE userid='$message_id'";
    mysqli_query($link, $sql);
    $lastname = $_SESSION['username'];
    $userid = $_SESSION['id'];
    $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$lastname</b> deleted your notifications.', '$message_id')";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (text, userid) VALUES ('You deleted <b>$username</b>\'s notifications.', '$userid')";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname deleted $username\'s notifications.')";
    mysqli_query($link, $sql);
    header('location: profile.php?id='.$message_id.'');
  endwhile;
?>