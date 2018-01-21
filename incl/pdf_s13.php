<?php
//============================================================+

require_once('tcpdf/config/lang/pol.php');
require_once('tcpdf/tcpdf.php');


class MYPDF extends TCPDF {

	public function Header() {		
		$this->SetY(-286);$this->SetX(22);
				$this->SetFont('helvetica', 'B', 14);
				$this->Cell(60, 10, 'Karta przydzialów terenu', 0, false, 'L', 0, '', 0, false, 'M', 'M');
				$this->SetFont('helvetica', 'B', 11);
		$this->SetY(-282);$this->SetX(100);
				$this->Cell(20, 8, 'Przyklad:', 0, false, 'L', 0, '', 0, false, 'M', 'M');
				$this->SetFont('times', '', 7.5);
				$this->Cell(20, 8, 'Nazwisko glosiciela', 0, false, 'L', 0, '', 0, false, 'M', 'M');
		$this->SetY(-277);$this->SetX(124.5);
				$this->Cell(20, 8, 'Data przydzialu', 0, false, 'L', 0, '', 0, false, 'M', 'M');
		$this->SetY(-284.6);$this->SetX(147);
				$this->SetFont('times', '', 9);
				$ramka = array('L' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
							   'T' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
							   'R' => array('width' => 0.4, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
							   'B' => array('width' => 0.1, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
				$this->MultiCell(30, 5.2, ' J.Kowalski ', $ramka, 'C', 0, 0, '', '', true,0,false,0,5,'M');
		$this->SetY(-279.5);$this->SetX(147);
				$ramka = array('L' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
							   'T' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
							   'R' => array('width' => 0.1, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
							   'B' => array('width' => 0.4, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
				$this->MultiCell(15, 5.2, '6.11.1996', $ramka, 'C', 0, 0, '', '', true,0,false,0,5,'M');
				$ramka = array('L' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
							   'T' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
							   'R' => array('width' => 0.4, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
							   'B' => array('width' => 0.4, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
				$this->MultiCell(15, 5.2, '8.03.1997', $ramka, 'C', 0, 0, '', '', true,0,false,0,5,'M');
				$this->SetFont('times', '', 8);
		$this->SetY(-277);$this->SetX(182.8);
				$this->Cell(20, 8, 'Data zdania', 0, false, 'L', 0, '', 0, false, 'M', 'M');
				
		/*
		$this->setJPEGQuality(75);
		// Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
		$this->Image('../img/arrowR.jpg', 143, 14.8, 2, 1, 'JPG', '', '', true, 300, '', false, false, 0, false, false, false);
		$this->Image('../img/arrowR.jpg', 143, 17, 2, 1, 'JPG', '', '', true, 300, '', false, false, 0, false, false, false);
		$this->Image('../img/arrowL.jpg', 173, 17, 2.2, 1.2, 'JPG', '', '', true, 300, '', false, false, 0, false, false, false);
		*/
		$this->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
		$this->SetFillColor(0, 0, 0);
		$this->Arrow(143, 15, 145.5, 15, 2, 1, 30);
		$this->Arrow(143, 20, 145.5, 20, 2, 1, 30);
		$this->Arrow(181.5, 20, 179, 20, 2, 1, 30);
	}

	public function Footer() {
		$this->SetY(-18.4);
		$this->SetFont('freeserif', '', 7);
		$this->Cell(0, 10, 'S-13-P   7/98', 0, false, 'L', 0, '', 0, false, 'T', 'M');
		//$this->SetY(-13);
		//$this->Cell(0, 10, 'Printed in Germany', 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Dawid Sawczuk');
$pdf->SetTitle('Lista terenów cząstkowych zboru Bytom-Szombierki.');
$pdf->SetSubject('Lista wszystkich terenów cząstkowych zboru Bytom-Szombierki wraz z informacjami na temat opracowań.');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//$pdf->SetMargins(20, 29, 10);
$pdf->SetHeaderMargin(9);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, 9);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);

/*
/ create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// add a page
$resolution= array(100, 100);
$pdf->AddPage('P', $resolution);
*/

// --PRZYGOTOWANIE TRESCI-----------------------------------
// ---------------------------------------------------------

	include ("main_cfg.php");
	$db = db_lacz(); //main_cfg
	$db['link']->select_db($dbZbTeren."11163");
	//echo "->".$_SESSION['zbornr']."<-"; nie ma sesji w tym pdf
	if (isset($_GET['cz'])){
		switch ($_GET['cz']){
			case 1: $pytanie = "SELECT * FROM $tbTereny WHERE aktv=1 AND rdz IN (0,1) AND dziel IN(0,1,4) ORDER BY trnaz"; break; //wszystkie
			case 2: $pytanie = "SELECT * FROM $tbTereny WHERE aktv=1 AND rdz IN (0,1) AND dziel IN(0,1) ORDER BY trnaz"; break; //Szombierki
			case 3: $pytanie = "SELECT * FROM $tbTereny WHERE aktv=1 AND dziel = 4 AND grp<>0 ORDER BY trnaz"; break; //Łagiewniki
			case 4: $pytanie = "SELECT * FROM $tbTereny WHERE aktv=1 AND dziel = 3 ORDER BY trnaz"; break; //Godula
			case 5: $pytanie = "SELECT * FROM $tbTereny WHERE aktv=1 AND rdz = 9 ORDER BY trnaz LIMIT 100"; break; //Telefoniczne
			case 6: $pytanie = "SELECT * FROM $tbTereny WHERE aktv=1 AND rdz=1 ORDER BY trnaz"; break; //Handlowe
			default: $pytanie = "SELECT * FROM $tbTereny WHERE aktv=1 AND rdz IN (0,1) AND dziel IN(0,1,4) ORDER BY trnaz"; break;
		}
	}else{
		$pytanie = "SELECT * FROM $tbTereny WHERE aktv=1 AND rdz IN (0,1) AND dziel IN(0,1,4) ORDER BY trnaz";
	}
	//echo $pytanie;
	$zapytanie = @$db['link'] -> query($pytanie);
	
	if(date('m')>9){					
		$prs = date('Y')."-04-16"; //od kwietnia, bo jak od sierpnia (-08-16) to była pusta tabelka i nie wiadomo co ostatnio było podawane
		$krs = date('Y')+1 ."-08-31";
	}else{
		$prs = date('Y')-2 ."-08-16"; // UWAGA <- ABY WYŚWIETLAL NIE TYLKO OSTATNI ROK SLUZBOWY DALEM MINUS 2 LATA !!!!
		$krs = date('Y')."-08-31";
	}
	
	while($dane = $zapytanie->fetch_assoc()){
		
		set_time_limit(0);
		$idter = $dane['idter'];
		
		$tereny[$idter] = array ('numer' => $idter, 'opis' => $dane['trnaz'], 'mieszkan' => $dane['ilmsz'], 'dzielnica' => $dane['dziel'], 'rodzaj'=> $dane['rdz']); //iconv ("utf-8","windows-1250", 
//		$tereny[] - numer, opis, mieszkan, dzielnica, rodzaj
				
		$czyOpracowania = @$db['link'] -> query("SELECT count(*) as lbOpracowan FROM $tbS13 WHERE idter = '$idter'");
		if($czyOpracowania){
			$wystepuje = $czyOpracowania->fetch_assoc();
			if($wystepuje['lbOpracowan']>1){
				
				$przydzialy = @$db['link'] -> query("SELECT $tbS13.dod, $tbS13.ddo, $tbGlos.imie, $tbGlos.nazw FROM $tbS13, $tbGlos WHERE $tbGlos.idglo=$tbS13.idglo and $tbS13.dod > DATE_ADD(NOW(), INTERVAL -2 year) and $tbS13.idter=$idter order by $tbS13.dod");
				if($przydzialy){
					
					while ($daneOpracowania = $przydzialy->fetch_assoc()){
						
						$dataod = $daneOpracowania['dod'];
						if($daneOpracowania['ddo'] =='0000-00-00'){
							$datado ='';
						}else{
							$datado = $daneOpracowania['ddo'];
						}			

						if((strlen($daneOpracowania['imie']) + strlen($daneOpracowania['nazw'])) > 21 ){
							$ile = 20 - strlen($daneOpracowania['nazw']); //-1 na spacje między imieniem nazwiskiem
							$kto = mb_substr($daneOpracowania['imie'],0,$ile) . '. ' . $daneOpracowania['nazw'];
						}else{
							$kto = $daneOpracowania['imie']. ' ' . $daneOpracowania['nazw'];
						}

						$terenyOpracowania[$idter][] = array ('numerter' => $idter, 'kto' => $kto, 'dataod' => $dataod, 'datado' => $datado);
					}
				}
			}else{				
				$terenyOpracowania[$idter][] = array ('numerter' => $idter, 'kto' => ' ', 'dataod' => ' ', 'datado' => ' ');
			}
			//unset($idter);
		
		} //czy sa opracowania
	}
	
	/*
	echo "<pre>";
	print_r($terenyOpracowania);
	echo "</pre><br /><br /><br />";
	
	$klucze = array_keys($terenyOpracowania);
	echo "<pre>";
	print_r($klucze);
	echo "</pre>";
		

	echo "<pre>";
	print_r($terenyOpracowania);
	echo "</pre>";

	
	for ($strona=0;$strona<=20;$strona++){
		$klucze = array_keys($terenyOpracowania[$strona]);
		echo "<pre>";
		echo $strona." ::> ";
		print_r($klucze);
		echo "</pre>";
	}
	*/
	
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
	//------ KONIEC SEKCJI PRZYGOTOWANIA DANYCH-----------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------------------------------------------------------------------------------------------------------------------
			
			//$pdf->AddPage();
			$pdf->SetFont('times', 'BI', 8);
			$pdf->setCellPaddings(0, 0, 0, 0);
			$pdf->setCellMargins(0, 0, 0, 0);
			
			$numeryTerenow = array_keys($tereny);
			$ileter = count ($numeryTerenow);
			$ilestron = ceil ($ileter / 5) - 1;
			
			for ($strona=0;$strona<=$ilestron;$strona++){
				
				if(($strona%2)!=0){
					$pdf->SetMargins(10, 29, 20);
				}else{
					$pdf->SetMargins(20, 29, 10);
				}
				$pdf->AddPage();
				
				for($wrsz=0;$wrsz<=25;$wrsz++){
					
					//---------- WIERSZ 0 - NAZWA TERENU ------------------------------------------------------------------------------------------------------------------------------
					if ($wrsz==0){
						for($i=0;$i<=4;$i++){
							if(array_key_exists($strona * 5+$i,$numeryTerenow)){
								$numer = $numeryTerenow[$strona * 5+$i];
								
								$pdf->SetFont('times', '', 9);									
								$pdf->MultiCell(12, 8, 'Teren Nr: '.$tereny[$numer]['numer'], 0, 'L', 0, 0, '', '', true,0,false,0,8,'T'); //8
									
								switch($tereny[$numer]['dzielnica']){ 
									case 3: $pdf->SetTextColor(0,128,0); break;
									case 4: $pdf->SetTextColor(23,125,54); break;
									default: $pdf->SetTextColor(0,0,255); break;
								}							
									
								switch($tereny[$numer]['rodzaj']){
									case '1' : $pdf->SetFont('freeserifi', '', 6);  break; // + kolor $pdf->SetTextColor(0,128,0);
									case '0' : $pdf->SetFont('freeserifi', '', 8); break;
									default: $pdf->SetFont('freeserifi', '', 8); break;
								}						
										
								$pdf->MultiCell(22, 12, $tereny[$numer]['opis'], 0, 'L', 0, 0, '', '', true,0,false,0,8,'M');
								$pdf->SetTextColor(0,0,0);
								$pdf->MultiCell(2, 8, ' ', 0, 'L', 0, 0, '', '', true,0,false,0,8,'T');								
							}					
						}
					$pdf->MultiCell(1, 8, ' ', 0, 'L', 0, 1, '', '', true);
					//----------------------------------------------------------------------------------------------------------------------------------------------------------------			
					}else{					
						for($i=0;$i<=4;$i++){
							$numer = $numeryTerenow[$strona * 5+$i];
							if((isset($terenyOpracowania[$tereny[$numer]['numer']][$wrsz]))&& ($terenyOpracowania[$tereny[$numer]['numer']][$wrsz]['kto'] != ' ')){ //&& ($terenyOpracowania[$tereny[$numer]['numer']][$wrsz]['kto'] != ' ')
								$dataPoczRokuSluzb = date("Y", strtotime($terenyOpracowania[$numer][$wrsz]['dataod'])) ."-09-01";								
								$terNr = $tereny[$numer]['numer'];						
								$pytPoczRokuSluzb = @$db['link'] -> query("SELECT dod FROM $tbS13 WHERE idter = $terNr AND dod >= '$dataPoczRokuSluzb' ORDER BY dod ASC LIMIT 1"); //ORDER BY DATA WYGASNIECIA DESC i LIMIT 1
								
								if($pytPoczRokuSluzb){
									$poczRokuSluzb = $pytPoczRokuSluzb->fetch_assoc();
									if($terenyOpracowania[$numer][$wrsz]['dataod']==$poczRokuSluzb['dod']){								
										$ramka = array('L' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
														'T' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(255, 0, 0)),
														'R' => array('width' => 0.4, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
														'B' => array('width' => 0.1, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
									}else{								
										$ramka = array('L' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
														'T' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
														'R' => array('width' => 0.4, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
														'B' => array('width' => 0.1, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));								
									}
									
								}
								
								$pdf->setCellMargins(0, 0, 0, 0);
									$pdf->SetFont('times', '', 7);
									//$pdf->MultiCell(6, 5, '-', $ramka, 'C', 0, 0, '', '', true,0,false,0,5,'T');
									$pdf->SetFont('freeserifi', '', 8);
									$pdf->MultiCell(36, 4.9, ' '.$terenyOpracowania[$numer][$wrsz]['kto'], $ramka, 'L', 0, 0, '', '', true,0,false,0,5,'M'); //." ". $wrsz - przy imieniu widzisz numer wiersza
							
							}else{
								$pdf->setCellMargins(0, 0, 0, 0);
								$ramka = array('L' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'T' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'R' => array('width' => 0.4, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
											   'B' => array('width' => 0.1, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
								$pdf->MultiCell(36, 4.9, ' ', $ramka, 'L', 0, 0, '', '', true,0,false,0,5,'M');
							}
						}
						
						$pdf->MultiCell(1, 4.9, ' ', 0, 'L', 0, 1, '', '', true); //enter na końcu wiersza
						
						for($i=0;$i<=4;$i++){
							$numer = $numeryTerenow[$strona * 5+$i];
							if((isset($terenyOpracowania[$tereny[$numer]['numer']][$wrsz]))&& ($terenyOpracowania[$tereny[$numer]['numer']][$wrsz]['dataod'] != ' ')){
								$pdf->SetDrawColor(0, 0, 0);
								$pdf->setCellMargins(0, 0, 0, 0);
								$pdf->SetFont('times', '', 8);

								$ramka = array('L' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'T' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'R' => array('width' => 0.1, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
											   'B' => array('width' => 0.4, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
								$pdf->MultiCell(18, 4.9, $terenyOpracowania[$numer][$wrsz]['dataod'], $ramka, 'C', 0, 0, '', '', true,0,false,0,5,'M');
								$ramka = array('L' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'T' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'R' => array('width' => 0.4, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
											   'B' => array('width' => 0.4, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
								$pdf->MultiCell(18, 4.9, $terenyOpracowania[$numer][$wrsz]['datado'], $ramka, 'C', 0, 0, '', '', true,0,false,0,5,'M');
							}else{
								$ramka = array('L' => array('width' => 0.4, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'T' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'R' => array('width' => 0.1, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
											   'B' => array('width' => 0.4, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
								$pdf->MultiCell(18, 4.9, ' ', $ramka, 'L', 0, 0 , '', '', true);
								$ramka = array('L' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'T' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => array(0, 0, 0)),
											   'R' => array('width' => 0.4, 'cap' => 'round', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
											   'B' => array('width' => 0.4, 'cap' => 'square', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
								$pdf->MultiCell(18, 4.9, ' ', $ramka, 'L', 0, 0, '', '', true);
							}
						}
						
						$pdf->MultiCell(1, 4.9, ' ', 0, 'L', 0, 1, '', '', true); //enter na końcu wiersza						
						
					}
				}
			}
		
	$zapytanie->free();	
	unset($dane);

//Close and output PDF document
if (isset($_GET['id'])){
	$plik = 'S-13-P - '.date("Y-m-d").' - grupa '.$_GET['id'].'.pdf';
}else{
	$plik = 'S-13-P - '.date("Y-m-d").'.pdf';
}
$pdf->Output($plik, 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
