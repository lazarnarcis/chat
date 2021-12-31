<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config/config.php";
    $message = "";
    $message_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_message = htmlspecialchars($_POST['message']);

        if (empty($set_message)) {
            $message_err = "Please enter the message.";     
        } else {
            $message = $set_message;
        }

        if (strlen($message) > 1000) {
            $message_err = "You can't have more than 1000 letters!";
        }

        $ticket_user_id = $_SESSION['id'];
        $count_the_tickets = mysqli_query($link, "SELECT COUNT(*) FROM `tickets` WHERE userid=$ticket_user_id AND closed=0");
        $number_of_tickets = mysqli_fetch_row($count_the_tickets)[0];
        if ($number_of_tickets >= 10) {
            $message_err = "You cannot have more than 10 tickets open!";
        }

        if (empty($message_err)) {
            $sql = "INSERT INTO tickets (text, userid) VALUES ('".$message."', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $selectquery = "SELECT * FROM tickets ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($link, $selectquery);
            $row = mysqli_fetch_assoc($result);
            $ticketid = $row['id'];
            $ticketuserid = $row['userid'];
            $sql = "SELECT * FROM users WHERE id=$ticketuserid";
            $newResult = mysqli_query($link, $sql);
            $newRow = mysqli_fetch_assoc($newResult);
            $ticketusername = $newRow['username'];
            $sql = "INSERT INTO comments (text, userid, forTicket) VALUES ('Hello, $ticketusername!!\nI am an admin bot and please tell us in detail what your problem is! An admin will help you as soon as possible.\nIf you do not respond within 24 hours, this ticket will be closed!\n\nAdmBot, have a nice day!', '2', '$ticketid')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Ticket (#$ticketid) has been created! You will receive an answer soon!', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            header("location: showTicket.php?id=$ticketid");
        }
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
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px;">
            <h1>Contact</h1>
            <p>You can send us an ticket if you need assistance in resolving any issues. You can read the <a href="terms.php">terms and conditions</a>.</p>
            <br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div>
                    <textarea type="text" name="message" class="user-input" value="<?php echo $message; ?>" placeholder="Message"></textarea>
                    <br>
                    <span class="user-error"><?php echo $message_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="submit" class="user-button" value="Send">
                </div>
            </form>
        </div>    
    </body>
</html>