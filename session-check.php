<?php
session_start();

$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
if ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    // destroy_session_and_data();
    $_SESSION = array();    // Delete all the information in the array
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    // header('location:index.php');
} else {
    header('location:login.php');
}
