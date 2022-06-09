<?php

require_once 'connection.php';
require_once 'session-check.php';

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die("CONNECT ERROR!");
$db_select2 = mysqli_select_db($conn, DB_NAME) or die("DB ERROR!");

print_top_menu($username, $email);

$sql_list_id = "SELECT * FROM table_list WHERE username = '$username'";
$query_list_id = mysqli_query($conn, $sql_list_id);
print_list_id($query_list_id);

print_task_menu();

if (isset($_GET['list_id'])) {
    $list_id_url = $_GET['list_id'];
}

$sql_task_id = "SELECT * FROM table_task WHERE list_id=$list_id_url AND username='$username'";
$query_task_id = mysqli_query($conn, $sql_task_id);
print_task_table_by_id($query_task_id);

echo '</table></div></div></body></html>';

//functions

function print_top_menu($username, $email)
{
    echo <<<_END
        <html>
        <head>
            <title>Task Manager with PHP and MySQL</title>
            <link rel="stylesheet" href="style.css" />
        </head>
        <body>
            <div class="wrapper">
                <h1>TASK MANAGER</h1>
                <div class="tbl-profile">
                    Hi, $username
                    <br/>
                    Email: $email
                </div>

                <div class="menu">
                    <!-- <a href="index.html">Home</a> -->
                    <a href="index.php">Home </a>
_END;
}

function print_list_id($query)
{
    if ($query == true) {
        while ($row2 = mysqli_fetch_assoc($query)) {
            $list_id = $row2['list_id'];
            $list_name = $row2['list_name'];
            echo '<a href="list-task.php?list_id=' . $list_id . '">' . $list_name . '</a>';
        }
    }
}

function print_task_menu()
{
    echo <<<_END
<a href="manage-list.php"> Manage Lists</a>
<a class="btn-logout" href="logout.php">Log out</a>
</div>
<div class="all-task">
    <br/>
    <a class="btn-primary" href="add-task.php">Add Task</a>

    <table class="tbl-full">
        <tr>
            <th>S.N.</th>
            <th>Task Name</th>
            <th>Priority</th>
            <th>Deadline</th>
            <th>Actions</th>
        </tr>
_END;
}

function print_task_table_by_id($query)
{
    if ($query == true) {
        $count_rows = mysqli_num_rows($query);
        $sn = 1;
        if ($count_rows > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $task_id = $row['task_id'];
                $task_name = $row['task_name'];
                $priority = $row['priority'];
                $deadline = $row['deadline'];
                echo '<tr><td>' . $sn++ . '</td>';
                echo <<<_END
                <td>$task_name</td>
                <td>$priority</td>
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
        echo '<tr><td colspan="5">No Task Added on this list</td></tr>';
    }
}

$query_task_id->close();
$query_list_id->close();
$conn->close();
