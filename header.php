<?php
  $userid = $_SESSION['id'];
  $username = $_SESSION['username'];
  $result = mysqli_query($link, "SELECT id FROM users WHERE username = '$username'");
  if(mysqli_num_rows($result) == 0) {
    session_destroy();
    echo "<script>window.location = 'login.php'</script>";
    exit;
  } 
?>
<html>
<head>
  <style type="text/css">
    body {
      margin:0;
      padding:0;
    }
    #desktopul {
      list-style-type: none;
      margin: 0;
      padding: 0;
      overflow: hidden;
      background-color:#0080ff;
    }
    #desktopul li {
      float: left;
    }
    #desktopul li a {
      display: block;
      color: white;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
    }
    #desktopul li a:hover:not(.active) {
      background-color: #70bab7;
    }
    #desktopul .active {
      background-color: #003366;
    }
    #modal {
      position: fixed;
      z-index: 99;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%; 
      overflow: auto; 
      display: none;
      
      -webkit-backdrop-filter: blur(4px);
      backdrop-filter: blur(4px);
      background-color: rgba(186, 186, 186, 0.2);
    }
    .modal-content {
      z-index: 99;
      background-color: rgba(232, 232, 232, 0.9);
      border: none;
      position: fixed;
      bottom: 0;
      height: 100%;
      border-right: 2px solid transparent;
      width: 275px;
      transform: scaleX(0);    
      transform-origin: left;
      transition: transform 0.5s ease;
    }
    #phone-menu {
      margin: 0;
      padding: 25px;
    }
    #btns {
      text-decoration: none;
      color: black;
      font-size: 20px;
    }
    #phone-menu > li {
      list-style-type: none;
      margin-bottom: 10px;
      text-align: left;
    }
    #close {
      float: right;
      margin: 0;
      height: 20px;
      width: 20px;
      padding: 25px;
      transition: all 0.3s ease-in;
    }
    #close:hover, #close:focus {
      cursor: pointer;
    }
    #phone-nav {
      display: none;
    }
    #noInternet {
      text-align: center;
      background:lightgrey;
      position:absolute;
      align-items: center;
      justify-content: center;
      height: 100vh;
      display: flex;
      top: 0px;
      right: 0px;
      bottom: 0px;
      left: 0px;
      z-index: 99;
      font-size: 20px;
      transform: scale(0);
      transition: transform 1s;
    }
    @media (max-width: 800px) {
      #desktopul {
        display :none;
      }
      #phone-nav span {
        padding: 4px;
      }
      #phone-nav {
        display: flex;
      }
      #phone-nav {
        background-color: #0080ff;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 10px;
      }
      #show-options {
        position: absolute;
	      left: 25px;
        filter: invert(100%) sepia(0%) saturate(1%) hue-rotate(12deg) brightness(105%) contrast(101%);
      }
      #site-name {
        color: white;
      }
    }
  </style>
</head>
<body>
  <div id="noInternet"><h1>No Internet</h1></div>
  <?php
    $result = mysqli_query($link, "SELECT COUNT(*) FROM `notifications` WHERE userid=$userid");
    $row = mysqli_fetch_row($result)[0];
    
    $results = mysqli_query($link, "SELECT COUNT(*) FROM `tickets` WHERE userid=$userid");
    $rows = mysqli_fetch_row($results)[0];
  ?>
  <ul id="desktopul">
    <li><a class="active" href="home.php">Home Page (Messages)</a></li>
    <li><a href="profile.php?id=<?php echo $_SESSION['id']; ?>">My Account (<?php echo $_SESSION['username']?>)</a></li>
    <li><a href="notifications.php?id=<?php echo $_SESSION['id']; ?>">Notifications (<?php echo $row; ?>)</a></li>
    <li><a href="search.php">Search</a></li>
    <li><a href="contact.php">Contact</a></li>
    <?php if($_SESSION['admin'] != 0) echo '<li><a href="admin.php">Admin</a></li>'; ?>
    <li><a href="mytickets.php">My Tickets (<?php echo $rows; ?>)</a></li>
    <li style="float:right"><a class="active" href="logout.php">Logout</a></li>
  </ul>
  <div id="phone-nav">
    <img src="logos/menu.svg" id="show-options" height="30" width="30" />
    <span id="site-name">Live Chat</span>
  </div>
  <div id="modal"></div>
	<div class="modal-content">
		<img src="logos/close.svg" alt="Close" srcset="" id="close">
		<ul id="phone-menu">
			<li><a href="home.php" id="btns">Home Page</a></li>
			<li><a href="notifications.php?id=<?php echo $_SESSION['id']; ?>" id="btns">Notifications (<?php echo $row; ?>)</a></li>
			<li><a href="profile.php?id=<?php echo $_SESSION['id']; ?>" id="btns">My Account (<?php echo $_SESSION['username']?>)</a></li>
			<li><a href="search.php" id="btns">Search</a></li>
			<li><a href="contact.php" id="btns">Contact</a></li>
			<?php if($_SESSION['admin'] != 0) echo '<li><a href="admin.php" id="btns">Admin</a></li>'; ?>
			<li><a href="mytickets.php" id="btns">My Tickets (<?php echo $rows; ?>)</a></li>
			<li><a href="logout.php" id="btns">Logout</a></li>
		</ul>
	</div>
  <script>
    console.log('Initially ' + (window.navigator.onLine ? 'on' : 'off') + 'line');
    let internet = document.getElementById("noInternet");
    window.addEventListener('online', function() {
      internet.style.transform = "scale(0)";
      window.location.reload(false);
    });
    window.addEventListener('offline', function() {
      internet.style.transform = "scale(1)";
    });
    let modalContent = document.getElementsByClassName("modal-content")[0];
    let btn = document.getElementById("show-options");
    let span = document.getElementById("close");
    btn.onclick = function() {
        modalContent.style = "transform: scaleX(1)";
      modal.style = "display: inline";
    }
    span.onclick = function() {
      modalContent.style = "transform: scaleX(0)";
      modal.style = "display: none";
    }
  </script>
</body>
</html>