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
                <th><b>CHAT (last 100 messages)</b></th>
                <th><b>USERS (last 100 users)</b></th>
                <th><b>TICKETS (last 100 tickets)</b></th>
                <th><b>NOTIFICATIONS (last 100 notifications)</b></th>
                <th><b>COMMENTS (last 100 comments)</b></th>
            </tr>
            <tr>
            <th>
                    <?php
                        $sql = "SELECT * FROM chat";
                        $result = mysqli_query($link, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $message = $row['message'];
                                $created_at = $row['created_at'];
                                $user_id = $row['userid'];
                                $action = $row['action'];
                                $actiontext = $row['actiontext'];

                                if ($action == 0) {
                                    $sql1 = "SELECT * FROM users WHERE id=$user_id";
                                    $result1 = mysqli_query($link, $sql1);
                                    $row1 = mysqli_fetch_assoc($result1);
                                    $username = $row1['username'];
                                    
                                    echo "
                                        $username - $message - $created_at<br>
                                    ";
                                } else {
                                    echo "
                                        $actiontext<br>
                                    ";
                                }
                            }
                        } else {
                            echo "There are no messages.";
                        }
                    ?>
                </th>
                <th>
                    <?php
                        $sql = "SELECT * FROM users";
                        $result = mysqli_query($link, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $created_at = $row['created_at'];
                                $username = $row['username'];

                                echo "
                                    $username - $created_at<br>
                                ";
                            }
                        } else {
                            echo "There are no users.";
                        }
                    ?>
                </th>
                <th>
                    <?php
                        $sql = "SELECT * FROM tickets";
                        $result = mysqli_query($link, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $text = $row['text'];
                                $created_at = $row['created_at'];
                                $userid = $row['userid'];

                                $sql1 = "SELECT * FROM users WHERE id=$userid";
                                $result1 = mysqli_query($link, $sql1);
                                $row1 = mysqli_fetch_assoc($result1);
                                $username = $row1['username'];

                                echo "
                                    $username - $text - $created_at<br>
                                ";
                            }
                        } else {
                            echo "There are no tickets.";
                        }
                    ?>
                </th>
                <th>
                    <?php
                        $sql = "SELECT * FROM notifications";
                        $result = mysqli_query($link, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $text = $row['text'];
                                $created_at = $row['created_at'];
                                $userid = $row['userid'];

                                $sql1 = "SELECT * FROM users WHERE id=$userid";
                                $result1 = mysqli_query($link, $sql1);
                                $row1 = mysqli_fetch_assoc($result1);
                                $username = $row1['username'];

                                echo "
                                    $username - $text - $created_at<br>
                                ";
                            }
                        } else {
                            echo "There are no tickets.";
                        }
                    ?>
                </th>
                <th>
                    <?php
                        $sql = "SELECT * FROM comments";
                        $result = mysqli_query($link, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $text = $row['text'];
                                $created_at = $row['created_at'];
                                $userid = $row['userid'];
                                $ticketid = $row['forTicket'];

                                $sql1 = "SELECT * FROM users WHERE id=$userid";
                                $result1 = mysqli_query($link, $sql1);
                                $row1 = mysqli_fetch_assoc($result1);
                                $username = $row1['username'];

                                echo "
                                    $username  - ticket #$ticketid - $text - $created_at<br>
                                ";
                            }
                        } else {
                            echo "There are no comments.";
                        }
                    ?>
                </th>
            </tr>
        </table>
      </div>
    </div>
  </body>
</html>