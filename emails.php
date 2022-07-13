<?php
  session_start();
  require "config/config.php";
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php?redirect_link=emails.php");
    exit;
  }
  $err_message = "";
  if (!empty($_GET['err_message'])) {
    $err_message = $_GET['err_message'];
  }
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Emails</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/tickets.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php 
      require_once("header.php");
    ?>
    <div style="margin:20px;">
        <?php
            $number_emails = mysqli_query($link, "SELECT COUNT(*) FROM emails WHERE sended=0");
            $total_emails = mysqli_fetch_row($number_emails)[0];
        ?>
        <h2 id="err_message"><?php echo $err_message; ?></h2>
        <h1>Emails to send (#<?php echo $total_emails; ?> unsent)</h1>
        <?php
            if ($_SESSION['founder'] == 0) {
                echo '<span class="user-error">You do not have the role of founder!</span>';
                return;
            }
        ?>
        <div class="main-div">
        <?php
            $sql = "SELECT * FROM `emails` ORDER BY sended DESC";
            $query = mysqli_query($link, $sql);
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $message = $row['message'];
                    $name = $row['name'];
                    $created_at = $row['created_at'];
                    $email_id = $row['id'];

                    echo "
                    <a class='secondary-div' href='actions.php?action=accept_mail&id=".$email_id."'>
                        <div>
                        <h3 id='ticket_id'>Email (#".$email_id.")</h3>
                        </div>
                        <div>
                        <p>Username: <b>".$name."</b></p>
                        <p><b>Message:</b> $message</p>
                        <p>The email was sent to: <b>".$created_at."</b></p>
                        </div>
                    ";
                    if ($row['sended'] == 1) {
                    echo "
                        <div id='opened' style='background-color: #874c57;'>
                        <span>Delivered</span>
                        </div>
                    ";
                    } else if ($row['sended'] == 0) {
                    echo "
                        <div id='opened'>
                        <span>Send id!</span>
                        </div>
                    ";
                    }
                    echo "</a>";
                }
            } else {
                echo "<div class='secondary-div'><p>No Emails.</p></div>";
            } 
        ?>
    </div>
    </div>
    <script>
        setTimeout(() => {
            let p = document.getElementById("err_message");
            if (p.innerHTML != "") {
                p.innerHTML = "";
            }
        }, 7500);
    </script>
  </body>
</html>