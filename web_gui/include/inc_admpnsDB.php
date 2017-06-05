<?php
$host = "admpns.com";
$user = "admpns.com";
$pass = "Vmarvia17";
$db = "admpns";
$DBConnect = new mysqli($host, $user, $pass, $db);
if(!$DBConnect){
    $errorMsg[] = "The database server is not available.";
}