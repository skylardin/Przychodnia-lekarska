<?php

  $host="<<ip serwera>>";
  $db_user="<<nazwa urzytkownika>>";
  $db_password="<<has�o urzytkowinka>>";
  $database="<<nazwa bazy danych>>";
$link= mysqli_connect($host,$db_user,$db_password,$database);
$zat="call zatw()";
$zat_w=mysqli_query($link,$zat);
?>