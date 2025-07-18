<?php
$host = "localhost";
$user = "jit";
$pass = "1234";
$db_name = "final_project";


$conn = mysqli_connect($host,$user,$pass,$db_name);

if(mysqli_connect_error())
{
    echo mysqli_connect_error();
    exit;

}

?>