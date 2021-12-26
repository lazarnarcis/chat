<?php
  session_start();
  require 'config/config.php';
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];
    if ($_SESSION['id'] != $id) {
        header("location: home.php");
        return;
    }
    $queryString = "SELECT id, username FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
    $query = $link->prepare($queryString);
    $query->execute();
    $query->store_result();
    $query->bind_result($message_id, $username);
  }
  while ($query->fetch()):
    $sql = "UPDATE users SET verified=1 WHERE id='$message_id'";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (texts,userid) VALUES ('(".$username.") Your account has been verified.', '".$message_id."')";
    mysqli_query($link, $sql);
    $lastname = $_SESSION['username'];
    $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username just verified his account!')";
    mysqli_query($link, $sql);
    header('location: profile.php?id='.$message_id.'');
  endwhile;
?>