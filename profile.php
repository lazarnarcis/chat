<?php
  session_start();
  require 'config.php';
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
  } else if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
  } else {
    $id = $_GET['id'];
  }
  $queryString = "SELECT id, username, email, created_at, email, bio, phone, file, admin, founder, banned,ip, last_ip, logged FROM users WHERE id='$id' ORDER BY username DESC LIMIT 1"; 
  $query = $link->prepare($queryString);
  $query->execute();
  $query->store_result();
  $query->bind_result($user_id, $username, $email, $created_at, $email, $bio, $phone, $file, $admin, $founder, $banned, $ip, $last_ip, $logged);
?>
<!DOCTYPE html>
<html> 
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
  <title><?php echo $_SESSION['username']; ?>'s profile</title>
  <link rel="shortcut icon" href="logos/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <style type="text/css">
    .wrapper { 
      margin: 20px;
    }
    body {
      font-size: 18px;
    }
    #bio {
      font-family: arial;
    }
    #admin {
      padding: 5px;
      background-color: none;
      opacity: 50%;
      user-select: none;
      border: 1px solid blue;
      border-radius: 5px;
      color: blue;
      transition: .3s;
    }
    #admin:hover, #founder:hover, #banned:hover {
      text-decoration: underline;
      cursor:pointer;
    }
    #admin:hover {
      background-color: lightblue;
    }
    #founder:hover {
      background-color: #ff8a8a;
    }
    #founder {
      transition: .3s;
      padding: 5px;
      background-color: none;
      opacity: 50%;
      user-select: none;
      border: 1px solid red;
      border-radius: 5px;
      color: red;
    }
    #banned:hover {
      background-color: #b3ffb8;
    }
    #banned {
      transition: .3s;
      padding: 5px;
      background-color: none;
      opacity: 50%;
      user-select: none;
      border: 1px solid green;
      border-radius: 5px;
      color: green;
    }
    .title-text {
      padding: 10px;
      font-weight: bold;
      color: black;
    }
    .title-of-div {
      background-color: #99c4d1;
      border-radius: 20px;
    }
    .content-text {
      background: white;
      padding: 10px;
      color: black;
      border: 1px solid #99c4d1;
      border-radius: 20px;
    }
    h1 {
      text-shadow: 0px 0px 2.5px grey;
      margin: 0;
    }
    #online, #offline {
      padding: 5px;
      border-radius: 25px;
      color: white;
      height: 20px;
      margin-top: 10px;
      margin-left: 10px;
    }
    #online {
      background-color: lightgreen;
    }
    #offline {
      background-color: blueviolet;
    }
    @media only screen and (max-width: 1000px) {
      * {
      text-align: center;
      }
    }
    #img_div:after {
      content: "";
      display: block;
      clear: both;
    }
    #image-x {
      margin: 5px;
      width: 175px;
      border-radius: 100px;
      height: 175px;
      transition: .4s;
    }
    #image-x:hover {
      opacity: 75%;
      border-radius: 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <?php include_once("header.php"); ?>
  <div class="wrapper">
    <?php while ($query->fetch()) { ?>
    <?php
      echo "<div id='img_div'>";
      ?>
        <a href='images/<?php echo $file; ?>' target="_blank"><img id="image-x" src='images/<?php echo $file; ?>' alt="Profile Photo"></a><br/>
      <div style="display: flex;">
        <h1>
          <?php 
            echo $username; 
          ?>
        </h1>
        <?php
          if ($logged == 1) {
            echo "<span id='online'>Online</span>";
          } else if ($logged == 0) {
            echo "<span id='offline'>Offline</span>";
          }
        ?>
      </div>
      <p id="bio">
          <?php 
            if ($bio == "") {
              echo '';
            } else {
              echo $bio;
            }
          ?>
      </p>
      <?php
      if ($_SESSION['id'] == $user_id) {
        echo " <span>[<a href='change-photo.php' id='edits'>change photo</a>]</span><br/><br/>";
      }
      echo "</div>";
    ?>
    <?php
      if ($admin == 1) {
        echo ' <a href="admins.php"><span id="admin">Admin Chat</span></a>';
      }
      if ($founder == 1) {
        echo ' <a href="founders.php"><span id="founder">Founder Chat</span></a>';
      }
      if ($banned == 1) {
        echo ' <a href="banned.php"><span id="banned">Banned</span></a>';
      }
      if ($admin == 1 && $founder == 1 && $banned == 1) {
        echo '<br><br>';
      } else if ($admin == 1) {
        echo '<br><br>';
      } else if ($founder == 1) {
        echo '<br><br>';
      } else if ($banned == 1) {
        echo '<br><br>';
      }
    ?>
    <div class="title-of-div">
    <div class="title-text">Username
      <?php
        if ($_SESSION['id'] == $user_id) {
          echo '<span>[<a href="change-name.php" id="edits">edit</a>]</span>';
        }
      ?>
    </div>
    </div>
    <br>
    <?php
      if ($_SESSION['id'] == $user_id) {
        ?>
        <div class="title-of-div">
        <div class="title-text">Bio
          <?php
            if ($bio != "") {
              ?>
              [<a href="delete-bio.php" id="edits">delete bio</a>]
              <?php
            }
          ?>
          [<a href="change-bio.php" id="edits">edit</a>]
        </div>
      </div> <br/>
        <?php
      }
    ?>
    <div class="title-of-div">
      <div class="title-text">Account ID</div> 
      <div class="content-text">#<?php echo $user_id; ?></div>
    </div>
      <br>
    <?php
      if ($_SESSION['founder'] == 1 && $_SESSION['id'] != $user_id) {
        if ($admin == 0) {
          ?>
            <div class="title-of-div">
              <div class="title-text">Admin [<a href="make-admin.php?id=<?php echo $user_id; ?>" id="edits">make admin</a>]</div>
              <div class="content-text">
                <?php
                  if ($admin == 0) {
                    echo "No";
                  }
                ?>
              </div>
            </div><br>
          <?php
        } else if ($admin == 1) {
          ?>
            <div class="title-of-div">
              <div class="title-text">Admin [<a href="remove-admin.php?id=<?php echo $user_id; ?>" id="edits">remove admin</a>]</div>
              <div class="content-text">
                <?php
                  if ($admin == 1) {
                    echo "Yes";
                  }
                ?>
              </div>
            </div><br>
          <?php
        }
      }
      if ($_SESSION['admin'] == 1 && $_SESSION['id'] != $user_id) {
        if ($banned == 0) {
          ?>
            <div class="title-of-div">
              <div class="title-text">Banned [<a href="ban.php?id=<?php echo $user_id; ?>" id="edits">ban user</a>]</div>
              <div class="content-text">
                <?php
                  if ($banned == 0) {
                    echo "No";
                  }
                ?>
              </div>
            </div><br>
          <?php
        } else if ($banned == 1) {
          ?>
            <div class="title-of-div">
              <div class="title-text">Banned [<a href="unban.php?id=<?php echo $user_id; ?>" id="edits">unban user</a>]</div>
              <div class="content-text">
                <?php
                  if ($banned == 1) {
                    echo "Yes";
                  }
                ?>
              </div>
            </div><br>
          <?php
        }
      }
    ?>
    <?php 
      if ($_SESSION['admin'] == 1) {
        ?>
        <div class="title-of-div">
        <div class="title-text">Notifications [<a href="notifications.php?id=<?php echo $user_id; ?>">show</a>]</div>
        </div>
        <br/>
        <?php
      }
    ?>
    <div class="title-of-div">
      <div class="title-text">Account has been created at</div>
      <div class="content-text"><?php 
        echo $created_at; 
        if ($_SESSION['founder'] == 1) {
          echo " (account created with IP: $ip)";
        }
      ?></div>
    </div>
      <br>
    <?php
      if ($_SESSION['founder'] == 1 || $_SESSION['id'] == $user_id) {
        ?>
          <div class="title-of-div">
          <div class="title-text">Last IP</div>
          <div class="content-text"><?php 
            echo $last_ip; 
          ?></div>
        </div>
          <br>
        <?php
      }
    ?>
    <?php 
      if ($_SESSION['id'] == $user_id) {
        ?>
        <div class="title-of-div">
        <div class="title-text">Password [<a href="reset-password.php" id="edits">change password</a>]</div>
        <div class="content-text">********</div> 
        </div>
        <br/>
        <?php
      }
    ?>
    <?php
      if ($_SESSION['id'] == $user_id) {
        ?>
        <div class="title-of-div">
        <div class="title-text">Phone Number
          <?php
            if ($phone != 0) {
              echo '[<a href="delete-phone.php" id="edits">delete phone</a>]';
            }
          ?>
          [<a href="change-phone.php" id="edits">edit</a>]
        </div>
        <div class="content-text">
          <?php 
            if ($phone == 0) {
              echo 'No';
            } else {
              echo $phone;
            }
          ?>
          </div>
      </div> <br/>
        <?php
      }
    ?>
    <div class="title-of-div">
    <div class="title-text">Email
      <?php
        if ($_SESSION['id'] != $user_id) {
          echo ' [<a href="mailto:<?php echo $email; ?>" id="edits">send a email</a>]';
        }
        if ($_SESSION['id'] == $user_id) {
          echo ' [<a href="change-email.php" id="edits">edit</a>]';
        }
      ?>
    </div>
      <div class="content-text">
        <?php 
          echo $email;
        ?> 
      </div> 
    </div>
    <br>
    <?php } ?> 
  </div> 
</body>
</html>