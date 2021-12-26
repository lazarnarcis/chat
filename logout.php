<?php
    session_start();
    require "config.php";
    $id = $_SESSION['id'];
    $username = $_SESSION['username'];
    $sql = "UPDATE users SET logged=0 WHERE id=".$id."";
    mysqli_query($link, $sql);
    $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username just disconnected!')";
    mysqli_query($link, $sql);
    session_reset();
    session_destroy();
    header("location: home.php");
    exit;
?>