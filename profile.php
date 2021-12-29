<?php
  session_start();
  require 'config/config.php';
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];
  }
  $queryString = "SELECT id, username, email, created_at, email, bio, file, admin, founder, banned,ip, last_ip, logged, verified FROM users WHERE id='$id' ORDER BY username DESC LIMIT 1"; 
  $query = $link->prepare($queryString);
  $query->execute();
  $query->store_result();
  $query->bind_result($user_id, $username, $email, $created_at, $email, $bio, $file, $admin, $founder, $banned, $ip, $last_ip, $logged, $verified);
?>
<!DOCTYPE html>
<html> 
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height">
    <title>Profile</title>
    <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/profile.css?v=<?php echo time(); ?>">
    <script>
      function showImg() {
        let picture = document.getElementById("profileIMG");
        picture.style.transform = "scale(1)";
      }
      function unshowImg() {
        let picture = document.getElementById("profileIMG");
        picture.style.transform = "scale(0)";
      }
    </script>
  </head>
  <body>
    <?php require_once("header.php"); ?>
    <div class="wrapper">
      <?php while ($query->fetch()):
        $bio = nl2br($bio, false);  
      ?>
      <div id="user-interface">
        <div id="profileIMG">
            <div>
              <div id="topRight">
                <span>Click the "X" button to close this profile photo.</span>
                <img src="logos/close.svg" alt="Close" srcset="" id="closeImg" onClick='unshowImg();'>
              </div>
              <h1><?php echo $username . "'s Profile Picture" ?></h1>
              <img src='<?php echo $file; ?>' alt="Profile Photo" id="pictureFullScreen" />
              <p style="margin: 0;"><a href="<?php echo $file; ?>" style="text-decoration: underline;" download="profile_picture_<?php echo $username; ?>.jpg">Download image</a></p>
            </div>
          </div>
        <?php
          echo "
            <div id='img_div'>
              <img id='image-x' src='$file' alt='Profile Phot' onClick='showImg();' />
              <br>
              <div id='nameOnline'>
              <h1>$username</h1>
          ";
              if ($logged == 1) {
                echo "<span id='online'>Online</span>";
              } else if ($logged == 0) {
                echo "<span id='offline'>Offline</span>";
              }
          echo "
              </div>
              <p id='bio'>
          ";
          if ($bio == "") {
            echo '';
          } else {
            echo $bio;
          }
          echo "
            </p>
          ";
          if ($_SESSION['id'] == $user_id) {
            echo " <div id='two-changes'><a href='change-photo.php' id='button-user'><p>change photo</p></a> <a href='change-name.php' id='button-user'><p>change name</p></a></div>";
          }
          echo "</div>";
        ?>
        <div id="roles">
          <?php
            if ($admin == 1) {
              echo ' <a href="admins.php"><p id="admin">Admin Chat</p></a>';
            }
            if ($founder == 1) {
              echo ' <a href="founders.php"><p id="founder">Founder Chat</p></a>';
            }
            if ($banned == 1) {
              echo ' <a href="banned.php"><p id="banned">Banned</p></a>';
            }
            if ($verified == 1) {
              echo ' <p id="verified">Verified</p>';
            }
            if ($verified == 0) {
              echo ' <p id="verified" class="not-verified">Not Verified</p>';
            }
          ?>
        </div>
      </div>
      <div id="user-settings">
        <h2>Profile Details</h2>
        <?php
          if ($_SESSION['id'] == $user_id) {
            echo "
              <div class='title-of-div'>
                <div class='title-text'>
                  Bio
            ";
            if ($bio != "") {
              echo "<a href='delete-bio.php' id='button-user'>delete bio</a>";
            }
            echo "
              <a href='change-bio.php' id='button-user'>change</a>
                </div>
              </div>
            ";
          }
        ?>
        <div class="title-of-div">
          <div class="title-text">Account ID</div> 
          <div class="content-text">#<?php echo $user_id; ?></div>
        </div>
        <?php
          if ($_SESSION['founder'] == 1 && $_SESSION['id'] != $user_id) {
            if ($admin == 0) {
              echo "
              <div class='title-of-div'>
                <div class='title-text'>Admin <a href='make-admin.php?id=$user_id' id='button-user'>make admin</a></div>
                <div class='content-text'>
              ";
              if ($admin == 0) {
                echo "No";
              }
              echo "
                </div>
              </div>
              ";
            } else if ($admin == 1) {
              echo "
              <div class='title-of-div'>
                <div class='title-text'>Admin <a href='remove-admin.php?id=$user_id' id='button-user'>remove admin</a></div>
                <div class='content-text'>
              ";
              if ($admin == 1) {
                echo "Yes";
              }
              echo "
                </div>
              </div>
              ";
            }
          }
          if ($_SESSION['admin'] == 1 && $_SESSION['id'] != $user_id) {
            if ($banned == 0) {
              echo "
                <div class='title-of-div'>
                  <div class='title-text'>Banned <a href='ban.php?id=$user_id' id='button-user'>ban user</a></div>
                  <div class='content-text'>
              ";
              if ($banned == 0) {
                echo "No";
              }
              echo "
                  </div>
                </div>
              ";
            } else if ($banned == 1) {
              echo "
                <div class='title-of-div'>
                  <div class='title-text'>Banned <a href='unban.php?id=$user_id' id='button-user'>unban user</a></div>
                  <div class='content-text'>
              ";
              if ($banned == 1) {
                echo "Yes";
              }
              echo "
                  </div>
                </div>
              ";
            }
          }
        ?>
        <?php 
          if ($_SESSION['admin'] == 1) {
            echo "
              <div class='title-of-div'>
                <div class='title-text'>Notifications <a href='notifications.php?id=$user_id' id='button-user'>show</a></div>
              </div>
            ";
          }
        ?>
        <div class="title-of-div">
          <div class="title-text">Account has been created at</div>
          <div class="content-text">
            <?php 
              echo $created_at; 
              if ($_SESSION['founder'] == 1) {
                echo " (account created with IP: $ip)";
              }
            ?>
          </div>
        </div>
        <?php
          if ($_SESSION['founder'] == 1 || $_SESSION['id'] == $user_id) {
            echo "
              <div class='title-of-div'>
              <div class='title-text'>Last IP</div>
                <div class='content-text'>
                  $last_ip
                </div>
              </div>
            ";
          }
        ?>
        <?php 
          if ($_SESSION['id'] == $user_id) {
            echo "
              <div class='title-of-div'>
                <div class='title-text'>
                  Password <a href='reset-password.php' id='button-user'>change</a>
                </div>
                <div class='content-text'>********</div> 
              </div>
            ";
          }
        ?>
        <div class="title-of-div">
          <div class="title-text">Email
            <?php
              if ($_SESSION['id'] != $user_id) {
                echo ' <a href="mailto:<?php echo $email; ?>" id="button-user">send an email</a>';
              }
              if ($_SESSION['id'] == $user_id) {
                echo ' <a href="change-email.php" id="button-user">change</a>';
              }
            ?>
          </div>
          <div class="content-text">
            <?php 
              echo $email;
            ?> 
          </div> 
        </div>
        <div class="title-of-div">
          <div class="title-text">
            Verified Account
            <?php
              if ($_SESSION['id'] == $user_id && $verified == 0) {
                echo ' <a href="verify-account.php" id="button-user">verify</a>';
              }
            ?>
          </div>
          <div class="content-text">
            <?php 
              if ($verified == 0) {
                echo "The account is not verified";
              } else {
                echo "The account is verified";
              }
            ?> 
          </div> 
        </div>
      </div>
      <?php endwhile; ?> 
    </div> 
  </body>
</html>