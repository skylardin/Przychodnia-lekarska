<?php
ob_start();
date_default_timezone_set('Europe/Warsaw');
session_start();
include("connect_db.php");

?>
<html>
<head>
<title>Łukasz Szkaradek, Łukasz Mamak i Paweł Wilczek </title>

<META http-equiv=Content-Language content=pl>
<META http-equiv=Content-Type content="text/html; charset=windows-1250">
<style>
a {
    color: black;
    text-decoration: none;
}
</style>
</head>

<body>
<center>
<a href="index.php">Powrót</a> | <a href="recepcja.php"><?php if($_SESSION['zalogowany']=="ok") {
echo $_SESSION['login'];
} else {
echo 'Zaloguj się';
}
?></a>
<body style="background-size: 100% 200%;" background="tlo.jpg" bgproperties="fixed"> <br> <br>
<img style="margin:-7px;width: 101%; text-align:center;" src="logo.png" alt="Logo" />
<?php

if (isset($_POST[imie_osoby]) and isset($_POST[nazwisko_osoby]) and isset($_POST[pesel])) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
for ($i = 0; $i < 5; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
$zapytanie="INSERT into pacjenci (imie, nazwisko, pesel, nr_telefonu, kod) values('".$_POST[imie_osoby]."', '".$_POST[nazwisko_osoby]."', '" . $_POST[pesel] . "', '" . $_POST[tel] . "', '". $randomString ."')";
$wykonaj = mysqli_query($link, $zapytanie);

$zapytanie_ip = "DELETE from ip WHERE ip='".$_SERVER['REMOTE_ADDR']."'";
$wykonaj_ip = mysqli_query($link, $zapytanie_ip);
$zapytanie_ip2="INSERT into ip (ip, pesel, kod) values('" . $_SERVER['REMOTE_ADDR'] . "','" . $_POST[pesel] . "','" . $randomString . "')";
$wykonaj_ip2=mysqli_query($link,$zapytanie_ip2);

echo "Wpisano do kolejki.<br>Proszę zapamiętać swój kod: " . $randomString;
echo '<br><br><br><a style="color:red" href="index.php">Powrót do strony głównej.</a><br>'; 
} else {
echo '<form action="formularz.php" method="POST"><table border="2">
<tr><td align="center" colspan="2">Formularz</td><tr>
<tr><td>Imię:</td><td><input type="text" name="imie_osoby"></td><tr>
<td>Nazwisko:</td><td><input type="text" name="nazwisko_osoby"></td><tr>
<td>Pesel:</td><td><input type="number" name="pesel"></td><tr>
<td>Numer telefonu:</td><td><input type="number" name="tel"></td><tr>
<td align="center" colspan="2"><input type="submit" value="Wyślij"> </form></td></table>';
}
?>
</body>
<br>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td style="background: rgba(42,42,42,0.4);" align="center"><font face="verdana" size="1" color="white">
      &nbsp&nbspCopyright &copy; <b>Łukasz Szkaradek, Łukasz Mamak i Paweł Wilczek&nbsp&nbsp</b></td>
  </tr>
</table>
</center>
</html>