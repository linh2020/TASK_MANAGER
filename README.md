
/*  
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
