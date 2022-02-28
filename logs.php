<?php
    session_start();
    require "config/config.php";
    
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
?> 
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Logs</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/logs.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div style="margin: 20px;">
        <h1>Logs</h1>
        <div>
        <table>
            <tr>
                <th></th>
            </tr>
            <tr>
                <th></th>
            </tr>
        </table>
      </div>
    </div>
  </body>
</html>