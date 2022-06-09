<?php
require_once 'connection.php';
require_once 'session-check.php';

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die("CONNECT ERROR!");
$db_select = mysqli_select_db($conn, DB_NAME) or die("DB ERROR!");

print_top_menu();
print_session_msg();
print_form_add_list();

if (isset($_POST['submit'])) {
    $list_name = $_POST['list_name'];
    $list_description = $_POST['list_description'];
    $sql = "INSERT INTO table_list SET
                list_name = '$list_name',
                list_description = '$list_description',
                username = '$username'";

    $res = mysqli_query($conn, $sql);
    if ($res == true) {
        $_SESSION['add'] = "List Added Successfully";
        header('location:manage-list.php');
    } else {
        $_SESSION['add_fail'] = "Failed to Add List";
        header('location:add-list.php');
    }
    $res->close();
    $conn->close();
}

//functions

function print_session_msg()
{
    if (isset($_SESSION['add_fail'])) {
        echo $_SESSION['add_fail'];
        unset($_SESSION['add_fail']);
    }
}

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
            <a class="btn-secondary" href="manage-list.php">Manage Lists</a>
            <h3>Add List Page</h3>        
    _END;
}

function print_form_add_list()
{
    echo <<<_END
    <form method="POST" action="">
        <table class="tbl-half">
            <tr>
                <td>List Name:</td>
                <td><input type="text" name="list_name" placeholder="Type list name here" required="required"></td>
            </tr>

            <tr>
                <td>List Description</td>
                <td><textarea name="list_description" placeholder="Type List Description Here"></textarea></td>
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
