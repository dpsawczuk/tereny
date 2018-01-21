<?php 

$strona_glowna = 'g';
$strona_informacji = 'sc';

$dbhost = 'localhost';
$dbuser = 'dawsaw_jw';
$dbpswd = '%sMzLm3p8L';
$bdnazwa = 'pl11163';

$dbOpcje = 'dawsaw_ter_options';
$dbZbTeren = 'dawsaw_ter_pl_407_';
$tbMenu = 'menu';
$tbUsers = 'users';
$tbZbory = 'zbory';
$tbLogs = 'logs';
$tbBlok = 'blokady';
$tbGlos = 'glosiciele';
$tbS13 = 'kartoteka';

$tbTereny = 'tereny';
$tbKpn = 'terminy';
$tbKpNaz = 'kampanie';

$db_glos = 'tereny_glosiciele';
$db_kart = 'tereny_kartoteka';
$db_trny = 'tereny_tereny';
$db_mnu  = 'menu';
/*
mysql_connect ($dbhost,$dbuser,$dbpswd) or die ('Nie udalo sie polaczyc z serwerem');
mysql_select_db ($bdnazwa) or die ('Nie udalo sie wybrac bazy');
mysql_query("set names 'utf8'");
*/
function db_lacz(){
	$polaczenie = @new mysqli("localhost", "dawsaw_jw", "%sMzLm3p8L");
	$wynik = @$polaczenie -> query("set names 'utf8'");
	$tab = array();
	if (mysqli_connect_errno() != 0){
		$tab['status'] = false;	
		return  $tab;
	}else {
		$tab['status'] = true;
		$tab['link'] = $polaczenie;
		return  $tab;
	}
}
?>