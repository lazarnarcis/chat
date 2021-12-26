<?php
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }
    require "config.php";
    $confirm_err = "";

    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require "gmail_account.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name=$_SESSION['username'];
        if (!isset($_POST['email-verification'])) {
            $confirm_err = 'Please confirm by pressing the checkbox. </br>';
        }
        if (empty($confirm_err)) {
            $myemail = $_SESSION['email'];
            $sql = "INSERT INTO notifications (texts, userid) VALUES ('(".$_SESSION['username'].") An account verification email has been sent to $myemail.', '".$_SESSION['id']."')";
            $query = mysqli_query($link, $sql);

            if ($query) {
                $account_name = $_SESSION['username'];
                $account_id = $_SESSION['id'];
                $account_email = $_SESSION['email'];
                $actual_link = "http://$_SERVER[SERVER_NAME]/confirm-email.php?id=$account_id";

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
                $mail->Subject = "Account verification - $account_name";
                $mail->Body = "Please confirm your account by clicking this link: $actual_link";
                $mail->AddAddress("$account_email");
                $mail->send();

                $id = $_SESSION['id'];
                header('location: profile.php?id='.$id.'');
            } else {
                $confirm_err = "Something went wrong";
            }
        }
    }
?> 
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Send email verification</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/verify-account.css?v=<?php echo time(); ?>">
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div style="margin:20px;">
      <h1>Are you sure we want to send you an account verification email?</h1>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="email-verification" id="email-verification" name="email-verification">
          <label class="form-check-label" for="email-verification">
            Yes, I am.<br>
            My is my email: <?php echo $_SESSION['email']; ?><br>
            After clicking the "Send email verification" button you will have to go to the email and search for the email. Please also check the "Spam" section.
          </label>
        </div>
        <span class="user-error"><?php echo $confirm_err; ?></span>
        <br>
        <button class="user-button" type="submit">Send email verification</button>
      </form>
    </div>
  </body>
</html>