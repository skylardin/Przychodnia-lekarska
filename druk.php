<?php
ob_start();
date_default_timezone_set('Europe/Warsaw');
session_start();
include("connect_db.php");
?>
<html lang="pl-PL">
<head>
<title>Przychodnia lekarska.</title>
<style type="text/css" media="print">
textarea {
  width: 100%;
  height: 85%;
  resize: none;
}
  nav, footer, .adv, .ndop {
    display:none;
  }
@page 
{
    size: auto;
    margin: 0mm;
}
</style>
<head>
<body onload="load()">
<center> <h3>Karta pacjenta</h3><br>
<?php
if (isset($_GET['karta_pacjenta'])) {
if ($_SESSION['baza'] == 'dyrektor' or $_SESSION['baza'] == 'lekarze') {
echo '<table border="1" cellspacing="0" cellpadding="0">';
	echo "<td>Imię osoby</td><td>Nazwisko osoby</td><td>Pesel</td>";
	$zapytanie = "select * from pacjenci where id_pacjenta='".$_GET['karta_pacjenta']."'";
	$wykonaj = mysqli_query($link, $zapytanie);
	while($wiersz=mysqli_fetch_assoc($wykonaj)) {
	echo " <tr>
	<td>".$wiersz['imie']."</td>
	<td>".$wiersz['nazwisko']."</td>
	<td>".$wiersz['pesel']."</td>".'</table><br>';
	echo '</center><textarea readonly name="karta">' . $wiersz['karta_pacjenta'] . '</textarea>';
	}

} else {
header("Location: recepcja.php");
}
}
?>
</body>
<script>
function load() {
    window.print();
}
</script>
</html>
