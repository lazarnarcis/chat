<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }
    require 'config.php';
    $result = array();
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
        $message = str_replace('<br />', PHP_EOL, $message);
        $message = str_replace("'", "\'", $message);
        $message = strip_tags($message);
        $message = displayTextWithLinks($message);
        $sql = "INSERT INTO chat (`message`, `name`, `admin`, `userid`, `file`, `founder`) VALUES ('".$message."', '".$_SESSION['username']."', '".$admin."', '".$id."', '".$file."', '".$founder."')";
        $result['send_status'] = $link->query($sql);
    }
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $items= $link->query("SELECT * FROM `chat` WHERE `id` > " . $start);
    while($row = mysqli_fetch_assoc($items)) {
        $result['items'][] = $row;
    }
    $link->close();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode($result);
    function displayTextWithLinks($s) {
        return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank" id="link-by-user">$1</a>', $s);
    }
?>