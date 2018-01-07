<?php
error_reporting(E_ALL ^ E_NOTICE);
ob_start();
session_start();
date_default_timezone_set('Europe/Warsaw');
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
textarea {
  width: 800px;
  height: 300px;
  resize: none;
}
</style>
</head>
<body <?php if ($_GET['l']=='spotkania') { echo 'onload="z_stan(\'d\', \'c\'), z_stan(\'lekarz_d\', \'stan_d\')"'; } ?> style="background-size: 100% 200%;" background="tlo.jpg" bgproperties="fixed">
<div align="center">

<a href="recepcja.php?stan=wyloguj">Wyloguj</a> | <a href="recepcja.php"><?php echo $_SESSION['login']; ?></a><br><br>

<img style="margin:-7px;width: 101%; text-align:center;" src="logo.png" alt="Logo" />



<?php



if(!file_exists("connect_db.php")){
exit;
} else {
include("connect_db.php");
}

if($_SESSION['zalogowany']=="ok")
{
switch($_GET['l'])
	{
	    
	case "aktualnosci":
	if ($_SESSION['baza'] == 'dyrektor' or $_SESSION['baza'] == 'recepcja') {
	if(isset($_GET['id_aktualnosci_u'])) {
	$zapytanie_u="DELETE from aktualnosci WHERE id_aktualnosci='".$_GET['id_aktualnosci_u']."'";
	$wykonaj_u = mysqli_query($link, $zapytanie_u);
	header('Location: wyswietl.php?l='.$_GET['l']);

	}
    	if(isset($_POST['id_aktualnosci_e'])) { 
    	$zapytanie_e="UPDATE aktualnosci SET data='".$_POST['data']."', opis='".$_POST['opis']."' WHERE id_aktualnosci='".$_POST['id_aktualnosci_e']."'";
	$wykonaj_e = mysqli_query($link, $zapytanie_e);
	header('Location: wyswietl.php?l='.$_GET['l']);
    	}
    	if(isset($_POST['data_d'])) { 
	if ($_POST['data_d']=="") {
	$zapytanie_d="INSERT into aktualnosci (opis) values('".$_POST['opis']."')";
	} else {
	$zapytanie_d="INSERT into aktualnosci (data, opis) values('".$_POST['data_d']."', '".$_POST['opis']."')";
	}
    	$wykonaj_d=mysqli_query($link,$zapytanie_d);
   	header('Location: wyswietl.php?l='.$_GET['l']);
 }
    
    
	echo '<button onclick="dodaj()">Dodaj</button>&nbsp;<button onclick="edycja()">Edytuj</button><br>';
	echo '<br> Lista aktulaności <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>ID aktualności</td><td>Data</td><td>Opis</td><td></td><td></td>";
	$zapytanie = "select * from aktualnosci";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['id_aktualnosci']."</td>
	<td>".$wiersz['data']."</td>
	<td>".$wiersz['opis']."</td>";
	echo '<td><button onclick="dane(\'' . $wiersz['id_aktualnosci'] . '\',\'' . $wiersz['data'] . '\',\'' . $wiersz['opis'] . '\')">Edytuj</button></td>';
	echo '<td><a href="wyswietl.php?l=aktualnosci&id_aktualnosci_u=' . $wiersz['id_aktualnosci'] . '"/><button>Usuń</button></td>';
	}
	echo '</table><br><div style="height:150px;">';
	
	echo '<table id="edycja" style="display: none" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=aktualnosci" method="post">';
	echo '<tr><td>ID aktualności</td><td><input type="text" id="a" name="id_aktualnosci_e"></tr>
	<tr><td>Data</td><td><input type="text" id="b" name="data"><td>
	<tr><td>Opis</td><td><input type="text" id="c" name="opis"><td>';
	echo '</table><input id="p_edycja" style="display: none" type="submit" value="Edytuj">';
	echo '</form><br><br>';
	echo '<table style="display: none" id="dodaj" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=aktualnosci" method="post">';
	echo '<tr><td>Data</td><td><input type="text" name="data_d"><td>
	<tr><td>Opis</td><td><input type="text" name="opis"><td>';
	echo '</table><input style="display: none" id="p_dodaj" type="submit" value="Dodaj">';
	echo '</form></div>';
	}
	break;
	case "spotkania":
	if ($_SESSION['baza'] == 'dyrektor' and isset($_GET['karta_pacjenta']) or isset($_POST['karta'])) {
	$t = 1;
	}
	if ($t != '1' and $_SESSION['baza'] == 'dyrektor' or $_SESSION['baza'] == 'recepcja') {
	if(isset($_GET['id_spotkania_u'])) {
	$zapytanie_u="DELETE from spotkania WHERE id_spotkania='".$_GET['id_spotkania_u']."'";
	$wykonaj_u = mysqli_query($link, $zapytanie_u);
	header('Location: wyswietl.php?l='.$_GET['l']);
	}
	if(isset($_POST['id_spotkania_e'])) { 
	$odb = strtotime($_POST['data_odbycia']);
	$zapytanie_e="UPDATE spotkania SET id_specjalizacji='".$_POST['id_specjalizacji']."', id_lekarza='".$_POST['id_lekarza']."', id_osoby='".$_POST['id_osoby']."', data_odbycia='".$odb."', data_zapisu='".$_POST['data_zapisu']."', stan='".$_POST['stan']."' WHERE id_spotkania='".$_POST['id_spotkania_e']."'";
	$wykonaj_e = mysqli_query($link, $zapytanie_e);
	header('Location: wyswietl.php?l='.$_GET['l']);
	}
	if(isset($_POST['id_specjalizacji_d'])) { 
	$odb = strtotime($_POST['data_zapisu']);
	$zapytanie_d="INSERT into spotkania (id_specjalizacji, id_lekarza, id_osoby, data_odbycia, data_zapisu, stan) values('".$_POST['id_specjalizacji_d']."', '".$_POST['id_lekarza']."', '".$_POST['id_osoby']."', '".$odb."', '".$_POST['data_zapisu']."', '".$_POST['stan']."')";
	$wykonaj_d=mysqli_query($link,$zapytanie_d);
	header('Location: wyswietl.php?l='.$_GET['l']);
	}
    
    
	echo '<button onclick="dodaj()">Dodaj</button>&nbsp;<button onclick="edycja()">Edytuj</button><br>';
	echo '<br> Lista Spotkań <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>ID Spotkania</td><td>Data zapisu</td><td>Specjalizacja</td><td>Lekarz</td><td>Pacjent</td><td>Data odbycia</td><td>Odbyło się</td><td></td><td></td>";
	$zapytanie = "select * from widok_lista";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['id_spotkania']."</td>
	<td>".$wiersz['data_zapisu']."</td>
	<td>".$wiersz['specjalizacja']."</td>
	<td>".$wiersz['imie_lekarza']." ".$wiersz['nazwisko_lekarza']."</td>
	<td>".$wiersz['imie_osoby']." ".$wiersz['nazwisko_osoby']."</td>
	<td>".date('Y-m-j H:i:s' , $wiersz['data_odbycia'])."</td>
	<td>";
	if ($wiersz['stan'] == "1") {echo "Tak";} else {echo "Nie";}
	echo '</td><td><button onclick="dane(\'' . $wiersz['id_spotkania'] . '\',\'' . $wiersz['data_zapisu'] . '\',\'' . $wiersz['id_specjalizacji'] . '\',\'' . $wiersz['id_lekarza'] . '\',\'' . $wiersz['id_osoby'] . '\',\'' . date('Y-m-j H:i:s' , $wiersz['data_odbycia']) . '\',\'' . $wiersz['stan'] . '\')">Edytuj</button></td>';
	echo '<td><a href="wyswietl.php?l=spotkania&id_spotkania_u=' . $wiersz['id_spotkania'] . '"/><button>Usuń</button></td>';
	}
	echo '</table><br><div style="height:180px;">';
	
	echo '<table id="edycja" style="display: none" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=spotkania" method="post">';
	echo '<tr><td>ID Spotkania</td><td><input type="text" id="a" name="id_spotkania_e"></tr>
	<tr><td>Data zapisu (RRRR-MM-DD gg:mm:ss)</td><td><input type="text" id="b" name="data_zapisu"></tr>
	<tr><td>Specjalizacja</td><td>';
	$zapytanie_s="SELECT * FROM specjalizacje";
	$wykonaj_s=mysqli_query($link,$zapytanie_s);
	echo '<select onchange="z_stan(\'d\', \'c\')" id="c" name="id_specjalizacji">';
	while($wiersz=mysqli_fetch_assoc($wykonaj_s)) {
	echo '<option value="' . $wiersz['id_specjalizacji'] . '">' . $wiersz['nazwa_specjalizacji'] . ' - ID: ' . $wiersz['id_specjalizacji'] . '</option>';
	}
	echo '</select>';
	echo '<td>
	<tr><td>Lekarz</td><td id="lekarz_e"><select id="d" name="id_lekarza"></select><td>
	<tr><td>Pacjent</td><td>';
	$zapytanie_s="SELECT * FROM pacjenci";
	$wykonaj_s=mysqli_query($link,$zapytanie_s);
	echo '<select id="e" name="id_osoby">';
	while($wiersz=mysqli_fetch_assoc($wykonaj_s)) {
	echo '<option value="' . $wiersz['id_pacjenta'] . '">' . $wiersz['imie'] . ' ' . $wiersz['nazwisko'] . '(' . $wiersz['pesel'] . ') - ID: ' . $wiersz['id_pacjenta'] . '</option>';
	}
	echo '</select>';
	echo '<td>
	<tr><td>Data odbycia (RRRR-MM-DD gg:mm:ss)</td><td id="data_e"><input type="text" id="f" name="data_odbycia"><td>
	<tr><td>Odbyło się</td><td><select id="g" name="stan">
	<option value="1" > Tak </option>
	<option value="0" > Nie </option>
	</select><td>';
	echo '</table><input id="p_edycja" style="display: none" type="submit" value="Edytuj">';
	echo '</form><br><br>';


	echo '<table style="display: none" id="dodaj" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=spotkania" method="post">
	<tr><td>Data zapisu (RRRR-MM-DD gg:mm:ss)</td><td><input type="text" name="data_zapisu"></tr>';
	echo '<tr><td>Specjalizacja</td><td>';
	$zapytanie_s="SELECT * FROM specjalizacje";
	$wykonaj_s=mysqli_query($link,$zapytanie_s);
	echo '<select id="stan_d" onchange="z_stan(\'lekarz_d\', \'stan_d\')" name="id_specjalizacji_d">';
	while($wiersz=mysqli_fetch_assoc($wykonaj_s)) {
	echo '<option value="' . $wiersz['id_specjalizacji'] . '">' . $wiersz['nazwa_specjalizacji'] . ' - ID: ' . $wiersz['id_specjalizacji'] . '</option>';
	}
	echo '</select>';
	echo '<td>
	<tr><td>Lekarz</td><td><select id="lekarz_d" name="id_lekarza"></select><td>
	<tr><td>Pacjent</td><td>';
	$zapytanie_s="SELECT * FROM pacjenci";
	$wykonaj_s=mysqli_query($link,$zapytanie_s);
	echo '<select name="id_osoby">';
	while($wiersz=mysqli_fetch_assoc($wykonaj_s)) {
	echo '<option value="' . $wiersz['id_pacjenta'] . '">' . $wiersz['imie'] . ' ' . $wiersz['nazwisko'] . '(' . $wiersz['pesel'] . ') - ID: ' . $wiersz['id_pacjenta'] . '</option>';
	}
	echo '</select>';
	echo '<td>
	<tr><td>Data odbycia (RRRR-MM-DD gg:mm:ss)</td><td><input id="data_d" type="text" name="data_odbycia"><td>
	<tr><td>Odbyło się</td><td><select name="stan">
	<option value="1" > Tak </option>
	<option value="0" > Nie </option>
	</select><td>';
	echo '</table><input style="display: none" id="p_dodaj" type="submit" value="Dodaj">';
	echo '</form></div>';
	
	
	} else {
	if ($_SESSION['baza'] != 'lekarze' AND $_SESSION['baza'] != 'dyrektor') {header('Location: recepcja.php');}
	if (isset($_POST['karta'])) {
	$zapytanie_e="UPDATE pacjenci SET karta_pacjenta='".$_POST['karta']."' WHERE id_pacjenta='".$_POST['id_pacjenta']."'";
	$wykonaj_e = mysqli_query($link, $zapytanie_e);
	header('Location: wyswietl.php?l='.$_GET['l']);
	}
	if (isset($_GET['karta_pacjenta'])) {
	echo '<button onclick="karta()">Edytuj kartę</button><br>';
	echo '<br> Karta pacjenta <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>Imię osoby</td><td>Nazwisko osoby</td><td>Pesel</td>";
	$zapytanie = "select * from pacjenci where id_pacjenta='".$_GET['karta_pacjenta']."'";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['imie']."</td>
	<td>".$wiersz['nazwisko']."</td>
	<td>".$wiersz['pesel']."</td>".'</table>';
	echo '<form action="wyswietl.php?l=spotkania" method="post">';
	echo '<input type="text" hidden="hidden" value="' . $_GET['karta_pacjenta'] . '" name="id_pacjenta">';
	echo '<textarea readonly name="karta" id="karta">' . $wiersz['karta_pacjenta'] . '</textarea>';
	echo '<br><input type="submit" style="display: none" id="p_karta" value="Potwierdź">';
	echo '</form>';
	}
	} else {
	echo '<br> Lista spotkań <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>Data odbycia</td><td>Imię osoby</td><td>Nazwisko osoby</td><td>Pesel</td><td>Data zapisu</td><td></td>";
	$zapytanie = "select * from widok_lista where id_lekarza='".$_SESSION['id']."'";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".date('Y-m-j H:i:s' , $wiersz['data_odbycia'])."</td>
	<td>".$wiersz['imie_osoby']."</td>
	<td>".$wiersz['nazwisko_osoby']."</td>
	<td>".$wiersz['pesel']."</td>
	<td>".$wiersz['data_zapisu']."</td>
	<td>" . '<td><a href="wyswietl.php?l=spotkania&karta_pacjenta=' . $wiersz['id_osoby'] . '"/><button>Karta pacjenta</button></td>';
	echo '</table>';
	}
	}
	
	

}
break;
	
	case "lekarze":
	if ($_SESSION['baza'] == 'dyrektor') {
	
	if(isset($_GET['id_lekarze_u'])) {
	$zapytanie_u="DELETE from lekarze WHERE id_lekarza='".$_GET['id_lekarze_u']."'";
	$wykonaj_u = mysqli_query($link, $zapytanie_u);
	header('Location: wyswietl.php?l='.$_GET['l']);
	}
    if(isset($_POST['id_lekarze_e'])) { 
    $zapytanie_e="UPDATE lekarze SET login='".$_POST['login']."', haslo='".$_POST['haslo']."', imie='".$_POST['imie']."', nazwisko='".$_POST['nazwisko']."', id_specjalizacji='".$_POST['id_specjalizacji']."', nr_pokoju='".$_POST['nr_pokoju']."' WHERE id_lekarza='".$_POST['id_lekarze_e']."'";
	$wykonaj_e = mysqli_query($link, $zapytanie_e);
    	header('Location: wyswietl.php?l='.$_GET['l']);
}
    if(isset($_POST['login_d'])) { 
    $zapytanie_d="INSERT into lekarze (login, haslo, imie, nazwisko, id_specjalizacji, nr_pokoju) values('".$_POST[login_d]."', '".$_POST[haslo]."', '".$_POST[imie]."', '".$_POST[nazwisko]."', '".$_POST[id_specjalizacji]."', '".$_POST[nr_pokoju]."')";
    $wykonaj_d=mysqli_query($link,$zapytanie_d);
    header('Location: wyswietl.php?l='.$_GET['l']);
    }
    
    
	echo '<button onclick="dodaj()">Dodaj</button>&nbsp;<button onclick="edycja()">Edytuj</button><br>';
	echo '<br> Lista lekarzy <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>ID lekarza</td><td>Login</td><td>Hasło</td><td>Imię</td><td>Nazwisko</td><td>Specjalizacja</td><td>Nr pokoju</td><td></td><td></td>";
	$zapytanie = "select * from widok_lekarze";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['id_lekarza']."</td>
	<td>".$wiersz['login']."</td>
	<td>".$wiersz['haslo']."</td>
	<td>".$wiersz['imie']."</td>
	<td>".$wiersz['nazwisko']."</td>
	<td>".$wiersz['specjalizacja']."</td>
	<td>".$wiersz['nr_pokoju']."</td>";
	echo '<td><button onclick="dane(\'' . $wiersz['id_lekarza'] . '\',\'' . $wiersz['login'] . '\',\'' . $wiersz['haslo'] . '\',\'' . $wiersz['imie'] . '\',\'' . $wiersz['nazwisko'] . '\',\'' . $wiersz['id_specjalizacji'] . '\',\'' . $wiersz['nr_pokoju'] . '\')">Edytuj</button></td>';
	echo '<td><a href="wyswietl.php?l=lekarze&id_lekarze_u=' . $wiersz['id_lekarza'] . '"/><button>Usuń</button></td>';
	}
	echo '</table><br><div style="height:180px;">';


	
	echo '<table style="display: none" id="edycja" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=lekarze" method="post">';
	echo '<tr><td>ID lekarza</td><td><input type="text" id="a" name="id_lekarze_e"></tr>
	<tr><td>Login</td><td><input type="text" id="b" name="login"></tr>
	<tr><td>Hasło</td><td><input type="text" id="c" name="haslo"></tr>
	<tr><td>Imię</td><td><input type="text" id="d" name="imie"></tr>
	<tr><td>Nazwisko</td><td><input type="text" id="e" name="nazwisko"></tr>
	<tr><td>Specjalizacja</td><td>';
	echo '<select id="f" name="id_specjalizacji">';
	$zapytanie_s="SELECT * FROM specjalizacje";
    $wykonaj_s=mysqli_query($link,$zapytanie_s);
    while($wiersz_s=mysqli_fetch_assoc($wykonaj_s)) {
     echo '<option value="' . $wiersz_s['id_specjalizacji'] . '">' . $wiersz_s['nazwa_specjalizacji'] . ' - ID: ' . $wiersz_s['id_specjalizacji'] . '</option>';
    }
    echo '</select>';
	echo '</tr><tr><td>Nr pokoju</td><td><input type="text" id="g" name="nr_pokoju"></td>';
	echo '</table><input style="display: none" id="p_edycja" type="submit" value="Edytuj">';
	echo '</form><br><br>';
	echo '<table style="display: none" id="dodaj" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=lekarze" method="post">';
	echo '<tr><td>Login</td><td><input type="text" name="login_d"></tr>
	<tr><td>Hasło</td><td><input type="text" name="haslo"></tr>
	<tr><td>Imię</td><td><input type="text" name="imie"></tr>
	<tr><td>Nazwisko</td><td><input type="text" name="nazwisko"></tr>
	<tr><td>Specjalizacja</td><td>';
	echo '<select name="id_specjalizacji">';
	$zapytanie_s="SELECT * FROM specjalizacje";
    $wykonaj_s=mysqli_query($link,$zapytanie_s);
    while($wiersz_s=mysqli_fetch_assoc($wykonaj_s)) {
    echo '<option value="' . $wiersz_s['id_specjalizacji'] . '">' . $wiersz_s['nazwa_specjalizacji'] . ' - ID: ' . $wiersz_s['id_specjalizacji'] . '</option>';
    }
    echo '</select>';
	echo '</tr><tr><td>Nr pokoju</td><td><input type="text" name="nr_pokoju"></tr>';
	echo '</table><input style="display: none" id="p_dodaj" type="submit" value="Dodaj">';
	echo '</form></div>';
	
	} else {
	echo '<br> Lista lekarzy <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>Imię</td><td>Nazwisko</td><td>Specjalizacja</td><td>Pokój</td>";
	$zapytanie = "select * from widok_lekarze";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['imie']."</td>
	<td>".$wiersz['nazwisko']."</td>
	<td>".$wiersz['specjalizacja']."</td>
	<td>".$wiersz['nr_pokoju']."</td>";
	}
	echo '</table>';
	}
	
	break;


	case "specjalizacje":
	if ($_SESSION['baza'] == 'dyrektor') {
	
	if(isset($_GET['id_specjalizacji_u'])) {
	$zapytanie_u="DELETE from specjalizacje WHERE id_specjalizacji='".$_GET['id_specjalizacji_u']."'";
	$wykonaj_u = mysqli_query($link, $zapytanie_u);
	header('Location: wyswietl.php?l='.$_GET['l']);
	}
    if(isset($_POST['id_specjalizacji_e'])) { 
    $zapytanie_e="UPDATE specjalizacje SET nazwa_specjalizacji='".$_POST['nazwa_specjalizacji']."' WHERE id_specjalizacji='".$_POST['id_specjalizacji_e']."'";
    $wykonaj_e = mysqli_query($link, $zapytanie_e);
    header('Location: wyswietl.php?l='.$_GET['l']);
	}
    if(isset($_POST['nazwa_specjalizacji_d'])) { 
    $zapytanie_d="INSERT into specjalizacje (nazwa_specjalizacji) values('".$_POST['nazwa_specjalizacji_d']."')";
    $wykonaj_d=mysqli_query($link,$zapytanie_d);
    header('Location: wyswietl.php?l='.$_GET['l']);
 }
    
    
	echo '<button onclick="dodaj()">Dodaj</button>&nbsp;<button onclick="edycja()">Edytuj</button><br>';
	echo '<br> Lista specjalizacji <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>ID Specjalizacji</td><td>Nazwa specjalizacji</td><td></td><td></td>";
	$zapytanie = "select * from specjalizacje";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['id_specjalizacji']."</td>";
	echo "<td>".$wiersz['nazwa_specjalizacji']."</td>";
	echo '<td><button onclick="dane(\'' . $wiersz['id_specjalizacji'] . '\',\'' . $wiersz['nazwa_specjalizacji'] . '\')">Edytuj</button></td>';
	echo '<td><a href="wyswietl.php?l=specjalizacje&id_specjalizacji_u=' . $wiersz['id_specjalizacji'] . '"/><button>Usuń</button></td>';
	}
	echo '</table><br><div style="height:150px;">';
	
	echo '<table id="edycja" style="display: none" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=specjalizacje" method="post">';
	echo '<tr><td>ID specjalizacji</td><td><input type="text" id="a" name="id_specjalizacji_e"></tr>
	<tr><td>Nazwa specjalizacji</td><td><input type="text" id="b" name="nazwa_specjalizacji"><td>';
	echo '</table><input id="p_edycja" style="display: none" type="submit" value="Edytuj">';
	echo '</form><br><br>';
	echo '<table style="display: none" id="dodaj" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=specjalizacje" method="post">';
	echo '<tr><td>Nazwa specjalizacji</td><td><input type="text" name="nazwa_specjalizacji_d"><td>';
	echo '</table><input style="display: none" id="p_dodaj" type="submit" value="Dodaj">';
	echo '</form></div>';
	
	} else {
	echo '<br> Lista specjalizacji <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>Nazwa specjalizacji</td>";
	$zapytanie = "select * from specjalizacje";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['nazwa_specjalizacji']."</td>";
	}
	echo '</table>';
	}
	break;
	
	case "recepcja":
    if ($_SESSION['baza'] == 'dyrektor') {
    if(isset($_GET['id_recepcjonisty_u'])) {
    $zapytanie_u="DELETE from recepcja WHERE id_recepcjonisty='".$_GET['id_recepcjonisty_u']."'";
    $wykonaj_u = mysqli_query($link, $zapytanie_u);
    header('Location: wyswietl.php?l='.$_GET['l']);
	}
    if(isset($_POST['id_recepcjonisty_e'])) { 
    $zapytanie_e="UPDATE recepcja SET imie='".$_POST['imie']."', nazwisko='".$_POST['nazwisko']."', login='".$_POST['login']."', haslo='".$_POST['haslo']."' WHERE id_recepcjonisty='".$_POST['id_recepcjonisty_e']."'";
    $wykonaj_e = mysqli_query($link, $zapytanie_e);
    header('Location: wyswietl.php?l='.$_GET['l']);
}
    if(isset($_POST['login_d'])) { 
    $zapytanie_d="INSERT into recepcja (imie, nazwisko, login, haslo) values('".$_POST['imie']."', '".$_POST['nazwisko']."', '".$_POST['login_d']."', '".$_POST['haslo']."')";
    $wykonaj_d=mysqli_query($link,$zapytanie_d);
	header('Location: wyswietl.php?l='.$_GET['l']);
    }
    
    
	echo '<button onclick="dodaj()">Dodaj</button>&nbsp;<button onclick="edycja()">Edytuj</button><br>';
	echo '<br> Lista recepcjonistów <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>ID Recepcjonisty</td><td>Login</td><td>Hasło</td><td>Imię</td><td>Nazwisko</td><td></td><td></td>";
	$zapytanie = "select * from recepcja";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['id_recepcjonisty']."</td>";
	echo "<td>".$wiersz['login']."</td>";
	echo "<td>".$wiersz['haslo']."</td>";
	echo "<td>".$wiersz['imie']."</td>";
	echo "<td>".$wiersz['nazwisko']."</td>";
	echo '<td><button onclick="dane(\'' . $wiersz['id_recepcjonisty'] . '\',\'' . $wiersz['login'] . '\',\'' . $wiersz['haslo'] . '\',\'' . $wiersz['imie'] . '\',\'' . $wiersz['nazwisko'] . '\')">Edytuj</button></td>';
	echo '<td><a href="wyswietl.php?l=recepcja&id_recepcjonisty_u=' . $wiersz['id_recepcjonisty'] . '"/><button>Usuń</button></td>';
	}
	echo '</table><br><div style="height:150px;">';
	
	echo '<table id="edycja" style="display: none" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=recepcja" method="post">';
	echo '<tr><td>ID recepcjonisty</td><td><input type="text" id="a" name="id_recepcjonisty_e"></tr>
	<tr><td>Login</td><td><input type="text" id="b" name="login"><td>
	<tr><td>Hasło</td><td><input type="text" id="c" name="haslo"><td>
	<tr><td>Imię</td><td><input type="text" id="d" name="imie"><td>
	<tr><td>Nazwisko</td><td><input type="text" id="e" name="nazwisko"><td>';
	echo '</table><input id="p_edycja" style="display: none" type="submit" value="Edytuj">';
	echo '</form><br><br>';
	echo '<table style="display: none" id="dodaj" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=recepcja" method="post">';
	echo '<tr><td>Login</td><td><input type="text" name="login_d"><td>
	<tr><td>Hasło</td><td><input type="text" name="haslo"><td>
	<tr><td>Imię</td><td><input type="text" name="imie"><td>
	<tr><td>Nazwisko</td><td><input type="text" name="nazwisko"><td>';
	echo '</table><input style="display: none" id="p_dodaj" type="submit" value="Dodaj">';
	echo '</form></div>';
	
	}
    break;
    
    case "pacjenci":
    if ($_SESSION['baza'] == 'dyrektor') {
	
    if(isset($_GET['id_osoby_u'])) {
    $zapytanie_u="DELETE from pacjenci WHERE id_pacjenta='".$_GET['id_osoby_u']."'";
    $wykonaj_u = mysqli_query($link, $zapytanie_u);
    header('Location: wyswietl.php?l='.$_GET['l']);
	}
    if(isset($_POST['id_osoby_e'])) { 
    $zapytanie_e="UPDATE pacjenci SET imie='".$_POST['imie_osoby']."', nazwisko='".$_POST['nazwisko_osoby']."', pesel='".$_POST['pesel']."', nr_telefonu='".$_POST['tel']."', kod='".$_POST['kod']."' WHERE id_pacjenta='".$_POST['id_osoby_e']."'";
    $wykonaj_e = mysqli_query($link, $zapytanie_e);
    header('Location: wyswietl.php?l='.$_GET['l']);
	}
    if(isset($_POST['imie_osoby_d'])) { 
    $zapytanie_d="INSERT into pacjenci (imie, nazwisko, pesel, nr_telefonu, kod) values('".$_POST['imie_osoby_d']."', '".$_POST['nazwisko_osoby']."', '".$_POST['pesel']."', '".$_POST['tel']."', '".$_POST['kod']."')";
    $wykonaj_d=mysqli_query($link,$zapytanie_d);
    header('Location: wyswietl.php?l='.$_GET['l']);
	}
    
    
	echo '<button onclick="dodaj()">Dodaj</button>&nbsp;<button onclick="edycja()">Edytuj</button><br>';
	echo '<br> Lista Pacjentów <br>';
	echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>ID Pacjenta</td><td>Imię</td><td>Nazwisko</td><td>Pesel</td><td>Numer telefonu</td><td>Kod</td><td></td><td></td><td></td>";
	$zapytanie = "select * from pacjenci";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['id_pacjenta']."</td>
    	<td>".$wiersz['imie']."</td>
    	<td>".$wiersz['nazwisko']."</td>
    	<td>".$wiersz['pesel']."</td>
	<td>".$wiersz['nr_telefonu']."</td>
    	<td>".$wiersz['kod']."</td>
	<td>" . '<td><a href="wyswietl.php?l=spotkania&karta_pacjenta=' . $wiersz['id_pacjenta'] . '"/><button>Karta pacjenta</button></td>';
    	echo '<td><button onclick="dane(\'' . $wiersz['id_pacjenta'] . '\',\'' . $wiersz['imie'] . '\',\'' . $wiersz['nazwisko'] . '\',\'' . $wiersz['pesel'] . '\',\'' . $wiersz['nr_telefonu'] . '\',\'' . $wiersz['kod'] . '\')">Edytuj</button></td>';
	echo '<td><a href="wyswietl.php?l=pacjenci&id_osoby_u=' . $wiersz['id_pacjenta'] . '"/><button>Usuń</button></td>';
	}
	echo '</table><br><div style="height:150px;">';
	
	echo '<table id="edycja" style="display: none" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=pacjenci" method="post">';
	echo '<tr><td>ID Pacjenta</td><td><input id="a" type="text" name="id_osoby_e"></tr>
	<tr><td>Imię</td><td><input id="b" type="text" name="imie_osoby"><td>
	<tr><td>Nazwisko</td><td><input id="c" type="text" name="nazwisko_osoby"><td>
	<tr><td>Pesel</td><td><input id="d" type="text" name="pesel"><td>
	<tr><td>Numer telefonu</td><td><input id="e" type="text" name="tel"><td>
	<tr><td>Kod</td><td><input id="f" type="text" name="kod"><td>';
	echo '</table><input id="p_edycja" style="display: none" type="submit" value="Edytuj">';
	echo '</form><br><br>';
	echo '<table style="display: none" id="dodaj" border="1" cellspacing="0" cellpadding="0">';
	echo '<form action="wyswietl.php?l=pacjenci" method="post">';
	echo '<tr><td>Imię</td><td><input type="text" name="imie_osoby_d"><td>
	<tr><td>Nazwisko</td><td><input type="text" name="nazwisko_osoby"><td>
	<tr><td>Pesel</td><td><input type="text" name="pesel"><td>
	<tr><td>Numer telefonu</td><td><input type="text" name="tel"><td>
	<tr><td>Kod</td><td><input type="text" name="kod"><td>';
	echo '</table><input style="display: none" id="p_dodaj" type="submit" value="Dodaj">';
	echo '</form></div>';
	
	}
    break;

	}

