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
    $queryString = "SELECT id, created_at, texts, email, username, userid, closed FROM tickets WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
    $query = $link->prepare($queryString);
    $query->execute();
    $query->store_result();
    $query->bind_result($ticketid, $created_at, $texts, $email, $username, $userid, $closed);
    $user_name = $_SESSION['username'];
    $user__id = $_SESSION['id'];
    if ($_SESSION['admin'] == 0 && $_SESSION['id'] != $userid) {
      header("location: home.php");
      return;
    }
  }
  while ($query->fetch()):
    $sql = "UPDATE tickets SET closed=1 WHERE id='$ticketid'";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO comments (text, username, userid, forTicket, file, admin) VALUES ('$user_name closed the ticket!', 'admbot', '2', '$id', 'images/bot.svg', 1)";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$user_name</b> closed your ticket!', '$userid')";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO notifications (text, userid) VALUES ('You closed <b>$username</b>\'s ticket.', '$user__id')";
    mysqli_query($link, $sql);
    header("location: showTicket.php?id=$id");
  endwhile;
?>