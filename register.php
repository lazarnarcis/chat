<?php
    require "config/config.php";
    require 'PHPMailer-master/src/Exception.php';
	require 'PHPMailer-master/src/PHPMailer.php';
	require 'PHPMailer-master/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

    require "gmail_account/gmail_account.php";

    $username = $password = $confirm_password = $email = $file_base64 = "";
    $username_err = $password_err = $confirm_password_err = $email_err = $file_error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $set_username = htmlspecialchars($_POST["username"]);
        $set_email = htmlspecialchars($_POST['email']);
        $set_password = htmlspecialchars($_POST['password']);
        $set_confirm_password = htmlspecialchars($_POST['confirm_password']);
        $set_file = htmlspecialchars($_FILES['image']['tmp_name']);

        if (!empty($_FILES["image"]["name"])) { 
            $fileName = basename($_FILES["image"]["name"]); 
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowTypes = array('jpg','png','jpeg','gif'); 
            
            if (in_array($fileType, $allowTypes)) { 
                $image_base64 = base64_encode(file_get_contents($set_file));
                $base64 = 'data:image/jpg;base64,'.$image_base64; 
                $file_base64 = $base64;
            } else { 
                $file_error = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
            } 
        } else { 
            $file_error = 'Please select an image file to upload.'; 
        }

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
            $sql = "SELECT id FROM users WHERE email = ?";
            if ($stmts = mysqli_prepare($link, $sql)) {
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
        if (empty($set_email)) {
            $email_err = "Please enter a email.";     
        } elseif (strlen($set_email) < 5) {
            $email_err = "Email too short!";
        } elseif (strlen($set_email) > 50) {
            $email_err = "Email too long!";
        } elseif (preg_match('/[A-Z]/', $set_email)) {
            $email_err = "The email cannot contain uppercase letters.";
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
        if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err) && empty($file_error)) {
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $serverip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $serverip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $serverip = $_SERVER['REMOTE_ADDR'];
            }

            $domain = "http://$_SERVER[HTTP_HOST]";
            $date = date("l jS \of F Y h:i:s A");

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465;
            $mail->IsHTML(true);
            $mail->Username = "$email_gmail";
            $mail->Password = "$password_gmail";
            $mail->SetFrom("$email_gmail");
            $mail->Subject = "Thanks for registering - $domain";
            $mail->Body = "Thank you for registering on our site, <b>$username</b>.<br>This is an open source project (https://github.com/lazarnarcis/chat). <br>The IP you registered with is: $serverip.<br>The account was created at: $date<br><br>Regards,<br>Narcis.";
            $mail->AddAddress("$email");
            $mail->send();

            $sql = "INSERT INTO users (username, password, admin, email, file, ip, last_ip, logged, verified) VALUES (?, ?, 0, ?, '$file_base64', '".$serverip."', '".$serverip."', 0, 0)";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $email);
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT);
                if (mysqli_stmt_execute($stmt)) {
                    header("location: login.php");
                    $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$param_username just created an account.')";
                    mysqli_query($link, $sql);
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
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
        <title>Sign Up</title>
        <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
        <link rel="stylesheet" href="css/register.css?v=<?php echo time(); ?>">
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div id="menu">
                    <h2>Sign Up</h2>
                    <div>
                        <input type="text" name="username" class="user-input" value="<?php echo $username; ?>" placeholder="Username"><br>
                        <span class="user-error"><?php echo $username_err; ?></span>
                    </div>    
                    <br>
                    <div>
                        <input type="text" name="email" class="user-input" value="<?php echo $email; ?>" placeholder="Email"><br>
                        <span class="user-error"><?php echo $email_err; ?></span>
                    </div>  
                    <br>
                    <div>
                        <label for="image" class="custom-file-upload">
                            Click here to add a profile picture
                        </label>
                        <input id="image" name="image" type="file" value="<?php echo $file_base64; ?>" style="display:none;">
                        <br>
                        <span class="user-error"><?php echo $file_error; ?></span>
                    </div>
                    <br>
                    <div>
                        <input type="password" name="password" class="user-input" value="<?php echo $password; ?>" placeholder="Password"><br>
                        <span class="user-error"><?php echo $password_err; ?></span>
                    </div>
                    <br>
                    <div>
                        <input type="password" name="confirm_password" class="user-input" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password"><br>
                        <span class="user-error"><?php echo $confirm_password_err; ?></span>
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