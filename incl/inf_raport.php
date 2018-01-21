<?php

	echo "<br /><br /><br /><br />";
	
	$db = db_lacz(); //main_cfg
	$db['link']->select_db($dbZbTeren . $_SESSION['zbornr']);
			
	if (isset($_GET['grupa'])){
		switch ($_GET['grupa']){
			case 1: $grnr = 1; break;
			case 2: $grnr = 2; break;
			case 3: $grnr = 3; break;
			case 4: $grnr = 4; break;
			case 5: $grnr = 5; break;
			case 6: $grnr = 6; break;
			case 7: $grnr = 7; break;
			case 8: $grnr = 8; break;
			case 9: $grnr = 9; break;
			default: echo "błąd"; break;
		}
	}else{
		$pytanko = @$db['link'] -> query("SELECT gr FROM $tbGlos WHERE idglo = $_SESSION[oper]");
		$dane = $pytanko->fetch_assoc();
		$grnr = $dane['gr'];
		$pytanko->free();
	}
			
	echo '<h1>'. $grnr .'</h1>';
	echo '<h3>grupa</h3>';
	echo '<br />';
	
	//--------------LICZENIE-------------------------------------------------------
	$pyt = @$db['link'] -> query("SELECT count(*) as terAll FROM $tbTereny WHERE aktv = 1 AND rdz IN (0,1) AND grp='$grnr'");
	$dane = $pyt->fetch_assoc();
	$terAll = $dane['terAll'];
	$pyt->free();
	unset($dane);
	
	$pyt = @$db['link'] -> query("SELECT count(*) as terDoor FROM $tbTereny WHERE aktv = 1 AND rdz = 0 AND grp='$grnr'");
	$dane = $pyt->fetch_assoc();
	$terDoor = $dane['terDoor'];
	$pyt->free();
	unset($dane);
	
	$pyt = @$db['link'] -> query("SELECT count(*) as terShop FROM $tbTereny WHERE aktv = 1 AND rdz = 1 AND grp='$grnr'");
	$dane = $pyt->fetch_assoc();
	$terShop = $dane['terShop'];
	$pyt->free();
	unset($dane);
	
	echo '<div class="blok"><p class="tekst">';
		echo 'wszystkich terenów: '. $terAll .'<br />';
		echo 'terenów domowych: '. $terDoor .'<br />';
		echo 'terenów handlowych: '. $terShop .'<br />';
	echo '</p></div>';
	
	unset($terAll);
	unset($terDoor);
	unset($terShop);
	
