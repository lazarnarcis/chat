<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  }
  require "config/config.php";

  $err_message = "";
  if (!empty($_GET['err_message'])) {
    $err_message = $_GET['err_message'];
  }
  $send_message = $_SESSION['send_message'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
      <title>Home</title>
      <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
      <script src="jquery/jquery.js"></script>
      <link rel="stylesheet" href="css/home.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div class="home-page">
      <h2 id="err_message"><?php echo $err_message; ?></h2>
      <h1 id="general-chat">General Chat</h1>
      <div id="messages"></div>
      <form>
        <div id="inputs">
          <textarea type="text" name="message" id="message" placeholder="Type a message..." autocomplete="off" autofocus></textarea>
          <?php
            if ($_SESSION['send_message'] == 1) {
              ?>
                <p id="press_to_send">Press Enter to Send</p>
              <?php
            } else if ($_SESSION['send_message'] == 2) {
              ?>
                <input type="image" name="submit" src="logos/send.svg" alt="Submit" />
              <?php
            }
          ?>
        </div>
      </form>
    </div>
    <script>
      function showOptionsForMessage(val) {
        let msj = document.getElementById("showTimes" + val);
        msj.style = "display: inline;";
      }
      function unshowOptionsForMessage(val) {
        let msj = document.getElementById("showTimes" + val);
        msj.style = "display: none;";
      }
      var start = 0;
      var path = location.href.substring(0, location.href.lastIndexOf("/")+1);
      var send_message = path + '/actions.php?action=send_message';
      var load_chat = path + '/actions.php?action=load_chat';
      
      $(document).ready(function() {
        $('textarea').keyup(function (e) {
          if (e.key == "Enter" && <?php echo $send_message; ?> == 1) {
            $("form").submit();
          }
        });
        load();
        $("form").submit(function(e) {
          $.post(send_message, {
            message: $("#message").val()
          });
          $("#message").val(null);
          return false;
        });
      });
      function load() {
        $.get(load_chat + '&start=' + start, function(result) {
          if (result.items) {
            result.items.forEach(item => {
              start = item.id;
              $.post("actions.php?action=show_loaded_chat&message_id=" + item.id, $(this).serialize()).done(function(data) {
                $("#messages").append(data);
                $("#messages").animate({scrollTop: $("#messages")[0].scrollHeight}, 0);
              });
            });
          }
          load();
        });
      }
      setTimeout(() => {
        let p = document.getElementById("err_message");
        
        if (p.innerHTML != "") {
          p.innerHTML = "";
        }
      }, 5000);
    </script>
  </body>
</html>