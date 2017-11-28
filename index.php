<?php

ob_start();
date_default_timezone_set('Europe/Warsaw');
session_start();
include("connect_db.php");

if (isset($_GET[powrot])) {
session_destroy();
header("Location: index.php");
}

if (isset($_POST[stan])){
$zapytanie="SELECT * FROM pacjenci WHERE pesel='$_POST[pesel]' and kod='$_POST[kod]'";
$wykonaj=mysqli_query($link,$zapytanie);
if(@mysqli_num_rows($wykonaj)){
while($wiersz=mysqli_fetch_assoc($wykonaj)) {
$_SESSION[zalogowany] = "pacjent";
$_SESSION[id_p] = $wiersz['id_pacjenta'];
$_SESSION['baza'] = 'pacjent';
}
} else {
$brak='1';
}
}


?>
<html>
<head>
<title>Przychodnia lekarska</title>
<META http-equiv=Content-Language content=pl>
<META http-equiv=Content-Type content="text/html; charset=windows-1250">
<style>
a {
    color: black;
    text-decoration: none;
}
</style>
</head>
<body style="background-size: 100% 200%;" background="tlo.jpg" bgproperties="fixed">
<center>
<?php if($_SESSION['baza']=="pacjent") {
echo '<a href="?powrot">Powrót</a>';
} else {
echo '<a href="formularz.php">Formularz</a>';
}
?> | <a href="recepcja.php"><?php if($_SESSION['zalogowany']=="ok") {
echo $_SESSION['login'];
} else {
echo 'Zaloguj się';
}
?></a><br><br>
<img style="margin:-7px;width: 101%; text-align:center;" src="logo.png" alt="Logo" />
<?php
if (isset($brak)) {echo "Błąd! Brak osoby w bazie!";}

