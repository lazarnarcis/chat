<?php
    require "config/config.php";
    $sql = "SELECT * FROM users WHERE logged=1 AND admin=1";
    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $admin_username = $row['username'];
            $admin_file = $row['file'];
            $admin_id = $row['id'];
            ?>
                <div class='admin-card' onclick='window.location="profile.php?id=<?php echo $admin_id ?>";'>
                    <img src='<?php echo $admin_file; ?>' id='admin-photo' alt='profile picture'>
                    <p id='admin-name'><?php echo $admin_username; ?></p>
                </div>
            <?php
        }
    } else {
        echo "No admins!";
    }
    mysqli_close($link);
?>