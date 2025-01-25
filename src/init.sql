CREATE USER 'php_chatboard'@'localhost' IDENTIFIED BY 'password';
CREATE DATABASE php_chatboard;
GRANT ALL ON php_chatboard.* TO 'php_chatboard'@'localhost';
USE php_chatboard;

CREATE TABLE users (
    `uid` VARCHAR(100) NOT NULL PRIMARY KEY,
    `obj` TEXT NOT NULL
);

CREATE TABLE msg (
    `msgid` VARCHAR(15) PRIMARY KEY NOT NULL,
    `obj` LONGTEXT NOT NULL
);

CREATE TABLE chatroom (
    `roomid` VARCHAR(15) PRIMARY KEY NOT NULL,
    `obj` TEXT NOT NULL
);
