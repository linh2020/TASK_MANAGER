<?php

require_once 'connection.php';
require_once 'session-check.php';

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die("CONNECT ERROR!");
$db_select = mysqli_select_db($conn, DB_NAME) or die("SELECT DB ERROR!");

print_top_menu();
print_session_msg();
print_manage_menu();

$sql_list_id = "SELECT * FROM table_list WHERE username = '$username'";
$query_list_id = mysqli_query($conn, $sql_list_id);
print_table_list_id($query_list_id);

// functions
function print_top_menu()
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
            <a class="btn-secondary" href="index.php">HOME</a>
            <h3>Manage Lists Page</h3>            
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

    if (isset($_SESSION['delete_fail'])) {
        echo $_SESSION['delete_fail'];
        unset($_SESSION['delete_fail']);
    }

    if (isset($_SESSION['update'])) {
        echo $_SESSION['update'];
        unset($_SESSION['update']);
    }
}

function print_manage_menu()
{
    echo <<<_END
    <div class="all-lists">
        <br>
        <a class="btn-primary" href="add-list.php">Add List</a>
    
        <table class="tbl-half">
            <tr>
                <th>S.N.</th>
                <th>List Name</th>
                <th>Actions</th>
            </tr>
    _END;
}

function print_table_list_id($query)
{
    if ($query == true) {
        $count_rows = mysqli_num_rows($query);
        $sn = 1;
        if ($count_rows > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $list_id = $row['list_id'];
                $list_name = $row['list_name'];
                echo '<tr><td>' . $sn++ . '</td>';
                echo <<<_END
                        <td>$list_name</td>
                        <td>
                            <a href="update-list.php?list_id=$list_id">Update</a>
                            <a href="delete-list.php?list_id=$list_id">Delete</a>
                        </td>
                    </tr>
                _END;
            }
        } else {
            echo '<tr><td colspan="3">No List Added Yet.</td></tr>';
        }
    } else {
        // $_SESSION['add_fail'] = "Failed to Add List";
        // header('location:' . SITEURL . 'add-list.php');
    }
    echo '</table></div></div></body></html>';
}

$query_list_id->close();
$conn->close();
