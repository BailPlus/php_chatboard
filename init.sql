CREATE USER 'php_chatboard'@'localhost' IDENTIFIED BY 'password';
CREATE DATABASE php_chatboard;
GRANT ALL ON php_chatboard.* TO 'php_chatboard'@'localhost';
USE php_chatboard;

CREATE TABLE users (
    `uid` VARCHAR(100) NOT NULL PRIMARY KEY,
    `obj` TEXT NOT NULL
);
INSERT INTO users (uid,obj) VALUES ('root','O:4:"User":5:{s:3:"uid";s:4:"root";s:3:"psw";s:64:"4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2";s:9:"headphoto";s:26:"/img/headphoto/default.svg";s:7:"friends";a:0:{}s:7:"isadmin";b:1;}');

CREATE TABLE msg (
    `msgid` VARCHAR(15) PRIMARY KEY NOT NULL,
    `obj` LONGTEXT NOT NULL
);

CREATE TABLE chatroom (
    `roomid` VARCHAR(15) PRIMARY KEY NOT NULL,
    `obj` TEXT NOT NULL
);

CREATE TABLE refresh_tokens (
    `tokenid` VARCHAR(32) PRIMARY KEY NOT NULL,
    `obj` TEXT NOT NULL
);
