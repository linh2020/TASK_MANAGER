<?php

/*  
 * Final Project: Task Manager
 * Team member: Linh Huynh, Huy Nguyen  
 * Date: 12/12/2021
 * 
 * sql query
 * 
 
CREATE DATABASE CS174837;
USE CS174837;

CREATE TABLE table_list
(
    list_id          int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    list_name        varchar(50)      NOT NULL,
    list_description varchar(150) DEFAULT NULL,
    username         varchar(32)      NOT NULL,
    PRIMARY KEY (list_id)
);

CREATE TABLE table_task
(
    task_id          int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    task_name        varchar(150)     NOT NULL,
    task_description text             NOT NULL,
    list_id          int(11)          NOT NULL,
    priority         varchar(10)      NOT NULL,
    status           varchar(20)      NOT NULL,
    deadline         date             NOT NULL,
    username         varchar(32)      NOT NULL,
    PRIMARY KEY (task_id)
);

CREATE TABLE users
(
    username varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    email    varchar(255) NOT NULL,
    PRIMARY KEY (username)
);

*/

require_once 'connection.php';
require_once 'session-check.php';

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die("Connection error!");
$db_select = mysqli_select_db($conn, DB_NAME) or die("Database does not exist!");

$sql_table_list = "SELECT * FROM table_list WHERE username = '$username'";
$query_table_list = mysqli_query($conn, $sql_table_list);

print_top_menu($username, $email);
print_list_id_name($query_table_list);
print_bottom_menu();
print_session_msg();
print_task_menu();

$sql_table_task = "SELECT * FROM table_task WHERE username = '$username'";
$query_table_task = mysqli_query($conn, $sql_table_task);
print_task_table($query_table_task);

//functions
function print_top_menu($username, $email)
{
    echo <<<_END
<html>
<head>
    <title>Task Manager with PHP and MySQL</title>
    <link rel="stylesheet" href="style.css" />
</head>
<div class="wrapper">
    <h1>TASK MANAGER</h1>
    <div class="tbl-profile">
        Hi, $username
        <br/>
        Email: $email
    </div>
    <div class="menu">
        <a href="index.php">Home</a> 
_END;
}

function print_session_msg()
{
    if (isset($_SESSION['add'])) {
        echo $_SESSION['add'];
        unset($_SESSION['add']);
    }

    if (isset($_SESSION['delete'])) {
        echo $_SESSION['delete'];
        unset($_SESSION['delete']);
    }

    if (isset($_SESSION['update'])) {
        echo $_SESSION['update'];
        unset($_SESSION['update']);
    }

    if (isset($_SESSION['delete_fail'])) {
        echo $_SESSION['delete_fail'];
        unset($_SESSION['delete_fail']);
    }
}

function print_list_id_name($query)
{
    if ($query == true) {
        while ($row2 = mysqli_fetch_assoc($query)) {
            $list_id = $row2['list_id'];
            $list_name = $row2['list_name'];
            echo '<a href="list-task.php?list_id=' . $list_id . '">' . $list_name . '</a>';
        }
    }
}

function print_bottom_menu()
{
    echo <<<_END
    <a href="manage-list.php">Manage Lists</a>    
    <a class="btn-logout" href="logout.php">Log out</a>
</div>
_END;
}

function print_task_menu()
{
    echo <<<_END
<div class="all-tasks">
    <br/>
    <a class="btn-primary" href="add-task.php">Add Task</a>
    <table class="tbl-full">
        <tr>
            <th>S.N.</th>
            <th>Task Name</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Deadline</th>
            <th>Actions</th>
        </tr> 
_END;
}

function print_task_table($query)
{
    if ($query == true) {
        $count_rows = mysqli_num_rows($query);
        $sn = 1;
        if ($count_rows > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $task_id = $row['task_id'];
                $task_name = $row['task_name'];
                $priority = $row['priority'];
                $status = $row['status'];
                $deadline = $row['deadline'];

                echo "<tr>";
                echo "<td>" . $sn++ . "</td>";
                echo <<<_END
                    <td>$task_name</td>
                    <td>$priority</td>
                    <td>$status</td>
                    <td>$deadline</td>
                    <td>
                        <a href="update-task.php?task_id=$task_id">Update</a>
                        <a href="delete-task.php?task_id=$task_id">Delete</a>
                    </td>
                </tr>
                _END;
            }
        }
    } else {
        echo <<<_END
                        <tr>
                            <td colspan="5">No Task Added Yet</td>
                        </tr>
                    </table>
                </div>
            </div>
        _END;
    }
}

$query_table_list->close();
$query_table_task->close();
$conn->close();
