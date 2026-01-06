<?php 
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'naivas-db';

$conn = new mysqli($hostname,$username,$password,$dbname);
 if($conn){
    echo 'database connected';
 }

?>