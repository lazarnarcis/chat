<?php
    session_start();
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: home.php");
        exit;
    }
    $password = "";
    if (!empty($_GET['password'])) {
        $password = $_GET['password'];
    }
    $password_err = "";
    if (!empty($_GET['password_err'])) {
        $password_err = $_GET['password_err'];
    }
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
                    <div>
                        <input type="password" name="password" class="user-input" value="<?php echo $password; ?>" required placeholder="New password">
                        <br>
                        <span class="user-error"><?php echo $password_err; ?></span>
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