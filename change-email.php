<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config/config.php";

    $new_email_err = "";
    if (!empty($_GET['new_email_err'])) {
        $new_email_err = $_GET['new_email_err'];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Change Email</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px">
            <h1>Change Email</h1>
            <form action="actions.php?action=change_email" method="post"> 
                <div>
                    <input type="text" name="new_email" class="user-input" placeholder="New Email">
                    <br>
                    <span class="user-error"><?php echo $new_email_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="submit" class="user-button" value="Change Email">
                </div>
            </form>
        </div>    
    </body>
</html>