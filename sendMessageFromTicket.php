<?php
    session_start();
    require 'config.php';
    $message = $_POST['message'];
    $text = $_POST['text'];
    $user_name = $_SESSION['username'];
    $user_id = $_SESSION['id'];
    $message = str_replace('<br />', PHP_EOL, $message);
    $message = str_replace("'", "\'", $message);
    $message = strip_tags($message);
    $message = displayTextWithLinks($message);
    $admin = $_SESSION['admin'];
    $file = $_SESSION['file'];
    if (empty($message)) {
        header("location: showTicket.php?id=$text");
        return;
    } else if (!empty($message)) {
        $sqls = "INSERT INTO comments (text, username, userid, forTicket, file, admin) VALUES ('$message', '$user_name', '$user_id', '$text', '$file', '$admin')";
        $querys = mysqli_query($link, $sqls);
        header("location: showTicket.php?id=$text");
    } 
    function displayTextWithLinks($s) {
        return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank" id="unique-button-id">$1</a>', $s);
    }
?>