<?php

require_once 'connection.php';
require_once 'session-check.php';

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die("CONNECT ERROR!");
$db_select = mysqli_select_db($conn, DB_NAME) or die("SELECT DB ERROR!");

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    $sql = "DELETE FROM table_task WHERE task_id =$task_id";
    $res = mysqli_query($conn, $sql);
    if ($res == true) {
        $_SESSION['delete'] = "Task Deleted Successfully";
        header('location:index.php');
    } else {
        $_SESSION['delete_fail'] = "Failed to Delete Task";
        header('location:index.php');
    }
} else {
    header('location:index.php');
}

$res->close();
$conn->close();