echo'<br><br><a href="recepcja.php">Powrót</a>';	
} else {
header('Location: recepcja.php');
}



?>







	
	

	</table>
	
	
	
	
	
	
	</td>
    <td rowspan="2" width="2"></td>
  </tr>
</table>
<script>
var dod = document.getElementById('dodaj');
var pdodaj = document.getElementById('p_dodaj');
var edytuj = document.getElementById('edycja');
var pedytuj = document.getElementById('p_edycja');

function dodaj() {
    if (dod.style.display === 'none') {
        dod.style.display = '';
        pdodaj.style.display = '';
        edytuj.style.display = 'none';
        pedytuj.style.display = 'none';
    } else {
        dod.style.display = 'none';
        pdodaj.style.display = 'none';
    }
}


function edycja() {
    if (edytuj.style.display === 'none') {
        edytuj.style.display = '';
        pedytuj.style.display = '';
        dod.style.display = 'none';
        pdodaj.style.display = 'none';
    } else {
        edytuj.style.display = 'none';
        pedytuj.style.display = 'none';
    }
}

function dane(a, b, c, d, e, f, g) {
dod.style.display = 'none';
pdodaj.style.display = 'none';
edytuj.style.display = '';
pedytuj.style.display = '';
document.getElementById("a").value = a;
document.getElementById("b").value = b;
document.getElementById("c").value = c;
<?php
if ($_GET['l']=='spotkania') {
echo 'z_stan(\'d\', \'c\');';
}
?>

document.getElementById("d").value = d;
document.getElementById("e").value = e;
document.getElementById("f").value = f;
document.getElementById("g").value = g;
}

