<?php
  $host="<<ip serwera>>";
  $db_user="<<nazwa uzytkownika>>";
  $db_password="<<haslo uzytkowinka>>";
  $database="<<nazwa bazy danych>>";
$link= mysqli_connect($host,$db_user,$db_password,$database);
$zat="call zatw()";
$zat_w=mysqli_query($link,$zat);
?>