/*	
	//----------------------OSTATNIA MODYFIKACJA ------------------------------------
	echo '<p class="wskazowki">';
		$pyt = @$db['link'] -> query("SELECT MAX(dwpr) as dwp, MAX(dzdn) as dzd FROM $tbS13 WHERE idter IN (SELECT idter FROM $tbTereny WHERE aktv=1 AND grp='$grnr')");
		if($pyt<>false){
			$dane = $pyt->fetch_assoc();
			$pyt->free();
			//echo $dane['dwp'].'+'.$dane['dzd'].'<br/>';
			if($dane['dwp']<$dane['dzd']) echo 'ostatnia modyfikacja kartoteki dnia: '. date('d',strtotime($dane['dzd'])) .'.'. date('m',strtotime($dane['dzd'])) .'.'. date('Y',strtotime($dane['dzd'])) .'<br />';		
			else echo 'ostatnia modyfikacja kartoteki dnia: '. date('d',strtotime($dane['dwp'])) .'.'. date('m',strtotime($dane['dwp'])) .'.'. date('Y',strtotime($dane['dwp'])) .'<br />';		
			unset($dane);
		}else{
			echo 'ostatnia modyfikacja kartoteki dnia:<br/>brak danych.';
		}
	echo '</p>';		
*/
	
	
	echo '<div class="bledy">';
		echo '<p class="tekst">błędy</p>';		
	echo '</div>';



	//----------------------PODZIAŁ DANE--------------------------------------------
	$pyt = @$db['link'] -> query("SELECT count(*) as terPobr FROM $tbTereny WHERE aktv = 1 AND rdz IN (0,1) AND grp='$grnr' AND stus = 1"); //pobrane
	$pobrane = $pyt->fetch_assoc();
	$pyt->free();
	
	$pyt = @$db['link'] -> query("SELECT count(*) as terDost FROM $tbTereny WHERE aktv = 1 AND rdz IN (0,1) AND grp='$grnr' AND stus = 0"); //dostępne
	$dostepne = $pyt->fetch_assoc();
	$pyt->free();

	//----------------------KAMPANIE------------------------------------------------
	//	$data_przg = date("Y-m-d", strtotime($data_rozp.' +21 days')); // przykład
	
	$pyt = @$db['link'] -> query("SELECT * FROM $tbKpn WHERE CURDATE() >= DATE_ADD(dod,INTERVAL -21 DAY) AND CURDATE() < dod"); //przygotówka do kampanii
	if($pyt!=false){
		$kampania = $pyt->fetch_assoc();
		$pyt->free();
		if(isset($kampania['id'])){
			echo '<div class="blok">';
			echo '<p class="tekst">kampanie</p>';
						
				$db['link']->select_db($dbOpcje);
				$pyt = @$db['link'] -> query("SELECT nazwa FROM $tbKpNaz WHERE rdz=$kampania[rdz]");
				$kampania_rdz=$pyt->fetch_assoc();
				$pyt->free();
				$db['link']->select_db($dbZbTeren . $_SESSION['zbornr']);
				
				$zaXdni = round((strtotime($kampania['dod']) - strtotime(date("Y-m-d")))/86400);
				//=round((strtotime($dzisiaj)-strtotime($wyn['czas']))/86400); 
				
				echo '<p class="opis"><b>'.$zaXdni.'</b> dni do rozpoczęcia kampanii<br />'.$kampania_rdz['nazwa'].'<br />';			
				//echo '<br />w terminie od '.$kampania['dod'].' do '.$kampania['ddo'];
				
				if($pobrane['terPobr']>0) echo "<br />Zaplanuj zakończenie opracowania <b>".$pobrane['terPobr']."</b> rozpoczętych terenów.";
				if($pobrane['terPobr']>10) echo "Dobrze przemyśl rozpoczęcie nowego terenu.";
			
			echo '</p></div>';
		}else{
			
			$pyt = @$db['link'] -> query("SELECT * FROM $tbKpn WHERE CURDATE() >= dod AND CURDATE() < ddo"); //pobrane			

			if($pyt<>false){		
				$kampania = $pyt->fetch_assoc();
				$pyt->free();
				echo '<div class="blok">';
				echo '<p class="tekst">kampanie</p><p class="opis">';
					
					$db['link']->select_db($dbOpcje);
					$pyt = @$db['link'] -> query("SELECT nazwa FROM $tbKpNaz WHERE rdz=$kampania[rdz]");
					$kampania_rdz=$pyt->fetch_assoc();
					$pyt->free();
					$db['link']->select_db($dbZbTeren . $_SESSION['zbornr']);
					$zaXdni = round((strtotime($kampania['ddo']) - strtotime(date("Y-m-d")))/86400);					
					
					echo "Do zakończenia kampanii<br />".$kampania_rdz['nazwa']."<br />pozostało <b>".$zaXdni."</b> dni.";
					
					//---rozpoczętych----
					$zaczetych = $opracowanych = $zaczetychPrzed = 0;
					$pytTer = @$db['link'] -> query("SELECT idter FROM $tbTereny WHERE aktv = 1 AND rdz IN (0,1) AND grp='$grnr'");
					while($teren=$pytTer->fetch_assoc()){
						//echo $teren['idter'];
						$pytPrzed = @$db['link'] -> query("SELECT count(*) as ile FROM $tbS13 WHERE idter = $teren[idter] AND dod<='$kampania[dod]' AND ddo='0000-00-00'");
						if ($pytPrzed != false){ $odp_przed = $pytPrzed->fetch_assoc(); if($odp_przed['ile']>0) {$zaczetychPrzed++;}}
						$pytZacz = @$db['link'] -> query("SELECT count(*) as ile FROM $tbS13 WHERE idter = $teren[idter] AND dod>='$kampania[dod]' AND ddo='0000-00-00'");
						if ($pytZacz !== false){ $odp_zacz = $pytZacz->fetch_assoc(); if($odp_zacz['ile']>0) {$zaczetych++;}}
						$pytOpr = @$db['link'] -> query("SELECT count(*) as ile FROM $tbS13 WHERE idter = $teren[idter] AND dod>='$kampania[dod]' AND ddo=DATE_ADD('$kampania[ddo]',INTERVAL 14 DAY) AND zczym=5");
						if ($pytOpr != false){ $odp_opr = $pytOpr->fetch_assoc(); if($odp_opr['ile']>0) {$opracowanych++;}}
					}
					$pytTer->free();
					$pytZacz->free();
					$pytOpr->free();
					echo "<br />Terenów nie ukończonych przed kampanią: ".$zaczetychPrzed;
					echo "<br />Terenów zaczętych w kampanii: ".$zaczetych;
					echo "<br />Terenów opracowanych z zaproszeniami: ".$opracowanych;
				echo '</p></p></div>';
			}				
		}
	}
	

	//-----------------------PODZIAŁ------------------------------------------------
	include_once('LabChartsBar.php');
	include_once('LabChartsPie.php');
	
	echo '<div class="blok">';
	echo '<p class="tekst">stan bieżący</p>';
	
	$wykres_tereny = new LabChartsPie();
		$wykres_tereny->setData(array($pobrane['terPobr'],$dostepne['terDost']));		
		//$wykres_tereny->setTitle('Wizualizacja stanu terenu');
		$wykres_tereny->setSize('266x100');
		$wykres_tereny->setColors('A7AAAD|7A7D7F');
		//$wykres_tereny->setBackground('ffffff');
		//$opis = 'oczekujące&nbsp;('. $dostepne['terDost'] .')|rozpoczęte&nbsp;(' . $pobrane['terPobr'] .')';
		//$wykres_tereny->setLabels($opis);
	echo "<img src=" . $wykres_tereny->getChart() ." class=\"wykres\"><br />";
	
	echo '<p class="opis">';
	//echo $opis;
		echo 'opracowanie rozpoczęte : '. $pobrane['terPobr'] .'<br />';
		echo 'oczekujące: '. $dostepne['terDost'] .'<br />';
	echo '</p></div>';
	
	//-----------------CZĘSTOTLIWOŚĆ-------------------------------

	if(date('m')>9){					
		$prs = date('Y')-1 ."-08-16"; //od kwietnia, bo jak od sierpnia (-08-16) to była pusta tabelka i nie wiadomo co ostatnio było podawane
		$krs = date('Y') ."-08-31";
	}else{
		$prs = date('Y')-1 ."-08-16"; // UWAGA <- ABY WYŚWIETLAL NIE TYLKO OSTATNI ROK SLUZBOWY DALEM MINUS 2 LATA !!!!
		$krs = date("Y-m-d"); //date('Y')."-08-31";
	}
	
	unset($razy);
	unset($tmp_razy);
	
	for($x=0;$x<10;$x++){$razy[$x] = 0;} //przygotowanie tablicy ilości opracowań $razy

	$wykresKolumnowyKolory = array('#0086b3','#0099cc','#00ace6','#00bfff','#4dd2ff','#66d9ff','#80dfff','#99e6ff','#b3ecff','#ccf2ff','#e6f9ff');
	$wykresKolumnowyKolory = array('#e6f9ff','#ccf2ff','#99e6ff','#99e6ff','#80dfff','#66d9ff','#4dd2ff','#00bfff','#00ace6','#0099cc','#0086b3');
	
	$razyJest=0;
	$pytTer = @$db['link'] -> query("SELECT idter FROM $tbTereny WHERE aktv = 1 AND rdz IN (0,1) AND grp='$grnr'");
	while($teren=$pytTer->fetch_assoc()){
		$pytLb = @$db['link'] -> query("SELECT count(*) as razy FROM $tbS13 WHERE idter = $teren[idter] AND dod>='$prs' and ddo<='$krs'");
		if ($pytLb == false){
			$razy[0]++;
		}else{
			$dane = $pytLb->fetch_assoc();
			$razy[$dane['razy']]++;
			$razyJest++;
		}
	} 
