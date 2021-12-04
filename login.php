<?php
    session_start();
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: home.php");
        exit;
    }
    require "config.php";
    $username = $password = "";
    $username_err = $password_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_username = htmlspecialchars(trim($_POST["username"]));
        $set_password = htmlspecialchars(trim($_POST["password"]));

        if (empty($set_username)) {
            $username_err = "Please enter username.";
        } else {
            $username = $set_username;
        }
        if (empty($set_password)) {
            $password_err = "Please enter your password.";
        } else {
            $password = $set_password;
        }
        if (empty($username_err) && empty($password_err)) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $serverip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $serverip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $serverip = $_SERVER['REMOTE_ADDR'];
            }
            $sql = "SELECT id, username, password, admin, created_at, gender, phone, email, bio, file, founder, banned, logged, ip, last_ip FROM users WHERE username = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $username;
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) == 1) {                
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $admin, $created_at, $gender, $phone, $email, $bio, $file, $founder, $banned, $logged, $ip, $last_ip);
                        if (mysqli_stmt_fetch($stmt)) {
                            if (password_verify($password, $hashed_password)) {
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;  
                                $_SESSION["admin"] = $admin;  
                                $_SESSION["created_at"] = $created_at;  
                                $_SESSION["gender"] = $gender;  
                                $_SESSION["phone"] = $phone;  
                                $_SESSION["bio"] = $bio;  
                                $_SESSION["file"] = $file;  
                                $_SESSION["email"] = $email;  
                                $_SESSION["founder"] = $founder;  
                                $_SESSION["banned"] = $banned;  
                                $_SESSION["logged"] = $logged;
                                $_SESSION["ip"] = $ip;
                                $_SESSION["last_ip"] = $last_ip;
                                $sqls = "UPDATE users SET last_ip='".$serverip."', logged=1 WHERE id='".$_SESSION["id"]."'";
                                $queryss = mysqli_query($link, $sqls);
                                $sqlx = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username just connected!')";
                                $queryx = mysqli_query($link,$sqlx);
                                header("location: home.php");
                            } else {
                                $password_err = "The password you entered was not valid.";
                            }
                        }
                    } else {
                        $username_err = "No account found with that username.";
                    }
                } else {
                    $username_err = "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_close($link);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
    <title>Login</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
        body {
            color:white;
            font-size: 18px;
            background-image: url(logos/loginBackground.jpg);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: 100%;
            width: 100%;
        }
        h2 {
            margin: 20px;
        }
        * {
            margin: 0;
        }
        #menu {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 20px;
        }
        .wrapper {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 0;
        }
        .help-block {
            color: lightgrey;
        }
        @media only screen and (max-width: 1000px) {
            * {
                text-align:center;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div id="menu">
                <h2>Login</h2>
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="username" class="form-controls" value="<?php echo $username; ?>" placeholder="Username">
                    <br>
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>    
                <br>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="password" class="form-controls" placeholder="Password">
                    <br>
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div> 
                <br>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="LOGIN">
                </div>
                <p>You don't have an account? 
                <br /><a href="register.php">Create an account</a></p>
            </div>
        </form>
    </div>    
</body>
</html>