<?php
session_start();
include("classes/autoload.php");

$login = new Login();

// Verify session token using check_login method
$login->check_login($_SESSION['session_token'] ?? null);

$email = "hugo@volny.sk";
$query = "select userid from users where email = '$email' limit 1;";
$DB = new Database();
print_r($DB->read($query)[0]['userid']);
