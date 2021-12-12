<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      header("location: login.php");
      exit;
  }
  require "config.php";
  $msg = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION['id'];
    if (!empty($_FILES["image"]["name"])) { 
      $fileName = basename($_FILES["image"]["name"]); 
      $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
      $allowTypes = array('jpg','png','jpeg','gif'); 
      if (in_array($fileType, $allowTypes)) { 
          $image = $_FILES['image']['tmp_name'];
          $image_base64 = base64_encode(file_get_contents($image));
          $imgContent = 'data:image/jpg;base64,'.$image_base64; 
          $sql = "UPDATE users SET file='$imgContent' WHERE id='$id'";
          mysqli_query($link, $sql);
          $sql = "UPDATE comments SET file='$imgContent' WHERE userid='$id'";
          mysqli_query($link, $sql);
          $sql = "UPDATE chat SET file='$imgContent' WHERE userid='$id'";
          mysqli_query($link, $sql);
          $lastname = $_SESSION['username'];
          $sqlx = "INSERT INTO chat (action, actiontext) VALUES ('1', '$lastname changed his profile picture.')";
          $queryx = mysqli_query($link,$sqlx);
          $_SESSION['file'] = $imgContent;
          header('location: profile.php?id='.$id.''); 
      } else { 
          $msg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
      } 
    } else { 
        $msg = 'Please select an image file to upload.'; 
    }
  }
?> 
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
	<title>Change Profile Photo</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="css/change-photo.css?v=<?php echo time(); ?>">
</head>
<body>
  <?php include_once("header.php"); ?>
  <div  style="margin: 20px;">
    <h1>Change Profile Photo</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
      <label class="custom-file-upload">
        <input type="file" name="image" />
        Press here to choose file
      </label>
      <br>
      <span class="help-block"><?php echo $msg; ?></span>
      <br>
      <button type="submit" class="btn-primary">Change photo</button>
    </form>
  </div>
</body>
</html>