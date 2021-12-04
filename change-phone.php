<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config.php";
    $new_phone= "";
    $new_phone_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_phone = htmlspecialchars(trim($_POST["new_phone"]));
        if (empty($set_phone)) {
            $new_phone_err = "Please enter the new phone.";     
        } elseif (strlen($set_phone) < 6) {
            $new_phone_err = "Phone must have atleast 6 characters.";
        } elseif (strlen($set_phone) > 16) {
            $new_phone_err = "Phone too long. (max 16 numbers)";
        } else {
            $new_phone = $set_phone;
        }
        if (empty($new_phone_err)) {
            $sql = "UPDATE users SET phone = ? WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $new_phone, $param_id);
                $param_id = $_SESSION["id"];
                if (mysqli_stmt_execute($stmt)) {
                    $sqls = "INSERT INTO notifications (texts, userid) VALUES ('(".$_SESSION['username'].") Your number has been changed from ".$_SESSION['phone']." to ".$new_phone."', '".$_SESSION['id']."')";
                    $querys = mysqli_query($link,$sqls);
                    $_SESSION['phone'] = $new_phone;
                    header("location: home.php");
                } else {
                    $new_phone_err = "Oops! Something went wrong. Please try again later.";
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
    <title>Change Phone</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <style type="text/css">
        body {
          font-size: 18px;
        }
        @media only screen and (max-width: 1000px) {
            * {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <?php include_once("header.php"); ?>
    <div class="wrapper" style="margin:20px;">
        <h2>Change Phone</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_phone_err)) ? 'has-error' : ''; ?>">
                <input type="number" name="new_phone" class="form-controls" value="<?php echo $new_phone; ?>" placeholder="New Phone">
                <br>
                <span class="help-block"><?php echo $new_phone_err; ?></span>
            </div>
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Change Phone">
            </div>
        </form>
    </div>    
</body>
</html>