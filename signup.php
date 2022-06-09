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

css();
echo "<br><br>";
print_table();

$connection = new mysqli(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($connection->connect_error) die("Connection Failed!");

// get data from user
if (
    isset($_POST['username']) &&
    isset($_POST['password']) &&
    isset($_POST['email'])
) {
    if (
        check_string($_POST['username']) == 1
        || !$_POST['password']
        || !$_POST['email']

    ) {
        echo "<p style='text-align:center'>All fields must be filled in!</p><br>";
    } else {
        $username = sanitizeMySQL($connection, 'username');
        $password = sanitizeMySQL($connection, 'password');
        $encryted_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = sanitizeMySQL($connection, 'email');

        add_user($connection, $username, $encryted_password, $email);
    }
} else {
    //echo "Name and Content must be filled!";
}

$connection->close();

// functions
function sanitizeString($var)
{
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}
function sanitizeMySQL($connection, $var)
{
    $var = $connection->real_escape_string($_POST[$var]);
    $var = sanitizeString($var);
    return $var;
}

function check_string($str)
{
    $regex = preg_match('/[@_!#$%^&*()<>?\/|}{~:]/i', $str);
    if ($str == '' || $regex == 1)
        return 1;
    else
        return 0;
}

function add_user($connection, $username, $password, $email)
{
    $query = "INSERT INTO users VALUES('$username', '$password', '$email')";
    $result = $connection->query($query);
    if (!$result) die("<p style='text-align:center'>Account Creation Failed.</p>");
    else echo "<p style='text-align:center'>Account Created!</p>";
}

function css()
{

    echo <<<_END
<style>
table,
th,
td {
    border: 1px solid black;
}

table {
    text-align: center;
    border-collapse: collapse;
    width: 350px;
    font-family: monospace;
    font-size: 14px;
    text-align: left;
    margin-left: auto;
    margin-right: auto;
}

th {
    background-color: #3867d6;
    color: white;
    text-align: center;
}

#id,
#name {
    text-align: center;
}

tr:nth-child(even) {
    background-color: #f2f2f2
}
</style>
_END;
}

function print_table()
{
    echo <<<_END

    <html>
        <head>
            <title>Task Manager with PHP and MySQL</title>
            <link rel="stylesheet" href="style.css" />
        </head>
            <div class="wrapper">
                <h1>TASK MANAGER</h1>

<form id = "signup" class = "form" action="signup.php" method="post"  enctype="multipart/form-data">
<table>
<tr>
    <th colspan="2">Sign Up</th>
</tr>
<tr>
    <td>Username:</td>
    <td><input type="text" name ="username" id ="username"><br><small></small></td>
</tr>
<tr>
    <td>Password:</td>
    <td><input type="password" name ="password" id ="password"><br><small></small></td>
    
</tr>
<tr>
    <td>Email:</td>
    <td><input type="text" name ="email" id ="email"><br><small></small></td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input type="submit" name ="submit" value="Sign Up">
    </td>
</tr>
<tr>
    <td colspan="2 " align="center">
        Already a member? <a href="login.php">Log In</a>
    </td>
</tr>
</table>
</form>
<script type="text/javascript" src="signup.js"></script>
_END;
}
