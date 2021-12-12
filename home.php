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
    <script src="jquery.js"></script>
    <link rel="stylesheet" href="css/home.css?v=<?php echo time(); ?>">
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
          other.style = "background-color: #1a1a1a;";
        } else {
          msj.style = "display: none;";
          other.style = "background-color: #616161;";
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
                    <span class="active-user" style="background-color: #0fbf15;"></span>
                  </div>
                  <div><span class="time" id="showTimes${item.id}"><small>${item.created_at}</small></span></div>
                </div>
                <div class="messageO">
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
                    <span class="active-user" style="background-color: #0fbf15;"></span>
                  </div>
                  <div><span class="time" id="showTimes${item.id}"><small>${item.created_at}</small></span></div>
                </div>
                <div class="messageO">
                  <div class="msj" id="idOfM${item.id}" style="background-color: #1f1f1f;" onclick="showOptionsForMessage(${item.id})">
                    <span>${item.message}</span>
                  </div>
                </div>
              </div>`;
        }
        return x;
      }
    </script>
</body>
</html>