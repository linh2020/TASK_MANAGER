<?php

require_once 'connection.php';
require_once 'session-check.php';

print_msg();
destroy_session_and_data();

function destroy_session_and_data()
{
  $_SESSION = array();
  setcookie(session_name(), '', time() - 2592000, '/');
  session_destroy();
}

function print_msg()
{
  echo <<<_END
    <html>
      <head>
        <title>Task Manager with PHP and MySQL</title>
        <link rel="stylesheet" href="style.css" />
      </head>
        <div class="wrapper center">
          <h1>TASK MANAGER</h1>
          <h2>You've been logged out</h1><br/>
          <a href="login.php">Click here to login</a>        
        </div>
_END;
}
