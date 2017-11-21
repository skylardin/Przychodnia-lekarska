<?php

session_start();
include("connect_db.php");

?>
<html>
<head>
<title>Łukasz Szkaradek, Łukasz Mamak i Paweł Wilczek </title>

<META http-equiv=Content-Language content=pl>
<META http-equiv=Content-Type content="text/html; charset=windows-1250">

</head>
<body leftmargin="0" topmargin="0" border="0" marginheight="0" marginwidth="0">

<a href="index.php">Powrót</a> | <a href="recepcja.php"><?php if($_SESSION['zalogowany']=="ok") {
echo $_SESSION['login'];
} else {
echo 'Zaloguj się';
}
?></a>
<center>
<?php

if (isset($_POST[imie_osoby]) and isset($_POST[nazwisko_osoby]) and isset($_POST[pesel])) {
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
for ($i = 0; $i < 5; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
$zapytanie="INSERT into pacjenci (imie, nazwisko, pesel, nr_telefonu, kod) values('".$_POST[imie_osoby]."', '".$_POST[nazwisko_osoby]."', '" . $_POST[pesel] . "', '" . $_POST[tel] . "', '". $randomString ."')";
$wykonaj = mysqli_query($link, $zapytanie);
echo "Wpisano do kolejki. Proszę zapamiętać swój kod: " . $randomString;
} else {
echo 'Formularz <br>
<form action="formularz.php" method="POST">
<table>
<tr>
<td>';
echo '</td>
Imię: <input type="text" name="imie_osoby"><br>
Nazwisko: <input type="text" name="nazwisko_osoby"><br>
Pesel: <input type="text" name="pesel"><br>
Numer telefonu: <input type="text" name="tel"><br>
<br> <input type="submit" value="Wyślij"> </form>';
}
?>
</center>
</body>
</html>