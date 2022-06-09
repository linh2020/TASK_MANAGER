<?php

require_once 'connection.php';
require_once 'session-check.php';

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die("CONNECT ERROR!");
$db_select = mysqli_select_db($conn, DB_NAME) or die("DB ERROR!");

print_top_menu();
print_session_msg();

if (isset($_GET['list_id'])) {
    $list_id = $_GET['list_id'];

    $sql_table_list = "SELECT * FROM table_List where list_id ='$list_id'";
    $query_table_list = mysqli_query($conn, $sql_table_list);
    if ($query_table_list == true) {
        $row = mysqli_fetch_assoc($query_table_list);
        $list_name = $row['list_name'];
        $list_description = $row['list_description'];
    } else {
        header('location:manage-list.php');
    }
}

content_list_name_description($list_name, $list_description);

if (isset($_POST['submit'])) {
    $list_name = $_POST['list_name'];
    $list_description = $_POST['list_description'];
    $sql_table_list_update = "UPDATE table_list SET
                list_name = '$list_name',
                list_description ='$list_description'
                WHERE list_id = $list_id";

    $query_table_task_update = mysqli_query($conn, $sql_table_list_update);
    if ($query_table_task_update == true) {
        $_SESSION['update'] = "List Updated Successfully";
        header('location: manage-list.php');
    } else {
        $_SESSION['update_fail'] = "Failed to Update List";
        header('location:update-list.php?list_id=' . $list_id);
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
            <a class="btn-secondary" href="index.php">Home</a>
            <a class="btn-secondary" href="manage-list.php">Manage List</a>
            <h3>Update List Page</h3>
    _END;
}

function print_session_msg()
{
    if (isset($_SESSION['update_fail'])) {
        echo $_SESSION['update_fail'];
        unset($_SESSION['update_fail']);
    }
}

function content_list_name_description($list_name, $list_description)
{
    echo <<<_END
                <form action="" method="POST">
                <table class="tbl-half">
                    <tr>
                        <td>List Name:</td>
                        <td><input type="text" name="list_name" value="$list_name" required="required"></td>
                    </tr>

                    <tr>
                        <td>List Description:</td>
                        <td>
                        <textarea name="list_description">
                            $list_description
                        </textarea></td>
                    </tr>

                    <tr>
                        <td><input class="btn-primary btn-lg" type="submit" name="submit" value="Update"></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>
_END;
}
