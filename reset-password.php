<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config/config.php";
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION["id"];
        $set_new_password = htmlspecialchars($_POST["new_password"]);
        $set_confirm_password = htmlspecialchars($_POST["confirm_password"]);

        if (empty($set_new_password)) {
            $new_password_err = "Please enter a password.";     
        } else if (strlen($set_new_password) < 6) {
            $new_password_err = "Password must have atleast 6 characters.";
        } else if (strlen($set_new_password) > 18) {
            $new_password_err = "Password too long (18 characters max).";
        } else if (!preg_match("#[0-9]+#", $set_new_password)) {
            $new_password_err = "Password must include at least one number!";
        } else if (!preg_match("#[a-zA-Z]+#", $set_new_password)) {
            $new_password_err = "Password must include at least one letter!";
        } else {
            $new_password = $set_new_password;
        }
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        if (empty($set_confirm_password)) {
            $confirm_password_err = "Please confirm the password.";
        } else {
            $confirm_password = $set_confirm_password;
            if (empty($new_password_err) && ($new_password != $confirm_password)) {
                $confirm_password_err = "Password did not match.";
            }
        }
        if (empty($new_password_err) && empty($confirm_password_err)) {
            $sql = "UPDATE users SET password='$hashed_password' WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Your password has been changed!', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
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
        <title>Reset Password</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px">
            <h1>Reset Password</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div>
                    <input type="password" name="new_password" class="user-input" placeholder="New Password" value="<?php echo $new_password; ?>"><br>
                    <span class="user-error"><?php echo $new_password_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" class="user-input"><br>
                    <span class="user-error"><?php echo $confirm_password_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="submit" class="user-button" value="Change Password">
                </div>
            </form>
        </div>    
    </body>
</html>