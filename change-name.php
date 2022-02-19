<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config/config.php";
    $new_name_err = "";
    if (!empty($_GET['new_name_err'])) {
        $new_name_err = $_GET['new_name_err'];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Change Name</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px;">
            <h1>Change Name</h1>
            <form action="actions.php?action=change_name" method="post"> 
                <div>
                    <input type="text" name="new_name" class="user-input" placeholder="New Name">
                    <br>
                    <span class="user-error"><?php echo $new_name_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="submit" class="user-button" value="Change Name">
                </div>
            </form>
        </div>    
    </body>
</html>