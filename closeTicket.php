<?php
  session_start();
  require 'config.php';
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];
    $queryString = "SELECT id, created_at, texts, email, username, userid, subject, closed FROM tickets WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
    $query = $link->prepare($queryString);
    $query->execute();
    $query->store_result();
    $query->bind_result($ticketid, $created_at, $texts, $email, $username, $userid, $subject, $closed);
    $user_name = $_SESSION['username'];
    $user__id = $_SESSION['id'];
    if ($_SESSION['admin'] == 0 && $_SESSION['id'] != $userid) {
        header("location: home.php");
        return;
    }
  }
  while ($query->fetch()) { 
    $sql = "UPDATE tickets SET closed=1 WHERE id='$ticketid'";
    $query = mysqli_query($link,$sql);
    $sqls = "INSERT INTO comments (text, username, userid, forTicket, admin) VALUES ('$user_name closed the ticket!', 'admbot', '2', '$id', 1)";
    $querys = mysqli_query($link, $sqls);
    $sqls = "INSERT INTO notifications (texts, userid) VALUES ('(".$username.") $user_name closed your ticket!', '$userid')";
    $querys = mysqli_query($link,$sqls);
    $sqlsx = "INSERT INTO notifications (texts, userid) VALUES ('(".$user_name.") You closed $username\'s ticket.', '$user__id')";
    $querysx = mysqli_query($link,$sqlsx);
    header("location: showTicket.php?id=$id");
  }
?>