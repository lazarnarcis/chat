<?php
    require "config.php";
    $username = $password = $confirm_password = $email = $gender = $phone  = "";
    $username_err = $password_err = $confirm_password_err = $email_err = $gender_err = $phone_err = $confirmation_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_username = htmlspecialchars(trim($_POST["username"]));
        $set_email = htmlspecialchars(trim($_POST['email']));
        $set_phone = htmlspecialchars(trim($_POST['phone']));
        $set_password = htmlspecialchars(trim($_POST['password']));
        $set_confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

        if (empty($set_username)) {
            $username_err = "Please enter a username.";
        } else if (strlen($set_username) < 6) {
            $username_err = "Username must have atleast 6 characters.";
        } else if (strlen($set_username) > 25) {
            $username_err = "Username too long.";
        } else if (preg_match('/\s/', $set_username)) {
            $username_err = "Your username must not contain any whitespace.";
        } else if (preg_match('/[A-Z]/', $set_username)) {
            $username_err = "The name cannot contain uppercase letters.";
        } else {
            $sql = "SELECT id FROM users WHERE username = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $set_username;
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $username_err = "This username is already taken.";
                    } else {
                        $username = $set_username;
                    }
                } else{
                    $username_err = "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
            $sqls = "SELECT id FROM users WHERE email = ?";
            if ($stmts = mysqli_prepare($link, $sqls)) {
                mysqli_stmt_bind_param($stmts, "s", $param_usernames);
                $param_usernames = $set_email;
                if (mysqli_stmt_execute($stmts)) {
                    mysqli_stmt_store_result($stmts);
                    if (mysqli_stmt_num_rows($stmts) == 1) {
                        $email_err = "This email is already taken.";
                    } else {
                        $email = $set_email;
                    }
                } else{
                    $email_err = "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmts);
            }
        }
        if (empty($set_password)) {
            $password_err = "Please enter a password.";     
        } elseif (strlen($set_password) < 6) {
            $password_err = "Password must have atleast 6 characters.";
        } elseif (strlen($set_password) > 18) {
            $password_err = "Password too long (18 characters max).";
        } elseif (!preg_match("#[0-9]+#", $set_password)) {
            $password_err = "Password must include at least one number!";
        } elseif (!preg_match("#[a-zA-Z]+#", $set_password)) {
            $password_err = "Password must include at least one letter!";
        } else {
            $password = $set_password;
        }
        if (!empty($set_phone) && strlen($set_phone) < 5) { 
            $phone_err = "Phone too short!"; 
        } else if (!empty($set_phone) && strlen($set_phone) > 20) { 
            $phone_err = "Phone too long!"; 
        } else { 
            $phone = $set_phone; 
        }
        $gender;
        if (isset($_POST['gender'])) {
            if ($_POST['gender'] == 'male') {
                $gender = 1;
            } else if ($_POST['gender'] == 'female') {
                $gender = 2;
            } else if ($_POST['gender'] == 'other') {
                $gender = 3;
            }
        } else { 
            $gender_err = "Please select your gender!";
        }
        if (empty($set_email)) {
            $email_err = "Please enter a email.";     
        } elseif (strlen($set_email) < 5) {
            $email_err = "Email too short!";
        } elseif (strlen($set_email) > 50) {
            $email_err = "Email too long!";
        } elseif (!filter_var($set_email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Please enter a valid email!";
        } else {
            $email = $set_email;
        }
        if (empty($set_confirm_password)) {
            $confirm_password_err = "Please confirm password.";     
        } else {
            $confirm_password = $set_confirm_password;
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Password did not match.";
            }
        }
        if (!isset($_POST['confirmation'])) {
            $confirmation_err = "You must agree to the Terms and Conditions!";
        }
        if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($phone_err) && empty($gender_err) && empty($email_err) && isset($_POST['confirmation'])) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $serverip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $serverip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $serverip = $_SERVER['REMOTE_ADDR'];
            }
            $userBackground;
            if ($gender == 1) {
                $userBackground = "male.svg";
            } else if ($gender == 2) {
                $userBackground = "female.svg";
            } else if ($gender == 3) {
                $userBackground = "random.svg";
            }
            $sql = "INSERT INTO users (username, password, admin, email, gender, phone, file, ip, last_ip, logged) VALUES (?, ?, 0, ?, ?, ?, '$userBackground', '".$serverip."', '".$serverip."', 0)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_password, $email, $gender, $phone);
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT);
                if (mysqli_stmt_execute($stmt)) {
                    header("location: login.php");
                    $sqlx = "INSERT INTO chat (action, actiontext) VALUES ('1', '$param_username just created an account.')";
                    $queryx = mysqli_query($link,$sqlx);
                } else {
                    echo "Something went wrong. Please try again later.";
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
    <title>Sign Up</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <style>
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div id="menu">
                <h2>Sign Up</h2>
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="username" class="form-controls" value="<?php echo $username; ?>" placeholder="Username"><br>
                    <span class="help-block"><?php echo $username_err; ?></span>
                </div>    
                <br>
                <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="email" class="form-controls" value="<?php echo $email; ?>" placeholder="Email"><br>
                    <span class="help-block"><?php echo $email_err; ?></span>
                </div>  
                <br>
                Select your gender:
                <input class="form-check-input" type="radio" name="gender" id="male" value="male">
                <label class="form-check-label" for="male">
                Male
                </label>
                <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                <label class="form-check-label" for="female">
                Female
                </label>
                <input class="form-check-input" type="radio" name="gender" id="other" value="other">
                <label class="form-check-label" for="other">
                I prefer not to say
                </label>
                <div class="form-group <?php echo (!empty($gender_err)) ? 'has-error' : ''; ?>">
                    <span class="help-block"><?php echo $gender_err; ?></span>
                </div>
                <br>
                <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
                    <input type="number" name="phone" class="form-controls" placeholder="Phone number (optional)" value="<?php echo $phone; ?>"><br>
                    <span class="help-block"><?php echo $phone_err; ?></span>
                </div>
                <br>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="password" class="form-controls" value="<?php echo $password; ?>" placeholder="Password"><br>
                    <span class="help-block"><?php echo $password_err; ?></span>
                </div>
                <br>
                <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                    <input type="password" name="confirm_password" class="form-controls" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password"><br>
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <br>
                <div class="form-group <?php echo (!empty($confirmation_err)) ? 'has-error' : ''; ?>">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="confirmation" id="confirmation" name="confirmation">
                        <label class="form-check-label" for="confirmation">
                        I agree to the <a href="terms.php">Terms and Conditions</a>.
                        </label>
                    </div>
                    <span class="help-block"><?php echo $confirmation_err; ?></span>
                </div>
                <br>
                <div class="form-group">
                    <input type="submit" class="btn-primary" value="REGISTER">
                </div>
                <p>Already have an account?<br/><a href="login.php">Login here</a></p>
            </div>
        </form>
    </div>    
</body>
</html>