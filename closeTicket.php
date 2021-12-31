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
    $queryString = "SELECT * FROM tickets WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
    $result = mysqli_query($link, $queryString);
    $row = mysqli_fetch_assoc($result);

    $ticketid = $row['id'];
    $created_at = $row['created_at'];
    $userid = $row['userid'];
    $closed = $row['closed'];

    $user_name = $_SESSION['username'];
    $user_id = $_SESSION['id'];

    if ($_SESSION['admin'] == 0 && $_SESSION['id'] != $userid) {
      header("location: home.php");
      return;
    }

    $sql = "SELECT * FROM users WHERE id=$userid";
    $newResult = mysqli_query($link, $sql);
    $newRow = mysqli_fetch_assoc($newResult);
    $username = $newRow['username'];

    $sql = "UPDATE tickets SET closed=1 WHERE id='$ticketid'";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO comments (text, username, userid, forTicket, file, admin) VALUES ('$user_name closed the ticket! (ticketid: $id)', 'admbot', '2', '$id', 'images/bot.svg', 1)";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$user_name</b> closed your ticket! (ticketid: $id)', '$userid')";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (text, userid) VALUES ('You closed <b>$username</b>\'s ticket. (ticketid: $id)', '$user_id')";
    mysqli_query($link, $sql);
    header("location: showTicket.php?id=$id");
  }
?>