<?php
error_reporting(E_ALL ^ E_NOTICE);
ob_start();
session_start();

if(!file_exists("connect_db.php")){
exit;
} else {
include("connect_db.php");
}
if(isset($_POST['login'])) {
$zapytanie_r="SELECT * FROM recepcja WHERE login='$_POST[login]' AND haslo='$_POST[haslo]'";
$zapytanie_l="SELECT * FROM lekarze WHERE login='$_POST[login]' AND haslo='$_POST[haslo]'";
$zapytanie_d="SELECT * FROM dyrektor WHERE login='$_POST[login]' AND haslo='$_POST[haslo]'";
$wykonaj_r=mysqli_query($link,$zapytanie_r);
$wykonaj_l=mysqli_query($link,$zapytanie_l);
$wykonaj_d=mysqli_query($link,$zapytanie_d);

if(@mysqli_num_rows($wykonaj_r)){
while($wiersz=mysqli_fetch_assoc($wykonaj_r)) {
$_SESSION['zalogowany'] = "ok";
$_SESSION['login'] = $wiersz[login];
$_SESSION['baza'] = 'recepcja';
$_SESSION['id'] = $wiersz[id_recepcjonisty];
$_SESSION['imie'] = $wiersz[imie];
$_SESSION['nazwisko'] = $wiersz[nazwisko];
}
}

if(@mysqli_num_rows($wykonaj_l)){
while($wiersz=mysqli_fetch_assoc($wykonaj_l)) {
$_SESSION['zalogowany'] = "ok";
$_SESSION['login'] = $wiersz[login];
$_SESSION['baza'] = 'lekarze';
$_SESSION['id'] = $wiersz[id_lekarza];
$_SESSION['imie'] = $wiersz[imie];
$_SESSION['nazwisko'] = $wiersz[nazwisko];
}
}

if(@mysqli_num_rows($wykonaj_d)){
while($wiersz=mysqli_fetch_assoc($wykonaj_d)) {
$_SESSION['zalogowany'] = "ok";
$_SESSION['login'] = $wiersz[login];
$_SESSION['baza'] = 'dyrektor';
$_SESSION['id'] = $wiersz[id_dyrektora];
$_SESSION['imie'] = $wiersz[imie];
$_SESSION['nazwisko'] = $wiersz[nazwisko];
}
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
<div align="center">
<?php
if($_SESSION['zalogowany']=="ok") {
echo '<a href="recepcja.php?stan=wyloguj">Wyloguj</a> | ';
if (isset($_GET['haslo'])) {
echo '<a href="recepcja.php">Powrót</a> |';
} else {
echo '<a href="recepcja.php?haslo">Zmień hasło</a> |';
}
}
?> <a href="index.php">Strona główna</a><br><br>
<img style="margin:-7px;width: 101%; text-align:center;" src="logo.png" alt="Logo" />




<?php

if($_GET['stan']=="wyloguj") {
session_destroy();
$_SESSION[zalogowany]="no";
$url = "recepcja.php?stan=wyloguj";
if (isset($_GET['pass'])) { header('Location: recepcja.php?pass'); } else { header('Location: recepcja.php'); }
}

if(isset($_GET['haslo'])) {
if ($_GET['stan']=='h') { 
echo "Błąd, hasła nie są takie same!";
echo '<br><a href="recepcja.php?haslo">Spróbuj jeszcze raz.</a>'; 
} else {
if($_SESSION['zalogowany']=="ok") {
if (isset($_POST['starehaslo'])) {
if ($_POST['haslo1']!==$_POST['haslo2'] || $_POST['haslo1']=='') { header('Location: recepcja.php?haslo&stan=h'); 
} else {
switch($_SESSION['baza']) {

case "recepcja":
$zapytanie1="SELECT * FROM recepcja WHERE haslo='".$_POST['starehaslo']."' AND id_recepcjonisty='".$_SESSION['id']."'";
$wykonaj1=mysqli_query($link,$zapytanie1);
if(@mysqli_num_rows($wykonaj1)){
$zapytanie="UPDATE recepcja SET haslo='".$_POST['haslo1']."' WHERE id_recepcjonisty='".$_SESSION['id']."'";
$wykonaj = mysqli_query($link, $zapytanie);
header('Location: recepcja.php?stan=wyloguj&pass');
}
break;

case "lekarze":
$zapytanie1="SELECT * FROM lekarze WHERE haslo='".$_POST['starehaslo']."' AND id_lekarza='".$_SESSION['id']."'";
$wykonaj1=mysqli_query($link,$zapytanie1);
if(@mysqli_num_rows($wykonaj1)){
$zapytanie="UPDATE lekarze SET haslo='".$_POST['haslo1']."' WHERE id_lekarza='".$_SESSION['id']."'";
$wykonaj = mysqli_query($link, $zapytanie);
header('Location: recepcja.php?stan=wyloguj&pass');
}
break;

case "dyrektor":
$zapytanie1="SELECT * FROM dyrektor WHERE haslo='".$_POST['starehaslo']."' AND id_dyrektora='".$_SESSION['id']."'";
$wykonaj1=mysqli_query($link,$zapytanie1);
if(@mysqli_num_rows($wykonaj1)){
$zapytanie="UPDATE dyrektor SET haslo='".$_POST['haslo1']."' WHERE id_dyrektora='".$_SESSION['id']."'";
$wykonaj = mysqli_query($link, $zapytanie);
header('Location: recepcja.php?stan=wyloguj&pass');
}
break;
}
echo 'Hasło jest niepoprawne.';
echo '<br><a href="recepcja.php?haslo">Spróbuj jeszcze raz.</a>'; 
}
} else {

echo '<form action="recepcja.php?haslo" method="post"> Podaj stare hasło: <input type="password" name="starehaslo"><br>
Podaj nowe hasło: <input type="password" name="haslo1"><br>
Powtórz nowe hasło: <input type="password" name="haslo2"><br> 
<input type="submit" value="Zmień hasło"></form>';
}
	

} else { header('Location: index.php');}
}
exit;
}




if($_SESSION['zalogowany']=="ok" and !isset($_GET['haslo']))
{
switch($_SESSION['baza']) {
case "lekarze":
$u = 'Lekarz';
break;
case "dyrektor":
$u = 'Dyrektor';
break;
case "recepcja":
$u = 'Recepcjonista';
break;
}
echo'Twoje dane:<br>';
echo '<table width="400" style="text-align: center;" border="1">
<tr><td>Imię</td><td>Nazwisko</td><td>Uprawnienia</td></tr>
<tr><td>' . $_SESSION['imie'] . '</td><td>' . $_SESSION['nazwisko'] . '</td><td>' . $u . '</td></tr></table><br>';
switch($_SESSION['baza'])
	{
case "lekarze":
echo '<table width="400" style="text-align: center;" border="1">
<tr><td>Umówione spotkania</td><td><a href="wyswietl.php?l=spotkania"><button>Wyświetl</button></a></td></tr>
</table><br>';

break; 

case "dyrektor":
echo '<table width="400" style="text-align: center;" border="1">';
echo '<tr><td>Lekarze</td><td><a href="wyswietl.php?l=lekarze"><button>Zarządzaj</button></a></td></tr>
<tr><td>Specjalizacje</td><td><a href="wyswietl.php?l=specjalizacje"><button>Zarządzaj</button></a></td></tr>
<tr><td>Recepcjoniści</td><td><a href="wyswietl.php?l=recepcja"><button>Zarządzaj</button></a></td></tr>
<tr><td>Lista spotkań</td><td><a href="wyswietl.php?l=spotkania"><button>Zarządzaj</button></a></td></tr>
<tr><td>Pacjenci</td><td><a href="wyswietl.php?l=pacjenci"><button>Zarządzaj</button></a></td></tr>
<tr><td>Aktualności</td><td><a href="wyswietl.php?l=aktualnosci"><button>Zarządzaj</button></a></td></tr>
	</center>
</table>';
break; 

case "recepcja":
	    

echo '<table width="400" style="text-align: center;" border="1">
<tr><td>Spotkania</td><td><a href="wyswietl.php?l=spotkania"><button>Zarządzaj</button></a></td></tr>
<tr><td>Aktualności</td><td><a href="wyswietl.php?l=aktualnosci"><button>Zarządzaj</button></a></td></tr>
<tr><td colspan="2"><br></td></tr>
<tr><td>Lista lekarzy</td><td><a href="wyswietl.php?l=lekarze"><button>Wyświetl</button></a></td><tr>
<tr><td>Lista specjalizacji</td><td><a href="wyswietl.php?l=specjalizacje"><button>Wyświetl</button></a></td></tr>
</table>
<br>
';	
 break; 

	    
	    
}
} else {
if(isset($_POST['login'])) {
echo 'Błąd! Niepoprawny login lub hasło.';
}
if(isset($_GET['pass'])) {
echo 'Zmieniono hasło. Proszę się zalogować.';
}
echo'
<form action="" method="POST">
<table class="linia" width="300">
<tr>
<td align="right">Login:</td>
<td align="let"><input type="text" name="login"></td>
</tr>
<tr>
<td align="right">Hasło:</td>
<td align="left"><input type="password" name="haslo"></td>
</tr>
<tr>
<td align="center" colspan="2"><input type="submit" name="stan" value="Zaloguj"></td>
</tr>
</table>
</form>
<br>

<table border="0" width="300">
<tr>
<td align="left">
Witaj na naszej przychodni lekarskiej, proszę podać login i hasło.
</td>
</tr>
<tr>
<td align="right"><i></i></td>
</tr>
</table>

';
}



?>



<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td style="background: rgba(42,42,42,0.4);" align="center"><font face="verdana" size="1" color="white">
      &nbsp&nbspCopyright &copy; <b>Łukasz Szkaradek, Łukasz Mamak i Paweł Wilczek&nbsp&nbsp</b></td>
  </tr>
</table>





</body>
</html>