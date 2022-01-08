<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config/config.php";
    $new_email = "";
    $new_email_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_email = htmlspecialchars($_POST["new_email"]);

        if (empty($set_email)) {
            $new_email_err = "Please enter a email.";     
        } else if (strlen($set_email) < 5) {
            $new_email_err = "Email too short!";
        } else if (strlen($set_email) > 50) {
            $new_email_err = "Email too long!";
        } else if (!filter_var($_POST["new_email"], FILTER_VALIDATE_EMAIL)) {
            $new_email_err = "Please enter a valid email!";
        } else {
            $new_email = $set_email;
        }
        if (empty($new_email_err)) {
            $user_id = $_SESSION["id"];
            $sql = "UPDATE users SET email='$new_email',verified=0 WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Your email has been changed from <b>".$_SESSION['email']."</b> to <b>".$new_email."</b>.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $_SESSION['email'] = $new_email;
            header('location: profile.php?id='.$user_id.'');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Change Email</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px">
            <h1>Change Email</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div>
                    <input type="text" name="new_email" class="user-input" value="<?php echo $new_email; ?>" placeholder="New Email">
                    <br>
                    <span class="user-error"><?php echo $new_email_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="submit" class="user-button" value="Change Email">
                </div>
            </form>
        </div>    
    </body>
</html>