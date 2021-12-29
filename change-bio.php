<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config/config.php";
    $new_bio = "";
    $new_bio_err = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_bio = htmlspecialchars($_POST["new_bio"]);

        if (empty($set_bio)) {
            $new_bio_err = "Bio enter the new bio.";     
        } elseif (strlen($set_bio) > 100) {
            $new_bio_err = "Bio too long. (max 100 characters)";
        } else {
            $new_bio = $set_bio;
        }
        if (empty($new_bio_err)) {
            $user_id = $_SESSION["id"];
            $sql = "UPDATE users SET bio='$new_bio' WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Your bio has been changed from <b>".$_SESSION['bio']."</b> to <b>".$new_bio."</b>.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $_SESSION['bio'] = $new_bio;
            header('location: profile.php?id='.$user_id.'');
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Change Bio</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general1.css?v=<?php echo time(); ?>">
    </head>
    <body>
        <?php require_once("header.php"); ?>
        <div class="wrapper" style="margin:20px;">
            <h1>Change Bio</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
                <div>
                    <input type="text" name="new_bio" class="user-input" value="<?php echo $new_bio; ?>" placeholder="New Bio">
                    <br>
                    <span class="user-error"><?php echo $new_bio_err; ?></span>
                </div>
                <br>
                <div>
                    <input type="submit" class="user-button" value="Change Bio">
                </div>
            </form>
        </div>    
    </body>
</html>