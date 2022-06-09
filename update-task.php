<?php

require_once 'connection.php';
require_once 'session-check.php';

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die("CONNECT ERROR!");
$db_select = mysqli_select_db($conn, DB_NAME) or die("DB ERROR!");

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $sql_table_task = "SELECT * FROM table_task where task_id ='$task_id'";
    $query_table_task = mysqli_query($conn, $sql_table_task);

    if ($query_table_task == true) {
        $row = mysqli_fetch_assoc($query_table_task);
        $task_name = $row['task_name'];
        $task_description = $row['task_description'];
        $list_id = $row['list_id'];
        $priority = $row['priority'];
        $status = $row['status'];
        $deadline = $row['deadline'];
    } else {
        header('location:index.php');
    }
}

print_top_menu();
print_session_msg();
content_task_name_description($task_name, $task_description);

$sql_table_list = "SELECT * FROM table_list WHERE username='$username'";
$query_table_list = mysqli_query($conn, $sql_table_list);
print_table_list_by_list_id($query_table_list, $list_id);
content($priority, $status, $deadline);

if (isset($_POST['submit'])) {
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $list_id = $_POST['list_id'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];

    $sql_table_task_update = "UPDATE table_task SET
                task_name = '$task_name',
                task_description = '$task_description',
                list_id = '$list_id',
                priority = '$priority',
                status = '$status',
                deadline = '$deadline'
                WHERE task_id = $task_id";

    $query_table_task_update = mysqli_query($conn, $sql_table_task_update);
    if ($sql_table_task_update == true) {
        $_SESSION['update'] = "Task Updated Successfully";
        header('location:index.php');
    } else {
        $_SESSION['update_fail'] = "Failed to Update Task";
        header('location: update-task.php?task_id=' . $task_id);
    }
}

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
        <p><a class="btn-secondary" href="index.php">Home</a></p>
        <h3>Update Task Page</h3>
_END;
}

function print_session_msg()
{
    if (isset($_SESSION['update_fail'])) {
        echo $_SESSION['update_fail'];
        unset($_SESSION['update_fail']);
    }
}

function content_task_name_description($task_name, $task_description)
{
    echo <<<_END
<form action="" method="POST">
    <table class="tbl-half">
        <tr>
            <td>Task Name:</td>
            <td><input type="text" name="task_name" value="$task_name" required="required"></td>
        </tr>

        <tr>
            <td>Task Description:</td>
            <td><textarea name="task_description">$task_description</textarea></td>
        </tr>

        <tr>
            <td>Select List:</td>
            <td>
                <select name="list_id">
_END;
}

function content($priority, $status, $deadline)
{
    echo <<<_END
    </select>
        </td>
        </tr>
        <tr>
            <td>Priority:</td>
            <td>
    <select name="priority">        
    _END;

    if ($priority == "High") {
        echo <<<_END
        <option selected="selected" value="High">High</option>
        <option value="Medium">Medium</option>
        <option value="Low">Low</option>
        _END;
    } elseif ($priority == "Medium") {
        echo <<<_END
        <option value="High">High</option>
        <option selected="selected" value="Medium">Medium</option>
        <option value="Low">Low</option>
        _END;
    } else {
        echo <<<_END
        <option value="High">High</option>
        <option value="Medium">Medium</option>
        <option selected="selected"value="Low">Low</option>
        _END;
    }

    echo <<<_END
                            </select>
                        </td>
                    </tr>
                            </select>
                        </td>
                    </tr>
    
                    <tr>
                        <td>Status:</td>
                        <td>
                            <select name="status">       
    _END;

    if ($status == "Be started") {
        echo <<<_END
        <option selected="selected" value="Be started">Be started</option>
        <option value="In progress">In progress</option>
        <option value="Completed">Completed</option>
        _END;
    } elseif ($status == "In progress") {
        echo <<<_END
        <option value="Be started">Be started</option>
        <option selected="selected" value="In progress">In progress</option>
        <option value="Completed">Completed</option>
        _END;
    } else {
        echo <<<_END
        <option value="Be started">Be started</option>
        <option value="In progress">In progress</option>
        <option selected="selected" value="Completed">Completed</option>
        _END;
    }

    echo <<<_END
                            </select>
                        </td>
                    </tr>
    
                    <tr>
                        <td>Deadline: </td>
                        <td><input type="date" name="deadline" value="$deadline"></td>
                    </tr>
                    <tr>
                        <td><input class="btn-primary" type="submit" name="submit" value="UPDATE"></td>
                    </tr>
                </table>
                </form>
            </div>
        </body>
    </html>    
    _END;
}

function print_table_list_by_list_id($query, $list_id)
{
    if ($query == true) {
        $count_row2 = mysqli_num_rows($query);
        if ($count_row2 > 0) {
            while ($row2 = mysqli_fetch_assoc($query)) {
                $list_id_db = $row2['list_id'];
                $list_name = $row2['list_name'];

                if ($list_id_db == $list_id) {
                    echo '<option selected="selected" value="' . $list_id_db . '">' . $list_name . '</option>';
                } elseif ($list_id_db != $list_id) {
                    echo '<option value="' . $list_id_db . '">' . $list_name . '</option>';
                } elseif ($list_id == 0) {
                    echo '<option selected="selected" value="0">None</option>';
                }
            }
        }
    }
}
