<?php 
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }
    include("classes/connect.php");
    include("classes/login.php");
    include("classes/generate_number.php");
    
     ?> 