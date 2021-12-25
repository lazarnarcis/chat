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
        $set_name = htmlspecialchars($_POST["new_name"]);

        if (empty($set_name)) {
            $new_name_err = "Please enter the new name.";     
        } else if (strlen($set_name) < 6) {
            $new_name_err = "Username must have atleast 6 characters.";
        } else if (strlen($set_name) > 25) {
            $new_name_err = "Username too long.";
        } else if ( preg_match('/\s/',$set_name)) {
            $new_name_err = "Your username must not contain any whitespace.";
        } else if (preg_match('/[A-Z]/', $set_name)) {
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
            $param_id = $_SESSION["id"];
            $lastname = $_SESSION['username'];
            $sql = "UPDATE users SET username='$new_name' WHERE id='$param_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (texts, userid) VALUES ('(".$lastname.") Your name has been changed from ".$lastname." to ".$new_name."', '".$param_id."')";
            mysqli_query($link, $sql);
            $sql = "UPDATE chat SET name='$new_name' WHERE userid='$param_id'";
            mysqli_query($link, $sql);
            $sql = "UPDATE tickets SET username='$new_name' WHERE userid='$param_id'";
            mysqli_query($link, $sql);
            $sql = "UPDATE comments SET username='$new_name' WHERE userid='$param_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname changed his name from $lastname to $new_name.')";
            mysqli_query($link, $sql);
            $_SESSION['username'] = $new_name;
            header('location: profile.php?id='.$param_id.'');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Change Name</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/change-name.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px;">
            <h2>Change Name</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div>
                    <input type="text" name="new_name" class="user-input" value="<?php echo $new_name; ?>" placeholder="New Name">
                    <br>
                    <span class="user-error"><?php echo $new_name_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="submit" class="user-button" value="Change Name">
                </div>
            </form>
        </div>    
    </body>
</html>