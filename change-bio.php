<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config/config.php";
    
    $new_bio_err = "";
    if (!empty($_GET['new_bio_err'])) {
        $new_bio_err = $_GET['new_bio_err'];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Change Bio</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px;">
            <h1>Change Bio</h1>
            <form action="actions.php?action=change_bio" method="post"> 
                <div>
                    <input type="text" name="new_bio" class="user-input" placeholder="New Bio">
                    <br>
                    <span class="user-error"><?php echo $new_bio_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="submit" class="user-button" value="Change Bio">
                </div>
            </form>
        </div>    
    </body>
</html>