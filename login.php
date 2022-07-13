<?php
    session_start();
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: home.php");
        exit;
    }
    $username = $password = $username_err = $password_err = $redirect_link = "";
    if (!empty($_GET['username'])) {
        $username = $_GET['username'];
    }
    if (!empty($_GET['password'])) {
        $password = $_GET['password'];
    }
    if (!empty($_GET['username_err'])) {
        $username_err = $_GET['username_err'];
    }
    if (!empty($_GET['password_err'])) {
        $password_err = $_GET['password_err'];
    }
    if (!empty($_GET['redirect_link'])) {
        $redirect_link = $_GET['redirect_link'];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Login</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general3.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <div class="wrapper">
            <form action="actions.php?action=login&redirect_link=<?php echo $redirect_link; ?>" method="post">
                <div id="menu">
                    <h1>Login</h1>
                    <div>
                        <input type="text" name="username" class="user-input" value="<?php echo $username; ?>" placeholder="Username or Email">
                        <br>
                        <span class="user-error"><?php echo $username_err; ?></span>
                    </div>    
                    <br>
                    <div>
                        <input type="password" name="password" class="user-input" value="<?php echo $password; ?>" placeholder="Password">
                        <br>
                        <span class="user-error"><?php echo $password_err; ?></span>
                    </div>
                    <br>
                    <p>Forgot your password?</p>
                    <a href="forgot-password.php">Reset your password</a>
                    <br>
                    <br>
                    <div>
                        <input type="submit" class="user-button" value="LOGIN">
                    </div>
                    <br>
                    <hr>
                    <br>
                    <p>You don't have an account? 
                    <br>
                    <a href="register.php">Create an account</a></p>
                </div>
            </form>
        </div>    
    </body>
</html>