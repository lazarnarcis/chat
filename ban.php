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
    $row = $result->fetch_assoc();

    $user_id = $row['id'];
    $username = $row['username'];
    $lastname = $_SESSION['username'];

    $sql = "UPDATE users SET banned=1 WHERE id='$user_id'";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$lastname</b> banned you.', '".$user_id."')";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (text, userid) VALUES ('You banned <b>".$username."</b>.', '".$_SESSION['id']."')";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username has been banned by $lastname.')";
    mysqli_query($link, $sql);
    header('location: profile.php?id='.$user_id.'');
  }
?>