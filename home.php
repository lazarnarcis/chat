<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      header("location: login.php");
      exit;
  }
  require "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
    <title>Home</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <script src="jquery.js"></script>
    <script>
      $('textarea').keyup(function (event) {
          if (event.keyCode == 13) {
              var content = this.value;  
              var caret = getCaret(this);          
              if (event.shiftKey) {
                this.value = content.substring(0, caret - 1) + "\n" + content.substring(caret, content.length);
                event.stopPropagation();
              } else {
                this.value = content.substring(0, caret - 1) + content.substring(caret, content.length);
                $('form').submit();
              }
          }
      });
      function showOptionsForMessage(val) {
        let msj = document.getElementById("showTimes" + val);
        let other = document.getElementById("idOfM" + val);
        
        if (msj.style.display === "none") {
          msj.style = "display: inline;";
          other.style = "background-color: #565171;";
        } else {
          msj.style = "display: none;";
          other.style = "background-color: ##3a365b;";
        }
      }
      var start = 0;
      var path = location.href.substring(0, location.href.lastIndexOf("/")+1);
      var url = path + '/chat.php';
      $(document).ready(function(){
        load();
        $("form").submit(function(e){
          $.post(url, {
            message: $("#message").val()
          });
          $("#message").val('')
          return false;
        });
      });
      function load() {
        $.get(url + '?start=' + start, function(result) {
          if(result.items) {
            result.items.forEach(item => {
              start = item.id;
              $("#messages").append(renderMessage(item));
            });
            $("#messages").animate({scrollTop: $("#messages")[0].scrollHeight}, 0);
          }
          load();
        });
      }
      function renderMessage(item) {
        let admin;
        if (item.founder == 1) {
          admin = " (F)";
        } else if (item.admin == 1) {
          admin = " (A)";
        } else {
          admin = "";
        }
        let x;
        <?php 
          $sql = "SELECT id, logged FROM users ORDER BY id=1";
          $result = mysqli_query($link,$sql);
          $row = mysqli_fetch_array($result, MYSQLI_NUM);
          mysqli_free_result($result);
        ?>
        if(item.action == 1) {
          x = `<div id="all-message">
              <div class="actiontext">
                <span>${item.actiontext} ${item.created_at}</span>
              </div>
            </div>`;
        } else if (<?php echo $_SESSION['id']; ?> == item.userid) {
          x = `<div id="all-message">
                <div class="date">
                  <div id="nameUser">
                    <a id="user-profile-link" href="profile.php?id=${item.userid}">${item.name}</a>
                    <span id="admin-text">${admin}</span>
                  </div>
                  <div><span class="time" id="showTimes${item.id}"><small>${item.created_at}</small></span></div>
                </div>
                <div class="messageO">
                  <div>
                    <img id="profile-message-picture" src="images/${item.file}" />
                    `;
                    if (<?php echo $row[1] ; ?>== 1) {
                      x += `<span class="active-user" style="background-color: #0fbf15;"></span>`;
                    } else {
                      x += `<span class="active-user" style="background-color: grey;"></span>`;
                    }
                    x += `
                  </div>
                  <div class="msj" id="idOfM${item.id}" onclick="showOptionsForMessage(${item.id})">
                    <span>${item.message}</span>
                  </div>
                </div>
              </div>`;
        } else {
          x = `<div id="all-message">
                <div class="date">
                  <div id="nameUser">
                    <a id="user-profile-link" href="profile.php?id=${item.userid}">${item.name}</a>
                    <span id="admin-text">${admin}</span>
                  </div>
                  <div><span class="time" id="showTimes${item.id}"><small>${item.created_at}</small></span></div>
                </div>
                <div class="messageO">
                  <div>
                    <img id="profile-message-picture" src="images/${item.file}" />
                    `;
                    if (1== 1) {
                      x += `<span class="active-user" style="background-color: #0fbf15;"></span>`;
                    } else {
                      x += `<span class="active-user" style="background-color: grey;"></span>`;
                    }
                    x += `
                  </div>
                  <div class="msj" id="idOfM${item.id}" style="background-color: #402e9f;" onclick="showOptionsForMessage(${item.id})">
                    <span>${item.message}</span>
                  </div>
                </div>
              </div>`;
        }
        return x;
      }
    </script>
    <style type="text/css">
      body {
        margin: 0;
        overflow: hidden;
        font-size:18px;
        background-color: #1b173a;
      }
      .uniquer-class {
        display: none;
      }
      #delete-text-x {
        color: white;
      }
      .actiontext {
        width: 100%;
        text-align: center;
        color: #74718e;
      }
      #messages {
        height: 70vh;
        overflow-x: hidden;
        padding-left: 10px;
        padding-right: 10px;
        font-size: 15px;
        font-family: "Lato",sans-serif;
      }
      #admin-text {
        color: #d16352;
        font-weight: 550;
      }
      #user-profile-link {
        text-decoration: none;
        color: #7b7798;
      }
      #user-profile-link:hover {
        text-decoration: underline;
      }
      .messageO {
        display: flex;
      }
      a {
        color: white;
        text-decoration: underline;
      }
      .active-user {
        height: 10px;
        width: 10px;
        border: 2px solid #1b173a;
        border-radius: 50%;
        display: inline-block;
        margin-left: -15px;
      }
      a:hover {
        color: #aea7d8;
      }
      #profile-message-picture {
        height: 27.5px;
        width: 27.5px;
        user-select: none;
        border-radius: 25px;
        float: left;
        margin-left: 5px;
        margin-right: 5px;
      }
      .time {
        color: #7a7796;
        display: none;
      }
      #message {
        padding: 7.5px;
        color: #8f8da5;
        border: 0;
        height: 25px;
        border-radius: 20px;
        resize: none;
        width:  97.5%;
        overflow: hidden;
        border: none;
        float:left;
        outline:none;
        background-color: #44415e;
        transition: .3s;
        margin: 5px;
        margin-left: 10px;
        margin-right: 10px;
      }
      .date {
        display: flex;
      }
      #nameUser {
        margin-left: 55px;
        flex: 1;
        margin-bottom: 3.5px;
      }
      .date::after {
        flex: 1;
        content: '';
      }
      #all-message {
        margin-top: 10px;
        margin-bottom: 10px;
      }
      .msj{
        background-color: #3a365b;
        color: #e8e6fe;
        padding: 10px;
        border-radius:20px;
        margin-bottom: 8px;
        width: fit-content;
        transition: .2s;        
        margin-left: 5px;
      }
      .content-messages {
        padding: 5px 10px;
        border-radius:5px;
        margin-bottom: 8px;
        width: fit-content;
      }
      .msj p{
        margin: 0;
        font-weight: bold;
      }
      #hidden-input {
        display: none;
      }
      #text-send {
        margin: 0;
        margin-left: 10px;
        margin-top: -5px;
        font-size: 14px;
        position: relative;
      }
      form div {
        display: flex;
      }
      input[type=image] {
        border: 0;
        width: 30px;
        height: 30px;
        filter: invert(48%) sepia(40%) saturate(1427%) hue-rotate(185deg) brightness(103%) contrast(92%);
        margin-top: 10px;
        margin-right: 10px;
      }
    </style>
</head>
<body>
  <?php include_once("header.php"); ?>
   <div id="messages">
   </div>
   <form>
    <div>
      <input type="text" name="message" id="message" placeholder="Type a message..." autocomplete="off" autofocus />
      <input type="image" name="submit" src="logos/send.svg" alt="Submit" />
    </div>
    </form>
</body>
</html>