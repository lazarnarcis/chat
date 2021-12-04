<?php
    session_start();
    require "config.php";
    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $sql = "UPDATE users SET logged=0 WHERE id=".$id."";
    $query = mysqli_query($link, $sql);
    $sqlx = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username just disconnected!')";
    $queryx = mysqli_query($link, $sqlx);
    session_reset();
    session_destroy();
    header("location: home.php");
    exit;
?>