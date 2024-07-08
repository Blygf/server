<?php
require_once("classes/autoload.php");
$DB = new Database();
$query = "SELECT challengeid FROM completed where challengeid = 69";
$checked_result = $DB->read($query);
print_r($checked_result[0]['challengeid']);