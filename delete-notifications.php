<?php
  session_start();
  require 'config/config.php';

  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else if ($_SESSION['admin'] == 0) {
    header("location: home.php");
  } else {
    $id = $_GET['id'];

    $queryString = "SELECT * FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
    $result = mysqli_query($link, $queryString);
    $row = mysqli_fetch_assoc($result);

    $user_id = $row['id'];
    $username = $row['username'];
    $lastname = $_SESSION['username'];
    $userid = $_SESSION['id'];

    $sql = "DELETE FROM notifications WHERE userid='$user_id'";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$lastname</b> deleted your notifications.', '$user_id')";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (text, userid) VALUES ('You deleted <b>$username</b>\'s notifications.', '$userid')";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname deleted $username\'s notifications.')";
    mysqli_query($link, $sql);
    header('location: profile.php?id='.$user_id.'');
  }
?>