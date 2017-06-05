<?php
       $error = array();
        $host = "admpns.com";
    $username = "admpns"; 
$password = "Vmarvia17";
$databaseName = "admpns";

$DBConnect = new mysqli($host, $username , $password, $databaseName);

if($DBConnect->connect_error){
    $error[] = "Server not available";
}
?>

