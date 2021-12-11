<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config.php";
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_new_password = htmlspecialchars(trim($_POST["new_password"]));
        $set_confirm_password = htmlspecialchars(trim($_POST["confirm_password"]));

        if (empty($set_new_password)) {
            $new_password_err = "Please enter a password.";     
        } elseif (strlen($set_new_password) < 6) {
            $new_password_err = "Password must have atleast 6 characters.";
        } elseif (strlen($set_new_password) > 18) {
            $new_password_err = "Password too long (18 characters max).";
        } elseif (!preg_match("#[0-9]+#", $set_new_password)) {
            $new_password_err = "Password must include at least one number!";
        } elseif (!preg_match("#[a-zA-Z]+#", $set_new_password)) {
            $new_password_err = "Password must include at least one letter!";
        } else {
            $new_password = $set_new_password;
        }
        if (empty($set_confirm_password)) {
            $confirm_password_err = "Please confirm the password.";
        } else {
            $confirm_password = $set_confirm_password;
            if (empty($new_password_err) && ($new_password != $confirm_password)) {
                $confirm_password_err = "Password did not match.";
            }
        }
        if (empty($new_password_err) && empty($confirm_password_err)) {
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
                    if (mysqli_stmt_execute($stmt)) {
                    $sqls = "INSERT INTO notifications (texts, userid) VALUES ('(".$_SESSION['username'].") Your password has been changed!', '".$_SESSION['id']."')";
                    $querys = mysqli_query($link,$sqls);
                    header('location: profile.php?id='.$param_id.'');
                } else {
                    $new_password_err = "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
    <title>Reset Password</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/reset-password.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include_once("header.php"); ?>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="new_password" class="form-controls" placeholder="New Password" value="<?php echo $new_password; ?>"><br>
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <br>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-controls"><br>
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Change Password">
            </div>
        </form>
    </div>    
</body>
</html>