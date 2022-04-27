<?php
    session_start();
    require "config/config.php";

    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: home.php");
        exit;
    }

    if (empty($_GET['email']) || empty($_GET['code'])) {
        header('location: login.php');
        exit;
    }
    
    $email = $_GET['email'];
    $code = $_GET['code'];

    $string = "SELECT * FROM forgot_password WHERE email='$email' AND code='$code'";
    $result = mysqli_query($link, $string);

    if (mysqli_num_rows($result) == 0) {
        header('location: login.php');
        exit;
    }
    mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Reset password</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general3.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <div class="wrapper">
            <form action="actions.php?action=reset_email_password" method="post">
                <div id="menu">
                    <h1>Reset your password</h1>
                    <input type="text" style="display: none" name='code' value="<?php echo $code; ?>">
                    <input type="text" style="display: none" name='email' value="<?php echo $email; ?>">
                    <div>
                        <input type="password" name="password" class="user-input" required placeholder="New password">
                        <br>
                    </div> 
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirm_password" required>
                        <label class="form-check-label" for="confirm_password">
                            Change my password.
                        </label>
                    </div>
                    <br>
                    <div>
                        <input type="submit" class="user-button" value="CHANGE PASSWORD">
                    </div>
                    <br>
                    <p>Remember password?</p>
                    <a href="login.php">Login now</a>
                </div>
            </form>
        </div>    
    </body>
</html>