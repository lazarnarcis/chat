<?php
    require "config/config.php";
    require 'PHPMailer-master/src/Exception.php';
	require 'PHPMailer-master/src/PHPMailer.php';
	require 'PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

    require "gmail_account/gmail_account.php";

    $username = $password = $confirm_password = $email = "";
    $err_message = "";

    if (!empty($_GET['err_message'])) {
        $err_message = $_GET['err_message'];
    }
    if (!empty($_GET['password'])) {
        $password = $_GET['password'];
    }
    if (!empty($_GET['username'])) {
        $username = $_GET['username'];
    }
    if (!empty($_GET['confirm_password'])) {
        $confirm_password = $_GET['confirm_password'];
    }
    if (!empty($_GET['email'])) {
        $email = $_GET['email'];
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Sign Up</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/general3.css?v=<?php echo time(); ?>">
        <script src="jquery/jquery.js"></script>
        <script>
            $(document).ready(function() {
                $('#image').change(function() {
                    var i = $(this).prev('label').clone();
                    var file = $('#image')[0].files[0].name;
                    if (file.length > 25) file = file.substring(0, 25) + "...";
                    $(this).prev('label').text(file);
                });
            });
        </script>
    </head>
    <body>
        <div class="wrapper">
            <form action="actions.php?action=register" method="post" enctype="multipart/form-data">
                <div id="menu">
                    <h1>Sign Up</h1>
                    <div>
                        <input type="text" name="username" class="user-input" value="<?php echo $username; ?>" placeholder="Username"><br>
                    </div>    
                    <br>
                    <div>
                        <input type="text" name="email" class="user-input" value="<?php echo $email; ?>" placeholder="Email"><br>
                    </div>  
                    <br>
                    <div>
                        <label for="image" class="custom-file-upload">
                            Click here to add a profile picture
                        </label>
                        <input id="image" name="image" type="file" style="display:none;">
                        <br>
                    </div>
                    <br>
                    <div>
                        <input type="password" name="password" class="user-input" value="<?php echo $password; ?>" placeholder="Password"><br>
                    </div>
                    <br>
                    <div>
                        <input type="password" name="confirm_password" class="user-input" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password"><br>
                        <span class="user-error"><?php echo $err_message; ?></span>
                    </div>
                    <br>
                    <div>
                        <input type="submit" class="user-button" value="REGISTER">
                    </div>
                    <p>Already have an account?<br><a href="login.php">Login here</a></p>
                </div>
            </form>
        </div>    
    </body>
</html>