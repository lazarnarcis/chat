<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config.php";
    $new_name= "";
    $new_name_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_name = htmlspecialchars(trim($_POST["new_name"]));
        if (empty($set_name)) {
            $new_name_err = "Please enter the new name.";     
        } elseif (strlen($set_name) < 6) {
            $new_name_err = "Username must have atleast 6 characters.";
        }elseif (strlen($set_name) > 25) {
            $new_name_err = "Username too long.";
        }elseif ( preg_match('/\s/',$set_name)) {
            $new_name_err = "Your username must not contain any whitespace.";
        }elseif (preg_match('/[A-Z]/', $set_name)) {
            $new_name_err = "The name cannot contain uppercase letters.";
        } else {
            $sql = "SELECT id FROM users WHERE username = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $set_name;
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $new_name_err = "This username is already taken.";
                    } else {
                        $new_name = $set_name;
                    }
                } else {
                    $new_name_err = "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        if (empty($new_name_err)) {
            $sql = "UPDATE users SET username = ? WHERE id = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $new_name, $param_id);
                $param_id = $_SESSION["id"];
                if (mysqli_stmt_execute($stmt)) {
                    $sqls = "INSERT INTO notifications (texts, userid) VALUES ('(".$_SESSION['username'].") Your name has been changed from ".$_SESSION['username']." to ".$new_name."', '".$_SESSION['id']."')";
                    $querys = mysqli_query($link,$sqls);
                    $sqlx = "UPDATE chat SET name = ? WHERE userid = ?";
                    if ($stmx = mysqli_prepare($link, $sqlx)) {
                        mysqli_stmt_bind_param($stmx, "si", $new_name, $user_id);
                        $user_id = $_SESSION['id'];
                        if (mysqli_stmt_execute($stmx)) {
                            $sqlz = "UPDATE chat SET deletedby = ? WHERE userdeletedid = ?";
                            if ($stmx = mysqli_prepare($link, $sqlz)) {
                                mysqli_stmt_bind_param($stmx, "si", $new_name, $user_id);
                                $user_id = $_SESSION['id'];
                                if (mysqli_stmt_execute($stmx)) {
                                    $lastname = $_SESSION['username'];
                                    $sqlx = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname changed his name from $lastname to $new_name.')";
                                    $queryx = mysqli_query($link,$sqlx);
                                    $_SESSION['username'] = $new_name;
                                    header('location: profile.php?id='.$user_id.'');
                                } else {
                                    $new_name_err = "Oops! Something went wrong. Please try again later.";
                                }
                            }
                        } else {
                            $new_name_err = "Oops! Something went wrong. Please try again later.";
                        }
                    }
                } else {
                    $new_name_err = "Oops! Something went wrong. Please try again later.";
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
    <title>Change Name</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/change-name.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include_once("header.php"); ?>
    <div class="wrapper" style="margin:20px;">
        <h2>Change Name</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_name_err)) ? 'has-error' : ''; ?>">
                <input type="text" name="new_name" class="form-controls" value="<?php echo $new_name; ?>" placeholder="New Name">
                <br>
                <span class="help-block"><?php echo $new_name_err; ?></span>
            </div>
            <br>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Change Name">
            </div>
        </form>
    </div>    
</body>
</html>