<?php

require_once 'connection.php';
require_once 'session-check.php';

print_top_menu();
print_session_msg();
print_form_add_task();

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die("CONNECT ERROR!");
$db_select = mysqli_select_db($conn, DB_NAME) or die("SELECT DB ERROR!");

$sql_table_list = "SELECT * FROM table_list WHERE username='$username'";
$query_table_list = mysqli_query($conn, $sql_table_list);

print_drop_box_table_list($query_table_list);
print_add_task_table();

if (isset($_POST['submit'])) {

    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $list_id = $_POST['list_id'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];

    $sql2 = "INSERT INTO table_task SET
                task_name = '$task_name',
                task_description = '$task_description',
                list_id = $list_id,
                priority = '$priority',
                status = '$status',
                deadline = '$deadline',
                username = '$username'";

    $res2 = mysqli_query($conn, $sql2);
    if ($res2 == true) {
        $_SESSION['add'] = 'Task Added Successfully';
        header('location:index.php');
    } else {
        $_SESSION['add_fail'] = 'Fail to add Task';
        header('location:add-task.php');
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
            <a class="btn-secondary" href="index.php">Home</a>
            <h3>Add Task Page</h3>      
    </body>
_END;
}

function print_session_msg()
{
    if (isset($_SESSION['add_fail'])) {
        echo $_SESSION['add_fail'];
        unset($_SESSION['add_fail']);
    }
}

function print_form_add_task()
{
    echo <<<_END
    <form method="POST" action="">
        <table class="tbl-half">
            <tr>
                <td>Task Name:</td>
                <td><input type="text" name="task_name" placeholder="Type your task name" required="required"></td>
            </tr>
    
            <tr>
                <td>Task Description:</td>
                <td><textarea name="task_description" placeholder="Type Task Description"></textarea></td>
            </tr>
    
            <tr>
                <td>Select List:</td>
                <td>
                    <select name="list_id">
    
    _END;
}

function print_drop_box_table_list($query)
{
    if ($query == true) {
        $count_rows = mysqli_num_rows($query);
        if ($count_rows > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $list_id = $row['list_id'];
                $list_name = $row['list_name'];
                echo '<option value="' . $list_id . '">' . $list_name . '</option>';
            }
        } else {
            echo '<option value="0">None</option>';
        }
    }
}

function print_add_task_table()
{
    echo <<<_END
                </select>
                    </td>   
                    </tr>
                    
                    <tr>
                        <td>Priority:</td>
                        <td>
                            <select name="priority">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Status:</td>
                        <td>
                            <select name="status">
                                <option value="Be started">Be started</option>
                                <option value="In progress">In progress</option>
                                <option value="Completed">Completed</option>          
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>Deadline: </td>
                        <td><input type="date" name="deadline"></td>
                    </tr>

                    <tr>
                        <td><input class="btn-primary btn-lg" type="submit" name="submit" value="SAVE"></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>
_END;
}
$query_table_list->close();
$conn->close();