/*	//tylko do prób
	$razy[0] = 0;
	$razy[1] = 44;
	$razy[2] = 0;
	$razy[3] = 0;
	$razy[4] = 3;
	$razy[5] = 22;
	$razy[6] = 66;
	$razy[7] = 0;
	$razy[8] = 0;
	$razy[9] = 0;
*/	
	
	for($x=1;$x<10;$x++){
		if($razy[$x]>0)$razyMax=$x;  //szukanie maksymalnej liczby opracowań
	}
	echo '<div class="blok">';
	echo '<div class="wykresKolumnowy" id="wykresKolumnowy" >';
		echo '<div class="nag">równomierność opracowań</div>';
		echo '<div class="kLewaN">razy</div><div class="kPrawa">terenów</div>';		
		for($x=0;$x<=$razyMax+1;$x++){
			$width = ($razy[$x] / max($razy));
			echo '<div class="kLewa">'.$x.'</div><div class="kPrawaF" style="background-color:'.$wykresKolumnowyKolory[$x].';width:'. round($width*182) .'px;">'.$razy[$x].'</div><div class="kPrawaE" style="width:'. ((round((1-$width)*182))-5) .'px;"></div><div class="br"></div>';		
		}
	echo '</div></div>';
	
/*	//tylko do testów
	echo '<p class="tekst">';
		for($x=0;$x<=9;$x++){
			echo $x ." raz : ". $razy[$x] ." terenów<br />";
			
		}
	echo '</p>';
*/	
?>