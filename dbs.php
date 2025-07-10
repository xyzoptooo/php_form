<?php

$host="localhost";
$user="root";
$pwd="Dannygram100%";
$db="ticketdb";
$myconn=mysqli_connect($host,$user,$pwd,$db);

if($myconn) 
{
    echo "Connection To Database Server Established<br>";

}

else
echo "connection failed";

?>