<?php
include 'config.php';
if(isset($_REQUEST["find"])){
    $sql = "SELECT * FROM users WHERE username LIKE ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $param_term);
        $param_term = $_REQUEST["find"] . '%';
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    ?>  
                        <div id="noFound" onclick="window.location='profile.php?id=<?php echo $row['id'] ?>';">
                            <img src='<?php echo $row['file']; ?>' id="imgUser" height="30" width="30">
                            <span id="linkToProfile"><?php echo $row["username"] ?></span></a> 
                        </div>
                    <?php
                }
            } else{
                echo "<div id='noFound'>No users found!</div>";
            }
        } else{
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($link);
?>