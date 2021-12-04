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
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
        <title>Search</title>
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <style>
            body {
                font-size: 18px;
            }
            #linkToProfile {
                margin-top: 5px;
                margin-left: 10px;
            }
            #noFound {
                padding: 10px;
                background-color: white;
                color: black;
                margin: 0;
                border-top: 1px solid lightgrey;
                border-bottom: 1px solid lightgrey;
                transition: background-color .3s;
                margin-top: -1px;
                display: flex;
                cursor: pointer;
            }
            input {
                margin-bottom: 20px;
            }
            #noFound:hover {
                background-color: #cfcfcf;
            }
            a:hover {
                text-decoration: none;
            }
        </style>
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
        <?php include_once("header.php"); ?>
        <div style="margin: 20px;">
            <div class="search-box">
                <input type="text" autocomplete="off" placeholder="Enter name" class="form-controls" />
                <div class="result"></div>
            </div>
        </div>
    </body>
</html>