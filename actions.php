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
    } else if ($action == "send_message") {
        $message = htmlspecialchars(isset($_POST['message']) ? $_POST['message'] : null);
        $admin = $_SESSION['admin'];
        $id = $_SESSION['id'];
        $founder = $_SESSION['founder'];
        $file = $_SESSION['file'];

        if (!empty($message)) {
            if ($_SESSION['banned'] == 1) {
                return;
            }
            if (strlen($message) > 100000) {
                return;
            } else if (preg_match('/\S{500,}/',$_POST['message'])) { 
                return; 
            } 

            $message = str_replace('<br>', PHP_EOL, $message);
            $message = str_replace("'", "\'", $message);
            $message = strip_tags($message);
            $message = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank" id="link-by-user">$1</a>', $message);
            $sql = "INSERT INTO chat (`message`, `name`, `admin`, `userid`, `file`, `founder`) VALUES ('".$message."', '".$_SESSION['username']."', '".$admin."', '".$id."', '".$file."', '".$founder."')";
            mysqli_query($link, $sql);
        }
    } else if ($action == "load_chat") {
        $result = array();
        $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
        $items = mysqli_query($link, "SELECT * FROM chat WHERE id > " . $start);
        while ($row = mysqli_fetch_assoc($items)) {
            $result['items'][] = $row;
        }
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        echo json_encode($result);
    } else if ($action == "close_ticket") {
        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        } else {
            $id = $_GET['id'];
            $queryString = "SELECT * FROM tickets WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
            $result = mysqli_query($link, $queryString);
            $row = mysqli_fetch_assoc($result);
        
            $ticketid = $row['id'];
            $created_at = $row['created_at'];
            $userid = $row['userid'];
            $closed = $row['closed'];
        
            $user_name = $_SESSION['username'];
            $user_id = $_SESSION['id'];
        
            if ($_SESSION['admin'] == 0 && $_SESSION['id'] != $userid) {
              header("location: home.php");
              return;
            }
        
            $sql = "SELECT * FROM users WHERE id=$userid";
            $newResult = mysqli_query($link, $sql);
            $newRow = mysqli_fetch_assoc($newResult);
            $username = $newRow['username'];
        
            $sql = "UPDATE tickets SET closed=1 WHERE id='$ticketid'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO comments (text, userid, forTicket) VALUES ('$user_name closed the ticket! (ticketid: $id)', '2', '$id')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$user_name</b> closed your ticket! (ticketid: $id)', '$userid')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('You closed <b>$username</b>\'s ticket. (ticketid: $id)', '$user_id')";
            mysqli_query($link, $sql);

            $err_message = "Ticket with ID ".$ticketid." closed!";
            header("location: showTicket.php?id=$id&err_message=".$err_message."");
        }
    } else if ($action == "open_ticket") {
        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        } else {
            $id = $_GET['id'];
        
            $queryString = "SELECT * FROM tickets WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
            $result = mysqli_query($link, $queryString);
            $row = mysqli_fetch_assoc($result);
            
            $ticketid = $row['id'];
            $created_at = $row['created_at'];
            $userid = $row['userid'];
            $closed = $row['closed'];
            
            $user_name = $_SESSION['username'];
            $user_id = $_SESSION['id'];
        
            if ($_SESSION['admin'] == 0 && $_SESSION['id'] != $userid) {
              header("location: home.php");
              return;
            }
        
            $sql = "SELECT * FROM users WHERE id=$userid";
            $newResult = mysqli_query($link, $sql);
            $newRow = mysqli_fetch_assoc($newResult);
            $username = $newRow['username'];
        
            $sql = "UPDATE tickets SET closed=0 WHERE id='$ticketid'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO comments (text, userid, forTicket) VALUES ('$user_name opened the ticket! (ticketid: $id)', '2', '$id')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$user_name</b> opened your ticket! (ticketid: $id)', '$userid')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('You opened <b>$username</b>\'s ticket. (ticketid: $id)', '$user_id')";
            mysqli_query($link, $sql);
            
            $err_message = "Ticket with ID ".$ticketid." opened!";
            header("location: showTicket.php?id=$id&err_message=".$err_message."");
        }
    } else if ($action == "send_ticket_message") {
        $message = htmlspecialchars($_POST['message']);
        $text = $_POST['text'];
        $user_name = $_SESSION['username'];
        $user_id = $_SESSION['id'];
        $message = str_replace('<br>', PHP_EOL, $message);
        $message = str_replace("'", "\'", $message);
        $message = strip_tags($message);
        $message = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank" id="link-by-user">$1</a>', $message);
        $admin = $_SESSION['admin'];
        $file = $_SESSION['file'];

        if (empty($message)) {
            header("location: showTicket.php?id=$text");
            return;
        } else if (!empty($message)) {
            $sql = "INSERT INTO comments (text, userid, forTicket) VALUES ('$message', '$user_id', '$text')";
            $result = mysqli_query($link, $sql);
            header("location: showTicket.php?id=$text");
        }
    } else if ($action == "confirm_email") {
        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        } else {
            $id = $_GET['id'];
        
            if ($_SESSION['id'] != $id) {
                header("location: home.php");
                return;
            }
        
            $queryString = "SELECT * FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
            $result = mysqli_query($link, $queryString);
            $row = mysqli_fetch_assoc($result);
        
            $user_id = $row['id'];
            $username = $row['username'];
        
            $sql = "UPDATE users SET verified=1 WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Your account has been verified.', '".$user_id."')";
            mysqli_query($link, $sql);
            $lastname = $_SESSION['username'];
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username just verified his account!')";
            mysqli_query($link, $sql);

            $err_message = "Your email has been confirmed!";
            header('location: profile.php?id='.$user_id.'&err_message');
        }
    } else if ($action == "create_ticket") {
        $set_message = htmlspecialchars($_POST['message']);
        $acces = 1;

        if (empty($set_message)) {
            $err_message = "Please enter the message.";
            header("location: contact.php?err_message=".$err_message."");     
            $acces = 0;
        }

        if (strlen($set_message) > 1000) {
            $err_message = "You can't have more than 1000 letters!";
            header("location: contact.php?err_message=".$err_message."");     
            $acces = 0;
        }

        $ticket_user_id = $_SESSION['id'];
        $count_the_tickets = mysqli_query($link, "SELECT COUNT(*) FROM `tickets` WHERE userid=$ticket_user_id AND closed=0");
        $number_of_tickets = mysqli_fetch_row($count_the_tickets)[0];

        if ($number_of_tickets >= 10) {
            $err_message = "You cannot have more than 10 tickets open!";
            header("location: contact.php?err_message=".$err_message."");     
            $acces = 0;
        }

        if ($acces == 1) {
            $sql = "INSERT INTO tickets (text, userid) VALUES ('".$set_message."', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $selectquery = "SELECT * FROM tickets ORDER BY id DESC LIMIT 1";
            $result = mysqli_query($link, $selectquery);
            $row = mysqli_fetch_assoc($result);
            $ticketid = $row['id'];
            $ticketuserid = $row['userid'];
            $sql = "SELECT * FROM users WHERE id=$ticketuserid";
            $newResult = mysqli_query($link, $sql);
            $newRow = mysqli_fetch_assoc($newResult);
            $ticketusername = $newRow['username'];
            $sql = "INSERT INTO comments (text, userid, forTicket) VALUES ('Hello, $ticketusername!!\nI am an admin bot and please tell us in detail what your problem is! An admin will help you as soon as possible.\nIf you do not respond within 24 hours, this ticket will be closed!\n\nAdmBot, have a nice day!', '2', '$ticketid')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Ticket (#$ticketid) has been created! You will receive an answer soon!', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);

            $err_message = "The ticket has been created!";
            header("location: showTicket.php?id=$ticketid&err_message=".$err_message."");
        }
    } else if ($action == "delete_bio") {
        $name = $_SESSION['username'];
        $acces = 1;
        if (!isset($_POST['delete'])) {
            $confirm_err = 'Please confirm by pressing the checkbox.';
            header("location: delete-bio.php?err_message=".$confirm_err."");
            $acces = 0;
        }
        if ($acces == 1) {
            $sql = "UPDATE users SET bio='' WHERE username='$name'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Your bio <b>".$_SESSION['bio']."</b> has been deleted.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $_SESSION['bio'] = "";
            $id = $_SESSION['id'];

            $err_message = "Your bio has been deleted!";
            header('location: profile.php?id='.$id.'&err_message='.$err_message.'');
        }
    } else if ($action == "delete_chat") {
        $name = $_SESSION['username'];
        $action = 1;

        if (!isset($_POST['delete'])) {
            $confirm_err = 'Please confirm by pressing the checkbox.';
            header("location: delete-chat.php?err_message=".$confirm_err."");
            $action = 0;
        }
        if ($action == 1) {
            $sql = "DELETE FROM chat";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('You deleted the chat.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$name deleted the chat.')";
            mysqli_query($link, $sql);

            $err_message = "The chat has been deleted!";
            header("location: home.php?err_message=".$err_message."");
        }
    } else if ($action == "delete_nofitications") {
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
            $userid = $_SESSION['id'];
        
            $sql = "DELETE FROM notifications WHERE userid='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$lastname</b> deleted your notifications.', '$user_id')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('You deleted <b>$username</b>\'s notifications.', '$userid')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname deleted $username\'s notifications.')";
            mysqli_query($link, $sql);

            $err_message = "$username's notifications have been deleted.";
            header('location: profile.php?id='.$user_id.'&err_message='.$err_message.'');
        }
    } else if ($action == "delete_tickets") {
        $name = $_SESSION['username'];
        $action = 1;
        if (!isset($_POST['delete'])) {
            $confirm_err = 'Please confirm by pressing the checkbox.';
            header("location: delete-tickets.php?err_message=".$confirm_err."");
            $action = 0;
        }
        if ($action == 1) {
            $sql = "DELETE FROM tickets";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('You deleted the tickets.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$name deleted the tickets.')";
            mysqli_query($link, $sql);

            $err_message = "Tickets have been deleted!";
            header("location: tickets.php?err_message=".$err_message."");
        }
    } else if ($action == "make_admin") {
        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        } else if ($_SESSION['founder'] == 0) {
            header("location: home.php");
        } else {
            $id = $_GET['id'];
            
            $queryString = "SELECT * FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
            $result = mysqli_query($link, $queryString);
            $row = mysqli_fetch_assoc($result);
        
            $user_id = $row['id'];
            $username = $row['username'];
        
            $lastname = $_SESSION['username'];
            
            $sql = "UPDATE users SET admin=1 WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$lastname</b> made you admin.', '".$user_id."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('You made <b>$username</b> admin.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname set $username as administrator.')";
            mysqli_query($link, $sql);

            $err_message = "$username is now admin!";
            header('location: profile.php?id='.$user_id.'&err_message='.$err_message.'');
        }
    } else if ($action == "remove_admin") {
        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        } else if ($_SESSION['founder'] == 0) {
            header("location: home.php");
        } else {
            $id = $_GET['id'];
        
            $queryString = "SELECT * FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
            $result = mysqli_query($link, $queryString);
            $row = mysqli_fetch_assoc($result);
        
            $user_id = $row['id'];
            $username = $row['username'];
        
            $lastname = $_SESSION['username'];
        
            $sql = "UPDATE users SET admin=0 WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('<b>$lastname</b> deleted your admin role.', '".$user_id."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('You deleted <b>".$username."</b> admin role.', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname removed $username from the role of administrator.')";
            mysqli_query($link, $sql);  

            $err_message = "$username is no longer admin!";
            header('location: profile.php?id='.$user_id.'&err_message='.$err_message.'');
        }
    } else if ($action == "reset_password") {
        $user_id = $_SESSION["id"];
        $password = htmlspecialchars($_POST["new_password"]);
        $confirm_password = htmlspecialchars($_POST["confirm_password"]);
        $acces = 1;

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if (empty($password)) {
            $new_password_err = "Please enter a password.";     
            header("location: reset-password.php?err_message=".$new_password_err."");
            $acces = 0;
        } else if (strlen($password) < 6) {
            $new_password_err = "Password must have atleast 6 characters.";
            header("location: reset-password.php?err_message=".$new_password_err."");
            $acces = 0;
        } else if (strlen($password) > 18) {
            $new_password_err = "Password too long (18 characters max).";
            header("location: reset-password.php?err_message=".$new_password_err."");
            $acces = 0;
        } else if (!preg_match("#[0-9]+#", $password)) {
            $new_password_err = "Password must include at least one number!";
            header("location: reset-password.php?err_message=".$new_password_err."");
            $acces = 0;
        } else if (!preg_match("#[a-zA-Z]+#", $password)) {
            $new_password_err = "Password must include at least one letter!";
            header("location: reset-password.php?err_message=".$new_password_err."");
            $acces = 0;
        } else if (empty($confirm_password)) {
            $confirm_password_err = "Please confirm the password.";
            header("location: reset-password.php?err_message=".$confirm_password_err."");
            $acces = 0;
        } else {
            if ($password != $confirm_password) {
                $confirm_password_err = "Password did not match.";
                header("location: reset-password.php?err_message=".$confirm_password_err."");
                $acces = 0;
            }
        }

        if ($acces == 1) {
            $sql = "UPDATE users SET password='$hashed_password' WHERE id='$user_id'";
            mysqli_query($link, $sql);
            $sql = "INSERT INTO notifications (text, userid) VALUES ('Your password has been changed!', '".$_SESSION['id']."')";
            mysqli_query($link, $sql);

            $err_message = "Password changed!";
            header('location: profile.php?id='.$user_id.'&err_message='.$err_message.'');
        }
    }
    mysqli_close($link);
?>