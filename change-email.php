<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config.php";
    $new_email = "";
    $new_email_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_email = htmlspecialchars(trim($_POST["new_email"]));

        if (empty($set_email)) {
            $new_email_err = "Please enter a email.";     
        } elseif (strlen($set_email) < 5) {
            $new_email_err = "Email too short!";
        } elseif (strlen($set_email) > 25) {
            $new_email_err = "Email too long!";
        } elseif (!filter_var($_POST["new_email"], FILTER_VALIDATE_EMAIL)) {
            $new_email_err = "Please enter a valid email!";
        } else {
            $new_email = $set_email;
        }
        if (empty($new_email_err)) {
            $sql = "UPDATE users SET email = ? WHERE id = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_email, $param_id);
                $param_id = $_SESSION["id"];
                if(mysqli_stmt_execute($stmt)){
                    $sqls = "INSERT INTO notifications (texts, userid) VALUES ('(".$_SESSION['username'].") Your email has been changed from ".$_SESSION['email']." to ".$new_email."', '".$_SESSION['id']."')";
                    $querys = mysqli_query($link,$sqls);
                    $_SESSION['email'] = $new_email;
                    header("location: home.php");
                } else {
                    $new_email_err = "Oops! Something went wrong. Please try again later.";
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
    <title>Change Email</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
        body {
            font-size:18px;
        }
        @media only screen and (max-width: 1000px) {
            * {
                text-align:center;
            }
        }
    </style>
</head>
<body>
    <?php include_once("header.php"); ?>
    <div class="wrapper" style="margin:20px">
        <h2>Change Email</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_email_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="new_email" class="form-controls" value="<?php echo $new_email; ?>" placeholder="New Email">
                <br>
                <span class="help-block"><?php echo $new_email_err; ?></span>
            </div>
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Change Email">
            </div>
        </form>
    </div>    
</body>
</html>