<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['invalid'] = ['value' => '❗Please login First', 'timestamp' => time()];
    header('Location: log_reg.php');
} else {
    include 'cartSettings.php';
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HandCrafted:Payment</title>
</head>

<body>
    <div class="cont-left">
        <?php if (isset($_SESSION['user_id'])) { ?>
            <h2>Welcome, <?php $_SESSION['logged_user'] ?></h2>
        <?php } else { ?>
            <a href="log_reg.php">
                <button class="btn">
                    <span class="button_top">
                        Login Now!
                    </span>
                </button>
            </a>

        <?php
        } ?>
    </div>
    <div class="cont-right">

    </div>
</body>

</html>