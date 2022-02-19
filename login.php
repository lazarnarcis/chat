<?php
    session_start();
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: home.php");
        exit;
    }
    require "config/config.php";
    $username = $password = "";
    $username_err = $password_err = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $serverip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $serverip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $serverip = $_SERVER['REMOTE_ADDR'];
        }

        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        
        if (empty($username)) {
            $username_err = "Please enter username/email.";
        } else if (empty($password)) {
            $password_err = "Please enter your password.";
        } else {
            $sql = "SELECT * FROM users WHERE username='$username' OR email='$username'";
            $result = mysqli_query($link, $sql);
            if (mysqli_num_rows($result) == 0) {
                $username_err = "No account found with that username/email.";
            } else {
                $row = mysqli_fetch_assoc($result);
                $hashed_password = $row['password'];

                if (!password_verify($password, $hashed_password)) {
                    $password_err = "The password you entered was not valid.";
                }
            }
        }

        if (empty($username_err) && empty($password_err)) {
            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_assoc($result);

            $id = $row['id'];
            $admin = $row['admin'];
            $created_at = $row['created_at'];
            $bio = $row['bio'];
            $file = $row['file'];
            $email = $row['email'];
            $founder = $row['founder'];
            $banned = $row['banned'];
            $logged = $row['logged'];
            $ip = $row['ip'];
            $last_ip = $row['last_ip'];
            $verified = $row['verified'];

            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $username;  
            $_SESSION["admin"] = $admin;  
            $_SESSION["created_at"] = $created_at;
            $_SESSION["bio"] = $bio;  
            $_SESSION["file"] = $file;  
            $_SESSION["email"] = $email;  
            $_SESSION["founder"] = $founder;  
            $_SESSION["banned"] = $banned;  
            $_SESSION["logged"] = $logged;
            $_SESSION["ip"] = $ip;
            $_SESSION["last_ip"] = $last_ip;
            $_SESSION["verified"] = $verified;
            $sql = "UPDATE users SET last_ip='".$serverip."', logged=1 WHERE id='".$_SESSION["id"]."'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username just connected!')";
            mysqli_query($link, $sql);
            header("location: home.php");
        }
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div id="menu">
                    <h1>Login</h1>
                    <div>
                        <input type="text" name="username" class="user-input" value="<?php echo $username; ?>" placeholder="Username or Email">
                        <br>
                        <span class="user-error"><?php echo $username_err; ?></span>
                    </div>    
                    <br>
                    <div>
                        <input type="password" name="password" class="user-input" placeholder="Password">
                        <br>
                        <span class="user-error"><?php echo $password_err; ?></span>
                    </div> 
                    <br>
                    <div>
                        <input type="submit" class="user-button" value="LOGIN">
                    </div>
                    <p>You don't have an account? 
                    <br>
                    <a href="register.php">Create an account</a></p>
                </div>
            </form>
        </div>    
    </body>
</html>