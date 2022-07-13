<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php?redirect_link=reset-password.php");
        exit;
    }
    require "config/config.php";
    $confirm_password_err = "";
    if (!empty($_GET['err_message'])) {
        $confirm_password_err = $_GET['err_message'];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Reset Password</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px">
            <h1>Reset Password</h1>
            <form action="actions.php?action=reset_password" method="post"> 
                <div>
                    <input type="password" name="new_password" class="user-input" placeholder="New Password"><br>
                </div>
                <br>
                <div>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" class="user-input"><br>
                    <span class="user-error"><?php echo $confirm_password_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="submit" class="user-button" value="Change Password">
                </div>
            </form>
        </div>    
    </body>
</html>