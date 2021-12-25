<?php
    require "config.php";
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Search</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <script src="jquery/jquery.js"></script>
        <link rel="stylesheet" href="css/search.css?v=<?php echo time(); ?>">
        <script>
        $(document).ready(function(){
            $('.search-box input[type="text"]').on("keyup input", function(){
                var inputVal = $(this).val();
                var resultDropdown = $(this).siblings(".result");
                if(inputVal.length){
                    $.get("searchUser.php", {find: inputVal}).done(function(data){
                        resultDropdown.html(data);
                    });
                } else{
                    resultDropdown.empty();
                }
            });
        });
        </script>
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div style="margin: 20px;">
            <h1>Enter someone's username ...</h1>
            <div class="search-box">
                <input type="text" autocomplete="off" placeholder="Enter name" class="user-input" />
                <div class="result"></div>
            </div>
        </div>
    </body>
</html>