<?php
session_start(); // start the session
session_destroy(); // destroy the session
header("Location: homepage.php"); // redirect the user to the login page
session_start();
$_SESSION['success'] = ['value' => 'Successfully Logged Out!', 'timestamp' => time()];
exit(); // exit the script
