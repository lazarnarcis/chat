<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config/config.php";
    $message_err = "";

    if (!empty($_GET['err_message'])) {
        $message_err = $_GET['err_message'];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Contact</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/contact.css?v=<?php echo time(); ?>">
        <script src="jquery/jquery.js"></script>
        <script>
            $(document).ready(function() {    
                loadadmins();
            });
            function loadadmins() {
                $("#show_admins").load("actions.php?action=search_admins");
                setTimeout(loadadmins, 2000);
            }
        </script>
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px;">
            <h1>Contact</h1>
            <p>You can send us an ticket if you need assistance in resolving any issues. You can read the <a href="terms.php">terms and conditions</a>.</p>
            <form action="actions.php?action=create_ticket" method="post">
                <div class="input">
                    <textarea type="text" name="message" class="user-input" placeholder="Message"></textarea>
                    <br>
                    <span class="user-error"><?php echo $message_err; ?></span>
                </div>
                <div>
                    <input type="submit" class="user-button" value="Send">
                </div>
            </form>
            <div class="online-admins">
                <h4>The members of the support team who can now help you solve the problem right now (admins):</h4>
                <div id="show_admins"></div>
            </div>
        </div>    
    </body>
</html>