if ($_SESSION[baza] == "pacjent") {

$zapytanie="SELECT * FROM pacjenci WHERE id_pacjenta='$_SESSION[id_p]'";
$wykonaj=mysqli_query($link,$zapytanie);
if(@mysqli_num_rows($wykonaj)){
echo '<table border="1">';
while($wiersz=mysqli_fetch_assoc($wykonaj)) {
echo "<tr><td>Imię: " . $wiersz['imie'] . "</td><td>Nazwisko: " . $wiersz['nazwisko'] . "</td><td>Pesel: " . $wiersz['pesel'] . "</td><td>Numer telefonu: " . $wiersz['nr_telefonu'] . "</td></tr>";
}
echo '</table><br><br>';
}



if (isset($_POST[w_godziny])){
if ($_POST[w_godziny]=="") {header("Location: index.php");}
$odb = strtotime($_SESSION['w_dnia'] . '-' . date('Y') . ' ' . $_POST[godzina] . '.00');
$zapytanie_x="SELECT * FROM spotkania WHERE id_osoby='$_SESSION[id_p]' and id_specjalizacji='$_SESSION[w_specjalizacji]' and stan='0'";
$wykonaj_x=mysqli_query($link,$zapytanie_x);
if(!@mysqli_num_rows($wykonaj_x)) {
$zapytanie2="INSERT into spotkania (id_specjalizacji, id_lekarza, id_osoby, data_odbycia, data_zapisu, stan) values('".$_SESSION[w_specjalizacji]."', '" . $_SESSION[w_lekarza] ."', '" . $_SESSION[id_p] ."', '" . $odb . "', '" . date('Y-m-j H:i:s') . "', '0')";
$wykonaj2=mysqli_query($link,$zapytanie2);
}
unset($_SESSION['w_dnia']);
unset($_SESSION[w_specjalizacji]);
unset($_SESSION[w_lekarza]);
header("Location: index.php");
}


if (isset($_POST[spotkanie]) or isset($_POST[lekarz])) {

if (isset($_POST[spotkanie])) {
echo "Wybierz lekarza:<br>";
$_SESSION['w_specjalizacji'] = $_POST[id_specjalizacji];
$_SESSION['w_dnia'] = $_POST[dzien];
echo '<form action="" method="POST"><select name="w_lekarza">';
$zapytanie1="SELECT * FROM lekarze where id_specjalizacji='".$_SESSION['w_specjalizacji']."'";
$wykonaj1=mysqli_query($link,$zapytanie1);
while($wiersz1=mysqli_fetch_assoc($wykonaj1)) {
echo '<option value="' . $wiersz1['id_lekarza'] . '">' . $wiersz1['imie'] . " " . $wiersz1['nazwisko'] . "</option>";
}
echo '</select><br> <input type="submit" name="lekarz" value="Dalej"></form><br><br><a href="">Wróć</a>';
}

if (isset($_POST[lekarz])) {
$_SESSION[w_lekarza] = $_POST[w_lekarza];
$zapytanie="SELECT * FROM spotkania WHERE id_lekarza='".$_POST[w_lekarza]."'";
$wykonaj=mysqli_query($link,$zapytanie);
if(@mysqli_num_rows($wykonaj)){
echo "Wybierz godzinę spotkania:<br>";
while($wiersz=mysqli_fetch_assoc($wykonaj)) {
$zaj[] = date ('j-m G.i' , $wiersz['data_odbycia']);
}
}
$arr = array('9.00', '9.30', '10.00', '10.30', '11.00', '11.30', '12.00', '12.30', '13.00', '13.30', '14.00', '14.30', '15.00', '15.30', '16.00', '16.30', '17.00', '17.30', '18.00', '18.30', '19.00', '19.30');
echo '<form action="" method="POST"><select name="godzina">';
foreach ($arr as &$value) {
if ($_SESSION['w_dnia'] == date ('j-m')) {
if ($value > date('G.i') and !is_numeric(array_search($_SESSION['w_dnia'].' '.$value, $zaj))) {
echo '<option value="' . $value . '">' . $value . "</option>";
}
} else {
echo $_SESSION['w_dnia'].' '.$value;
if (!is_numeric(array_search($_SESSION['w_dnia'].' '.$value, $zaj))) {
echo '<option value="' . $value . '">' . $value . "</option>";
}
}
}
echo '</select></td><br> <input type="submit" name="w_godziny" value="Zapisz"></form><br><br><a href="">Wróć</a>';
}


} else {
echo '<button onclick="dodaj()">Dodaj spotkanie</button><br><div  style="height:50px">';
$zapytanie_s="SELECT * FROM specjalizacje";
$wykonaj_s=mysqli_query($link,$zapytanie_s);
echo '<form action="" method="POST"><input type="text" hidden=hidden name="pesel" value="' . $_POST[pesel] . '"><input type="text" hidden=hidden name="kod" value="' . $_POST[kod] . '">';
echo '<table style="display: none; text-align:center;" id="dodaj" border="1">';
echo '<tr><td>Wybierz specjalizację:</td><td><select name="id_specjalizacji">';
while($wiersz=mysqli_fetch_assoc($wykonaj_s)) {
echo '<option value="' . $wiersz['id_specjalizacji'] . '">' . $wiersz['nazwa_specjalizacji'] . "</option>";
}

echo '</select></td></tr>';
echo '<tr><td>Wybierz dzień spotkania:</td><td><select name="dzien">';



$dod = 0;
$dzien = date("N");
$dzien_m = date("j");
$miesiac = date("n");

if ($dzien == 6) {
$dzien = 1;
$dzien_m = $dzien_m + 1;
if ($dzien_m > date("t")) {
$dzien_m = 1;
if ($miesiac == 12) {
$miesiac = 1;
} else {
$miesiac = $miesiac + 1;
}
}
$dzien_m = $dzien_m + 1;
if ($dzien_m > date("t")) {
$dzien_m = 1;
if ($miesiac == 12) {
$miesiac = 1;
} else {
$miesiac = $miesiac + 1;
}
}
}

if ($dzien == 7) {
$dzien_m = $dzien_m + 1;
if ($dzien_m > date("t")) {
$dzien_m = 1;
if ($miesiac == 12) {
$miesiac = 1;
} else {
$miesiac = $miesiac + 1;
}
}
}

while($dzien+$dod <= 5) {

if ($dzien_temp == date("t")) {
$dzien_m = -1 * $dod + 1 ;
if ($miesiac == 12) {
$miesiac = 1;
} else {
$miesiac = $miesiac + 1;
}
}
$dzien_temp = $dzien_m + $dod;
echo '<option value="' . $dzien_temp . '-' . $miesiac . '">' . $dzien_temp . '.' . $miesiac . ' - ';
switch($dzien+$dod) {
    case "1":
        echo "Poniedziałek";
    break;
    case "2":
        echo "Wtorek";
    break;
    case "3":
        echo "Środa";
    break;
    case "4":
        echo "Czwartek";
    break;
    case "5":
        echo "Piątek";
    break;
}

echo "</option>";
$dod = $dod + 1;
}

echo '</select></td></tr></table><input style="display: none" id="p_dodaj" type="submit" name="spotkanie" value="Dodaj"></from><br></div>';

echo '<br><br>Lista spotkań z lekarzami:<table width="1000" style="text-align:center;" border="1">';
echo "<td>Specjalizacja</td><td>Imie lekarza</td><td>Nazwisko lekarza</td><td>Numer pokoju</td><td>Data spotkania</td><td>Data zapisu</td><td>Obyło się</td>";
$zapytanie_z="SELECT * FROM spotkania WHERE id_osoby='".$_SESSION[id_p]."'";
$wykonaj_z=mysqli_query($link,$zapytanie_z);
while($wiersz=mysqli_fetch_assoc($wykonaj_z)) {

$zapytanie_s="SELECT * FROM specjalizacje WHERE id_specjalizacji='".$wiersz['id_specjalizacji']."'";
$wykonaj_s=mysqli_query($link,$zapytanie_s);
while($wiersz_s=mysqli_fetch_assoc($wykonaj_s)) {
$specjalizacja = $wiersz_s['nazwa_specjalizacji'];
}
echo '';

$zapytanie_l="SELECT * FROM lekarze WHERE id_lekarza='".$wiersz['id_lekarza']."'";
$wykonaj_l=mysqli_query($link,$zapytanie_l);
while($wiersz_l=mysqli_fetch_assoc($wykonaj_l)) {
$imie_lekarza = $wiersz_l['imie'];
$nazwisko_lekarza = $wiersz_l['nazwisko'];
$nr_pokoju = $wiersz_l['nr_pokoju'];
}

echo '<tr><td>'.$specjalizacja.'</td><td>'.$imie_lekarza.'</td><td>'.$nazwisko_lekarza.'</td><td>'.$nr_pokoju.'</td><td>'.date('Y-m-j H:i:s' , $wiersz['data_odbycia']).'</td><td>'.$wiersz['data_zapisu'].'</td><td>';
if ($wiersz['stan']=='0') {
echo 'Nie';
} else {
echo 'Tak';
}
echo'</td>';


}

echo '</table>';
}
} else {

echo'<br><br><br>
<form action="" method="POST">
<table width="300">
<tr>
<td align="right">Pesel:</td>
<td align="let"><input type="text" name="pesel"></td>
<td align="right">Kod:</td>
<td align="let"><input type="text" name="kod"></td>
</tr>
<tr>
<td align="center" colspan="4"><input type="submit" name="stan" value="Sprawdź"></td>
</tr>
</table>
</form>
<br>
<table border="0" width="300">
<tr>
<td align="left">
Witaj na naszej przychodni lekarskiej. Proszę podać swój pesel oraz kod.
</td>
</tr>
<tr>
<td align="right"><i></i></td>
</tr>
</table>';
    
    
    
echo '<br><br><br>
<table border="1px">
<tr><td style="text-align: center;" colspan="2">Aktualności</td></tr>';
$aktualnosci="SELECT * FROM aktualnosci";
$wykonaj=mysqli_query($link,$aktualnosci);
if(@mysqli_num_rows($wykonaj)){
while($wiersz=mysqli_fetch_assoc($wykonaj)) {
echo '<tr><td width="150">'.$wiersz['data'].'</td><td width="950">'.$wiersz['opis'].'</tr></table>';
}
}
}

?>
</center>

<script>

var d = document.getElementById('dodaj');
var pd = document.getElementById('p_dodaj');

function dodaj() {
    if (d.style.display === 'none') {
        d.style.display = '';
        pd.style.display = '';
    } else {
        d.style.display = 'none';
        pd.style.display = 'none';
    }
}
</script>
</body>
</html>