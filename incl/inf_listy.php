<?php
echo "informacje dodatkowe"
/*
switch ($_SESSION['prawa']){
	case 4: //admin - sł. ter, n.służby, koordynator
		if (isset($_GET['id'])){
			switch ($_GET['id']){
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
			$result = mysql_query("SELECT gr FROM $db_glos WHERE usrn = '$_SESSION[user]'");
			$user = mysql_fetch_array($result);
			$grnr = $user['gr'];			
		}
		break;
	case 2: //n.grupy, zast, n. grupy	
		$result = mysql_query("SELECT gr FROM $db_glos WHERE usrn = '$_SESSION[user]'");
		$user = mysql_fetch_array($result);
		$grnr = $user['gr'];			
		break;
}
if($_SESSION['prawa']>=2){
	if (isset($grnr)){		
		$grupa = mysql_query("SELECT * FROM $db_glos WHERE nazw = 'grupa' AND imie = '$grnr'");
		if ($grupa){
			$dane = mysql_fetch_assoc($grupa);
		
			echo "<h3>GRUPA</h3>";
			echo "<h1>".$grnr."</h1>";
			
			if($_SESSION['prawa']==4){
				
				echo "<br /><br />";
				
				$maxgrp = mysql_fetch_assoc(mysql_query("SELECT MAX(gr) as nr FROM $db_glos"));
				for($gr=1;$gr<=$maxgrp['nr'];$gr++){
					echo"<button class=\"gr_nr\" type=\"button\" value=\"".$gr."\">".$gr."</button>";
				}			
			}
			
			echo "<br><br>";
			
			echo '<script type="text/javascript" src="https://www.google.com/jsapi"></script>';
			
?>

<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
		
          ['rodzaj', 'ilość'],

<?php
			if(date('m')>10){ // bądź równy jeśli chcesz już we wrześniu raport za nowy rok, ale nie ma jeszcze opracowań !				
				$prs = date('Y')."-08-16";
				$krs = date('Y')+1 ."-08-31";
			}else{
				$prs = date('Y')-1 ."-08-16";
				$krs = date('Y')."-08-31";
			}
			
			$caly_grupowo_pyt = mysql_query("SELECT count(*) as oprgrp FROM $db_kart WHERE dod>='$prs' and ddo<='$krs' AND idglo  IN(SELECT idglo FROM $db_glos WHERE nazw = 'grupa' AND imie = '$grnr' )"); 
			if ($caly_grupowo_pyt){ 
				$caly_grupowo_odp = mysql_fetch_assoc($caly_grupowo_pyt);
				$caly_grupowo = $caly_grupowo_odp['oprgrp'];
			}else{
				$caly_grupowo = 0;
			}
			
			$caly_indywid_pyt = mysql_query("SELECT count(*) as oprind FROM $db_kart WHERE idglo NOT IN(SELECT idglo FROM $db_glos WHERE nazw = 'grupa' AND imie = '$grnr' ) AND idter IN(SELECT idter FROM $db_trny WHERE grp = '$grnr' AND rdz=0) AND dod>='$prs' and ddo<='$krs'");
			if ($caly_indywid_pyt){ 
				$caly_indywid_odp = mysql_fetch_assoc($caly_indywid_pyt);
				$caly_indywidualnie = $caly_indywid_odp['oprind'];
			}else{
				$caly_indywidualnie = 0;
			}
			
			echo "['Grupowo', ".$caly_grupowo."],";
			echo "['Indywidualnie', ".$caly_indywidualnie."],";
			echo "]);";
?>	  

        var options = {
          pieHole: 0.4,		  
		  slices: {
            0: { color: '#b3b3b3' },
		    1: { color: '#808080' },
		  }
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>

<?php
			
			
			echo '<script type="text/javascript">';
?>
google.load('visualization', '1', {packages: ['corechart', 'bar']});
google.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = google.visualization.arrayToDataTable([
        ['razy', 'ilość',{ role: 'style' }],
<?php
		$rodzaj = 0;
		unset($razy);
		unset($tmp_razy);
		$razy[0] = 0;
		$razy[1] = 0;
		$razy[2] = 0;
		$razy[3] = 0;
		$razy[4] = 0;
		$razy[5] = 0;
		$razy[6] = 0;
		$razy[7] = 0;
		$razy[8] = 0;
		$razy[9] = 0;
			
		$pyt = mysql_query("SELECT idter FROM $db_trny WHERE aktv = 1 AND rdz = $rodzaj");
		if($pyt){			
			while ($teren = mysql_fetch_assoc($pyt)) {
				// SPRAWDZENIE ILE RAZY OPRACOWANY				
				$pyt_razy = mysql_query("SELECT count(*) as ile_razy FROM $db_kart WHERE idter = $teren[idter] AND dod>='$prs' and ddo<='$krs'");				
					
				if($pyt_razy){
					$odp_razy = mysql_fetch_assoc($pyt_razy);
					$ile_razy = $odp_razy['ile_razy'];
				}else{
					$ile_razy = 0;
				}
				//--------------------------------------
				
				$razy[$ile_razy] = $razy[$ile_razy] + 1; // ??? jeszcze trzeba ?
			}
		}
		
		foreach($razy as $klucz => $w){
			if($w>0){
				$tmp_razy[] = array('ile' => $klucz, 'razy' => $w);
			}
		}
		$kolor[0]='#cccccc';
		$kolor[1]='#bfbfbf';
		$kolor[2]='#b3b3b3';
		$kolor[3]='#a6a6a6';
		$kolor[4]='#999999';
		$kolor[5]='#8c8c8c';
		$kolor[6]='#808080';
		$kolor[7]='#737373';
		$kolor[8]='#ff6600';
		$kolor[9]='#ff0000';
		
		foreach($tmp_razy as $klucz){
			if ($klucz['ile']==1){
				echo "['".$klucz['ile']." raz', ".$klucz['razy'].",'".$kolor[$klucz['ile']]."'],";
			}else{
				echo "['".$klucz['ile']." razy', ".$klucz['razy'].",'".$kolor[$klucz['ile']]."'],";
			}			
		}
		echo "]);";

?>
      var options = {
        legend: { position: "none" },
        
        hAxis: {
          title: 'ilość terenów cząstkowych',
          minValue: 0
        },
        
      };

      var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

      chart.draw(data, options);
    }
<?php
			echo '</script>';
			echo '<div id="donutchart" style="width: 350px; height: 180px;"></div>';
			echo '<div id="chart_div" style="width: 340px; height: 160px;margin-top:-30px;"></div>';
			
			echo '<p class="wskazowki">';
			
			//przekroczenia terminów
			
			
			//$pyt = mysql_query("SELECT * FROM $db_glos WHERE idglo IN(SELECT idglo FROM $db_tereny WHERE aktv = 1 AND rdz = $rodzaj AND stus = 0 AND grp = $grnr AND dod>='$prs' and ddo<='$krs' AND dod =0 )");
			
			//SELECT count(*) as oprind FROM $db_kart WHERE idglo NOT IN(SELECT idglo FROM $db_glos WHERE nazw = 'grupa' ) AND idter IN(SELECT idter FROM $db_trny WHERE grp<>0 AND rdz=0) AND dod>='$prs' and ddo<='$krs'");
			
			//równomierność
			$licz_rowno
			for($i=0;$i<=9;$i++){
				if($razy[$i]>0){
					$licz_rowno++;
				}
			}
			if($licz_rowno>3){
				echo "nierównomiernie<br />";
			}
			
			//praca grupowa
			if ($caly_indywidualnie > ($caly_grupowo + $caly_indywidualnie) *0.25){
				echo "za dużo terenów opracowanych indywidualnie <br />";
			}
			
			//kampanie
			// sprawdz czy akualnie trwa jakaś kampania i jeśli trwa to:
			// porównaj datę bieżącą z datą po rozpoczęciu ostatnio rozpoczętej lub wskazanej kampanii i sprawdź czy są tereny rozpoczęte od tej daty i nieopracowane i opracowane z publikacją na kampanię.
			// echo"opracowano: ";
			// echo"w trakcie opracowania: ";
			// echo"nie rozpoczęto: ";
			echo '</p>';
		
		}
			
			
			
	}
}
*/
?>