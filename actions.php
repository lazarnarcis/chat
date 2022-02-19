<?php
    session_start();
    require 'config/config.php';

    $action = $_GET['action'];

    if (empty($action)) {
        header("location: index.php");
    }
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit;
    }

    if ($action == "ban") {
        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        } else if ($_SESSION['admin'] == 0) {
            header("location: home.php");
        } else {
            $id = $_GET['id'];
    
            $queryString = "SELECT * FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
            $result = mysqli_query($link, $queryString);
            $row = mysqli_fetch_assoc($result);
    
            $user_id = $row['id'];
            $username = $row['username'];
            $lastname = $_SESSION['username'];
    
            $sql = "UPDATE users SET banned=1 WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$lastname</b> banned you.', '".$user_id."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('You banned <b>".$username."</b>.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username has been banned by $lastname.')";
            mysqli_query($link, $sql);

            $err_message = "$username was banned!";
            header('location: profile.php?id='.$user_id.'&err_message='.$err_message.'');
        }
    } else if ($action == "unban") {
        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        } else if ($_SESSION['admin'] == 0) {
            header("location: home.php");
        } else {
            $id = $_GET['id'];
        
            $queryString = "SELECT * FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
            $result = mysqli_query($link, $queryString);
            $row = mysqli_fetch_assoc($result);
        
            $user_id = $row['id'];
            $username = $row['username'];
            $lastname = $_SESSION['username'];
        
            $sql = "UPDATE users SET banned=0 WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$lastname</b> unbanned you.', '".$user_id."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('You unbanned <b>".$username."</b>.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username has been unbanned by $lastname.')";
            mysqli_query($link, $sql);

            $err_message = "$username was unbanned!";
            header('location: profile.php?id='.$user_id.'&err_message='.$err_message.'');
        }
    } else if ($action == "change_bio") {
        $set_bio = htmlspecialchars($_POST["new_bio"]);
        $acces = 1;

        if (empty($set_bio)) {
            $new_bio_err = "Bio enter the new bio.";
            header("location: change-bio.php?new_bio_err=".$new_bio_err."");
            $acces = 0;
        } else if (strlen($set_bio) > 100) {
            $new_bio_err = "Bio too long. (max 100 characters)";
            header("location: change-bio.php?new_bio_err=".$new_bio_err."");
            $acces = 0;
        }

        if ($acces == 1) {
            $user_id = $_SESSION["id"];
            $sql = "UPDATE users SET bio='$set_bio' WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Your bio has been changed from <b>".$_SESSION['bio']."</b> to <b>".$set_bio."</b>.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $_SESSION['bio'] = $set_bio;

            $err_message = "Your bio has been changed!";
            header('location: profile.php?err_message='.$err_message.'&id='.$user_id.'');
        }
    } else if ($action == "change_email") {
        $set_email = htmlspecialchars($_POST["new_email"]);
        $acces = 1;

        if (empty($set_email)) {
            $new_email_err = "Please enter a email.";
            header("location: change-email.php?new_email_err=".$new_email_err."");
            $acces = 0;     
        } else if (strlen($set_email) < 5) {
            $new_email_err = "Email too short!";
            header("location: change-email.php?new_email_err=".$new_email_err."");
            $acces = 0;     
        } else if (strlen($set_email) > 50) {
            $new_email_err = "Email too long!";
            header("location: change-email.php?new_email_err=".$new_email_err."");
            $acces = 0;     
        } else if (!filter_var($_POST["new_email"], FILTER_VALIDATE_EMAIL)) {
            $new_email_err = "Please enter a valid email!";
            header("location: change-email.php?new_email_err=".$new_email_err."");
            $acces = 0;     
        } 

        if ($acces == 1) {
            $user_id = $_SESSION["id"];
            $sql = "UPDATE users SET email='$set_email', verified=0 WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Your email has been changed from <b>".$_SESSION['email']."</b> to <b>".$set_email."</b>.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $_SESSION['email'] = $set_email;

            $err_message = "Your email has been changed!";
            header('location: profile.php?err_message='.$err_message.'&id='.$user_id.'');
        }
    } else if ($action == "change_name") {
        $set_name = htmlspecialchars($_POST["new_name"]);
        $acces = 1;
        $new_name = "";

        if (empty($set_name)) {
            $new_name_err = "Please enter the new name."; 
            header("location: change-name.php?new_name_err=".$new_name_err."");
            $acces = 0;        
        } else if (strlen($set_name) < 6) {
            $new_name_err = "Username must have atleast 6 characters.";
            header("location: change-name.php?new_name_err=".$new_name_err."");
            $acces = 0;        
        } else if (strlen($set_name) > 25) {
            $new_name_err = "Username too long.";
            header("location: change-name.php?new_name_err=".$new_name_err."");
            $acces = 0;        
        } else if ( preg_match('/\s/',$set_name)) {
            $new_name_err = "Your username must not contain any whitespace.";
            header("location: change-name.php?new_name_err=".$new_name_err."");
            $acces = 0;        
        } else if (preg_match('/[A-Z]/', $set_name)) {
            $new_name_err = "The name cannot contain uppercase letters.";
            header("location: change-name.php?new_name_err=".$new_name_err."");
            $acces = 0;        
        } else {
            $sql = "SELECT id FROM users WHERE username = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $set_name;
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        $new_name_err = "This username is already taken.";
                        header("location: change-name.php?new_name_err=".$new_name_err."");
                        $acces = 0;   
                    } else {
                        $new_name = $set_name;
                    }
                } else {
                    $new_name_err = "Oops! Something went wrong. Please try again later.";
                    header("location: change-name.php?new_name_err=".$new_name_err."");
                    $acces = 0;   
                }
                mysqli_stmt_close($stmt);
            }
        }
        if ($acces == 1) {
            $param_id = $_SESSION["id"];
            $lastname = $_SESSION['username'];
            $sql = "UPDATE users SET username='$new_name' WHERE id='$param_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Your name has been changed from <b>".$lastname."</b> to <b>".$new_name."</b>.', '".$param_id."')";
            mysqli_query($link, $sql);
            $sql = "UPDATE chat SET name='$new_name' WHERE userid='$param_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname changed his name from $lastname to $new_name.')";
            mysqli_query($link, $sql);
            $_SESSION['username'] = $new_name;

            $err_message = "Your name has been changed!";
            header('location: profile.php?err_message='.$err_message.'&id='.$param_id.'');
        }
    } else if ($action == "change_photo") {
        $id = $_SESSION['id'];
        $acces = 1;
        if (!empty($_FILES["image"]["name"])) { 
            $fileName = basename($_FILES["image"]["name"]); 
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $allowTypes = array('jpg','png','jpeg','gif'); 
            if (!in_array($fileType, $allowTypes)) {
                $msg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
                header("location: change-photo.php?new_photo_err=".$msg."");
                $acces = 0;    
            } 
        } else { 
            $msg = 'Please select an image file to upload.'; 
            header("location: change-photo.php?new_photo_err=".$msg."");
            $acces = 0; 
        }

        if ($acces == 1) {
            $image = $_FILES['image']['tmp_name'];
            $image_base64 = base64_encode(file_get_contents($image));
            $imgContent = 'data:image/jpg;base64,'.$image_base64; 
            $sql = "UPDATE users SET file='$imgContent' WHERE id='$id'";
            mysqli_query($link, $sql);
            $sql = "UPDATE chat SET file='$imgContent' WHERE userid='$id'";
            mysqli_query($link, $sql);
            $lastname = $_SESSION['username'];
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname changed his profile picture.')";
            mysqli_query($link, $sql);
            $_SESSION['file'] = $imgContent;

            $err_message = "Your profile picture has been changed!";
            header('location: profile.php?err_message='.$err_message.'&id='.$id.''); 
        }
    }
?>