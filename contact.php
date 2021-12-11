<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config.php";
    $subject = $message = "";
    $subject_err = $message_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_subject = htmlspecialchars(trim($_POST['subject']));
        $set_message = htmlspecialchars(trim($_POST['message']));

        if (empty($set_subject)) {
            $subject_err = "Please enter the subject.";     
        } else {
            $subject = $set_subject;
        }
        if (empty($set_message)) {
            $message_err = "Please enter the message.";     
        } else {
            $message = $set_message;
        }
        if (empty($subject_err) && empty($message_err)) {
            $sqls = "INSERT INTO tickets (texts, userid, email, username, subject) VALUES ('".$message."', '".$_SESSION['id']."', '".$_SESSION['email']."', '".$_SESSION['username']."', '".$subject."')";
            $querys = mysqli_query($link,$sqls);
            $selectquery="SELECT id, username FROM tickets ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($link, $selectquery);
            $row = $result->fetch_assoc();
            $ticketid = $row['id'];
            $ticketusername = $row['username'];
            $sqlo = "INSERT INTO comments (text, username, userid, forTicket, file, admin) VALUES ('Hello, $ticketusername!!\nI am an admin bot and please tell us in detail what your problem is! An admin will help you as soon as possible.\nIf you do not respond within 24 hours, this ticket will be closed!\n\nAdmBot, have a nice day!', 'AdmBot', '0', '$ticketid', 'images/bot.svg', 1)";
            $queryo = mysqli_query($link,$sqlo);
            $sql = "INSERT INTO notifications (texts, userid) VALUES ('(".$_SESSION['username'].") Ticket has been created! You will receive an answer soon!', '".$_SESSION['id']."')";
            $query = mysqli_query($link,$sql);
            header("location: showTicket.php?id=$ticketid");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
    <title>Contact</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/contact.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include_once("header.php"); ?>
    <div class="wrapper" style="margin:20px;">
        <h2>Contact</h2>
        <p>You can send us an ticket if you need assistance in resolving any issues. You can read the <a href="terms.html">terms and conditions</a>.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($subject_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="subject" class="form-controls" value="<?php echo $subject; ?>" placeholder="Subject">
                <br>
                <span class="help-block"><?php echo $subject_err; ?></span>
            </div>
            <br>
            <div class="form-group <?php echo (!empty($message_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="message" class="form-controls" value="<?php echo $message; ?>" placeholder="Message">
                <br>
                <span class="help-block"><?php echo $message_err; ?></span>
            </div>
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Send">
            </div>
        </form>
    </div>    
</body>
</html>