<?php
if ($_GET['l']=='spotkania') {
echo "var lekarze = [";
$s="SELECT * FROM lekarze";
$w=mysqli_query($link,$s);
while($wiersz=mysqli_fetch_assoc($w)) {
echo "['" . $wiersz['id_specjalizacji'] . "', '" . $wiersz['id_lekarza'] . "', '" . $wiersz['imie'] . " " . $wiersz['nazwisko'] . " - ID: " . $wiersz['id_lekarza'] . "'], ";
}
echo "];
function z_stan(id, sel){
document.getElementById(id).innerHTML = '';
for (var j = 0; j < lekarze.length; j++){
if(lekarze[j][0] == document.getElementById(sel).value) {
var x = document.getElementById(id);
    var opt = document.createElement('option');
    opt.value = lekarze[j][1];
    opt.innerHTML = lekarze[j][2];
    x.appendChild(opt);
}
}
}

function karta() {
var karta = document.getElementById('karta');
var p = document.getElementById('p_karta');
    if (p.style.display === 'none') {
	document.getElementById('karta').readOnly = false;
        p.style.display = '';
    } else {
	document.getElementById('karta').readOnly = true;
        p.style.display = 'none';
    }
}
";
}
?>
</script>


<br>
<table border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td style="background: rgba(42,42,42,0.4);" align="center"><font face="verdana" size="1" color="white">
      &nbsp&nbspCopyright &copy; <b>Łukasz Szkaradek, Łukasz Mamak i Paweł Wilczek&nbsp&nbsp</b></td>
  </tr>




</body>
</html>