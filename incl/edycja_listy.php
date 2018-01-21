<?php
	
if((!isset($_POST['zapisz'])) and (!isset($_POST['przydziel']))){
	
	if($_SESSION['prawa'] > 1){		

		echo '<h2>';
			echo 'edycja listy terenów';			
		echo '</h2>';
		
		// zrób gdzie jesteś
	
			switch ($_SESSION['prawa']){
				case 4: //admin - sł. ter, n.służby, koordynator
					
					$db = db_lacz(); //main_cfg
					$db['link']->select_db($dbZbTeren. $_SESSION['zbornr']);
					$pytanko = $db['link']->query("SELECT MAX(gr) as lbGrup FROM $tbGlos");			
					$grlb = $pytanko->fetch_assoc(); //liczba grup
					$pytanko->free();
					
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
					}			
				break;
				case 2: //n.grupy, zast, n. grupy	
					$db = db_lacz(); //main_cfg
					$db['link']->select_db($dbZbTeren. $_SESSION['zbornr']);
					$pytanko = @$db['link'] -> query("SELECT gr FROM $tbGlos WHERE idglo = $_SESSION[oper]");
					$dane = $pytanko->fetch_assoc();
					$grnr = $dane['gr']; //numer grupy nadzorcy lub zastępcy
					$pytanko->free();
								
				break;
			}
	
				echo '<div ID="sterBox">';
					echo '<div class="sterBoxLeft">';
				
						echo '<h3>grupa';
							if ($_SESSION['prawa']==4){ //admin - obwodowy, sługa terenów, nadzorca służby, koordynator
								for($gr=0;$gr<=$grlb['lbGrup'];$gr++){
									if($gr==0){
										echo '<button class="grnr" onClick="window.location.href = \'index.php?g=edt_lst\'" />Z</button>';										
									}else{
										if($gr==$grnr){											
											echo '<button class="grnrch" />'. $gr .'</button>';	
										}else{
											echo '<button class="grnr" onClick="window.location.href = \'index.php?g=edt_lst&grupa='. $gr .'\'" />'. $gr .'</button>';
										}
									}
								}
							}else{ //nadzorca grupy, zastępca
								echo '<button class="grnrch" />'. $grnr .'</button>';
							}									
						echo '</h3>';
					echo '</div>';	
					unset($gr);
					
					echo '<div class="sterBoxCenter">';
						$pyt = @$db['link'] -> query("SELECT MAX(dwpr) as dwp, MAX(dzdn) as dzd FROM $tbS13 WHERE idter IN (SELECT idter FROM $tbTereny WHERE aktv=1)");
						if($pyt!=false){
							$dane = $pyt->fetch_assoc();
							$pyt->free();
							//echo $dane['dwp'].'+'.$dane['dzd'].'<br/>';
							if($dane['dwp']<$dane['dzd']) echo 'ostatnia modyfikacja kartoteki dnia: '. date('d',strtotime($dane['dzd'])) .'.'. date('m',strtotime($dane['dzd'])) .'.'. date('Y',strtotime($dane['dzd'])) .'<br />';		
							else echo 'ostatnia modyfikacja kartoteki dnia: '. date('d',strtotime($dane['dwp'])) .'.'. date('m',strtotime($dane['dwp'])) .'.'. date('Y',strtotime($dane['dwp'])) .'<br />';		
							unset($dane);
						}else{
							echo 'ostatnia modyfikacja kartoteki dnia:<br/>brak danych.';
						}
					echo '</div>';
					echo "\n<form action=\"" . $_SERVER['SCRIPT_NAME'] . '?'. $_SERVER['QUERY_STRING'] . "\" method=\"POST\">\n";
					echo '<div class="sterBoxRight">';
						echo '<input class="knefel" type="submit" value="zapisz" name="zapisz" />';
						echo "<input class=\"knefel\" type=\"button\" onClick=\"window.location.href = 'index.php?g=edt_lst_pdf'\" value=\"pdf\" /><br />"; 
						//echo '<input type="text" class="szTeren" /><input type="reset" value="szukaj" class="szGuzik" />';
					echo '</div>';
					
					echo '<div class="br"></div>';
				echo '</div><br />'; //sterBox
					
					
				//----------------------------------------------------------------------------------------------------------------------------
			
				echo '<div id="terList">';
				
					//$pyt = @$db['link'] -> query("SELECT idter, idglo, MAX(dod) as tStart FROM $tbS13 WHERE idter IN (SELECT idter FROM $tbTereny WHERE aktv=1) AND ddo = '0000-00-00'");
					if (!isset($_GET['grupa'])){ 
						$pyt = @$db['link'] -> query("SELECT $tbS13.idter, $tbS13.idglo, $tbS13.dod, $tbTereny.trnaz, $tbGlos.imie, $tbGlos.nazw FROM $tbS13 INNER JOIN $tbGlos ON $tbS13.idglo = $tbGlos.idglo INNER JOIN $tbTereny ON $tbS13.idter = $tbTereny.idter WHERE $tbS13.ddo = '0000-00-00' AND $tbTereny.rdz<>9  GROUP BY $tbS13.idter ORDER BY $tbS13.dod, $tbS13.idter ");
					}else{
						$pytanko = @$db['link'] -> query("SELECT idglo FROM $tbGlos WHERE nazw = 'grupa' AND gr = $grnr");
						$dane = $pytanko->fetch_assoc();
						$grp = $dane['idglo']; //numer grupy nadzorcy lub zastępca
						$pytanko->free();						
						$pyt = @$db['link'] -> query("SELECT $tbS13.idter, $tbS13.idglo, $tbS13.dod, $tbTereny.trnaz FROM $tbS13 INNER JOIN $tbTereny ON $tbS13.idter = $tbTereny.idter WHERE $tbS13.ddo = '0000-00-00' AND $tbTereny.rdz<>9 AND $tbS13.idglo=$dane[idglo]  GROUP BY $tbS13.idter ORDER BY $tbS13.dod, $tbS13.idter ");
					}
				
					//SELECT pracownik.id_pracownika, imie, nazwisko, adres_email FROM pracownik INNER JOIN adresy ON pracownik.id_pracownika = adresy.id_pracownika
					if($pyt!=false){
						
						echo"<div class=\"l_nag\">"; //id=\"w_".$dane['idter']."\">";
							echo"<div class=\"l_kol_lp1\">LP</div>";
							echo"<div class=\"l_kol_ter1\">ID</div>";
							echo"<div class=\"l_kol_naz1\">NAZWA <input name=\"szTer\" class=\"l_szTerenu\" value=\"\" /></div>";
							echo"<div class=\"l_kol_glo1\">KTO <input name=\"szGlo\" class=\"l_szKto\" value=\"\" /></div>";
							echo"<div class=\"l_kol_dat1\">DATA</div>";
							echo"<div class=\"l_kol_dni1\">DNI</div>";
							echo"<div class=\"l_kol_raz1\">RAZY</div>";
							echo"<div class=\"l_kol_stp1\">OPRAC.</div>";
							echo"<div class=\"l_kol_pub1\">PUBL.</div>";
							echo"<div class=\"l_kol_krt1\">KRT</div>";
							echo"<div class=\"tbr\"></div>";
						echo"</div>";
						echo"<div class=\"l_wiersze\">";
						
						$lp = 1;
						while($dane=$pyt->fetch_assoc()){
							//$dane = $pyt->fetch_assoc();
							//$pyt->free();
							
							echo"<div class=\"l_wiersz\" id=\"w_".$dane['idter']."\">";
							
							//--== LICZBA porządkowa ==--
							echo"<div class=\"l_kol_lp\">".$lp."</div>";
							
							//--== ID terenu ==--
							echo"<div class=\"l_kol_ter\">".$dane['idter']."</div>";
							
							//--== NAZWA ==--
							if (strlen($dane["trnaz"]) > 34){ $tNazwa = substr($dane["trnaz"], 0, 34) .'...';
							}else{ $tNazwa = $dane["trnaz"]; }
							echo"<div class=\"l_kol_naz\"><label><input type=\"checkbox\" name=\"terOpracowany[]\" value=\"".$dane['idter'] . "\" />&nbsp;".$tNazwa."</label></div>";
							
							//--== KTO opracowuje ==--
							$kto = $dane['imie']." ".$dane['nazw'];
							if(strlen($kto) > 17){ $glo = substr($dane['imie'],0,1).substr($dane['imie'],2,1).substr($dane['imie'],4,1).". ".$dane['nazw'];}
							else{ $glo = $kto; }
							unset($kto);
							echo"<div class=\"l_kol_glo\">".$glo."</div>"; unset($glo);
							
							//--== DATA pobrania ==--
							echo"<div class=\"l_kol_dat\">".$dane['dod']."</div>";
							
							//--== DNI ==--
							if($lbDni > 0 AND $lbDni <= 90){ echo"<div class=\"l_kol_dni\">"; }
							else{
								if($lbDni > 90 AND $lbDni <= 105){ echo"<div class=\"l_kol_dni_o1\">"; }
								else{
									if($lbDni > 105 AND $lbDni < 113){ echo"<div class=\"l_kol_dni_o2\">"; }
									else{
										if($lbDni >= 113 AND $lbDni < 120){ echo"<div class=\"l_kol_dni_o3\">"; }
										else{ echo"<div class=\"l_kol_dni_o4\">"; }
									}
								}
							}
							echo $lbDni."</div>";
							
							//--== obliczanie ile RAZY opracowany ==--
							$dStart = explode("-", $dane['dod']);
							$lbDni = round((mktime(0,0,0, date('m'), date('d'), date('Y')) - mktime(0,0,0, $dStart[1], $dStart[2], $dStart[0]))/(60*60*24));
							
							if(date('m')>=9){					
								$prs = date('Y')."-09-01";
								$krs = date('Y')+1 ."-08-31";
							}else{
								$prs = date('Y')-1 ."-09-01";
								$krs = date('Y')."-08-31";
							}
							
							$pytRazy = @$db['link'] -> query("SELECT count(*) as ileRazy FROM $tbS13 WHERE idter = $dane[idter] AND dod >= '$prs' AND ddo <= '$krs'");				
							if($pyt_razy){
								$odpRazy = $pytRazy->fetch_assoc();
								$lbRazy = $odpRazy['ileRazy'];
							}else{
								$lbRazy = 0;
							}
							//--== RAZY ==--
							echo"<div class=\"l_kol_raz\">".$lbRazy."</div>"; 
							
							//--== DATA zdania ==--
							echo"<div class=\"l_kol_stp\"><input name=\"data_".$dane["idter"]."_do\" class=\"l_datao\" value=\"".date('Y-m-d')."\" /></div>";
							
							//--== PUBLIKACJA ==--
							echo"<div class=\"l_kol_pub\">";								
								$czym = array(1=>'cz',2=>'br',3=>'ks',4=>'tr',5=>'zp',6=>'zk',7=>'wk',8=>'bi',9=>'li');
								echo "<select name=\"k_".$dane["idter"]."_czym[$wpis]\" class=\"l_czym\">";
									echo"<option></option>";										
									for($x=1;$x<=9;$x++){
										echo"<option";
										//if($teren_wiersz['zczym']==$x){echo" selected";}
										echo">".$czym[$x]."</option>";
									}						
								echo "</select>";	
							echo"</div>";
							
							//--== KARTA terenu S12 ==--
							echo"<div class=\"l_kol_krt\"><input type=\"checkbox\" name=\"ter_s12[]\" value=\"".$dane['idter'] . "\" /></div>";
							
							//--== koniec wiersza ==--
							echo"<div class=\"tbr\"></div>";
							echo"</div>"; //div wiersza
							
							$lp++;
						}
						echo"</div>";//div wszystkch wierszy
					}else{
						echo $_GET['grupa']. " - " .$grnr ."|";
					}				
				
				echo '</div><br />';
				
			echo "\n</form>";
		
		
		/*
			$grupa = mysql_query("SELECT * FROM $db_glos WHERE nazw = 'grupa' AND imie = '$grnr'");
			
			//$grupa = mysql_query("SELECT * FROM $db_glos WHERE idglo = '$_GET[id]'");

			if ($grupa){
				$dane = mysql_fetch_assoc($grupa);
				
				echo "\n<form action=\"" . $_SERVER['SCRIPT_NAME'] . '?'. $_SERVER['QUERY_STRING'] . "\" method=\"POST\" id=\"f_lista\">\n";

				echo "<div class=\"nag\">";
				/*
				echo "<div class=\"nazwa\">" . $dane['nazw'] . " " . $dane['imie'] . "</div>";
				echo "<div class=\"submit\">";								
				if($_SESSION['prawa']==4){
					$maxgrp = mysql_fetch_assoc(mysql_query("SELECT MAX(gr) as nr FROM $db_glos"));
					for($gr=1;$gr<=$maxgrp['nr'];$gr++){
						echo"<button type=\"button\" class=\"lista_grp\" value=\"".$gr."\">".$gr."</button>";
					}
					echo"<input class=\"lista_innagr\" type=\"button\" value=\"inna grupa\" />";
				}
				*/	
		/*		echo "<div class=\"submit\">";			
				echo"<input class=\"lista_pdf\" type=\"button\" onClick=\"window.location.href = 'index.php?".$strona_glowna."=edit&".$strona_informacji."=lst_pdf&gr=".$dane['imie']."'\" value=\"pdf\" />";
				echo"<input class=\"lista_zapis\" type=\"submit\" value=\"zapisz\" name=\"zapisz\" />";
				echo"</div>";			
				echo "<div class=\"klir\"></div>";
				echo "</div>";
			}
			
			echo "<br/><br/><br/><br/><br/><br/>";


			//$sql = mysql_query("SELECT idter, trnaz, ilmsz FROM $db_trny WHERE aktv = 1 AND stus = 0 AND grp = $grupa"); //pobrane tereny w danej grupie		
			
			$czysa = mysql_query("SELECT count(*) as sa FROM $db_trny WHERE aktv = 1 AND stus = 0 AND grp = $dane[imie]");
			$tersa = mysql_fetch_array($czysa);
			if($tersa['sa'] > 0){
				
			
			$tereny = mysql_query("SELECT idter, trnaz, ilmsz FROM $db_trny WHERE aktv = 1 AND stus = 0 AND grp = $dane[imie]");
			
				if($tereny){
					$nr=0;
					while($teren = mysql_fetch_assoc($tereny)){	
						$nr++;
						$datadzis = date("Y-m-d");					
						
						$tereny_kart = mysql_query("SELECT idwp, idglo, dod FROM $db_kart WHERE idter = $teren[idter] AND ddo = 0");
						
						if ($tereny_kart){
							$teren_kart = mysql_fetch_assoc($tereny_kart);
							$glosiciel = mysql_fetch_assoc(mysql_query("SELECT imie, nazw FROM $db_glos WHERE idglo = $teren_kart[idglo]"));
						}					
						if (strlen($teren['trnaz']) > 43){$nazwa = substr($teren['trnaz'], 0, 43). "...";}
						else{$nazwa = $teren['trnaz'];}

						$teren_w_gr[] = array('idter' => $teren['idter'], 
														 'nazwa' => $teren['trnaz'], 
														 'wpis' => $teren_kart['idwp'], 
														 'idglo' => $teren_kart['idglo'], 
														 'imie' => $glosiciel['imie'], 
														 'nazw' => $glosiciel['nazw'], 
														 'dod' => $teren_kart['dod']);
					}
					
					//--sortowanie			
					$tereny_w_gr_tmp = array();							
					foreach ($teren_w_gr as $klucz => $wiersz) {
						$tereny_w_gr_tmp[1][$klucz] = $wiersz['idter'];
						$tereny_w_gr_tmp[2][$klucz] = $wiersz['nazwa'];
						$tereny_w_gr_tmp[3][$klucz] = $wiersz['wpis'];
						$tereny_w_gr_tmp[4][$klucz] = $wiersz['idglo'];
						$tereny_w_gr_tmp[5][$klucz] = $wiersz['imie'];
						$tereny_w_gr_tmp[6][$klucz] = $wiersz['nazw'];					
						$tereny_w_gr_tmp[7][$klucz] = $wiersz['dod'];
					}			
					array_multisort($tereny_w_gr_tmp[6], SORT_ASC, $tereny_w_gr_tmp[5], SORT_ASC, $tereny_w_gr_tmp[7], SORT_ASC, $teren_w_gr);
					
					echo "<div class=\"teren_nag\">";
					echo "<div class=\"ter_nag\">TERENY PRZYDZIELONE</div>";
					echo "<div class=\"ter_opr_lp2\">lp</div>";
					echo "<div class=\"ter_opr_naz\">nazwa terenu</div>";
					echo "<div class=\"ter_opr_glo\">glosiciel</div>";
					echo "<div class=\"ter_opr_dpo\">data pobrania</div>";
					echo "<div class=\"ter_opr_pr0\">dni temu</div>";
					echo "<div class=\"ter_opr_dzd\">data zdania</div>";
					echo "<div class=\"ter_opr_zczal\">opracowane z publikacją</div>";
					echo "<div class=\"ter_opr_pon\">ponownie</div>";
					echo "<div class=\"teren_br3\"></div>";
					echo "</div>";
					
					$nr=0;
					foreach($teren_w_gr as $wierszyk){
						
						$nr++;
						$datadzis = date("Y-m-d");
						
						if (strlen($wierszyk['nazwa']) > 35){$nazwa = substr($wierszyk['nazwa'], 0, 35). "...";}
						else{$nazwa = $wierszyk['nazwa'];}
						
						echo "\n\t\t<div class=\"teren\" id=\"".$wierszyk['idter']."\">\n";
							
							//-------------------
							echo "\t\t\t<div class=\"ter_opr_lp\">$nr</div>";
							echo "\t\t\t<div class=\"ter_opr_naz\"><label><input type=\"checkbox\" name=\"ter_pobr[]\" value=\"".$wierszyk['idter'] . "\" />&nbsp;" . $nazwa . "</label><input type=\"hidden\" name=\"w" . $wierszyk['idter'] . "\" value=\"". $wierszyk['wpis'] ."\" /><input type=\"hidden\" name=\"g" . $wierszyk['idter'] . "\" value=\"". $wierszyk['idglo'] ."\" /><input type=\"hidden\" name=\"t" . $wierszyk['idter'] . "_od\" value=\"". $wierszyk['dod']."\" /></div>";
							$wierszyk_imie = substr($wierszyk['imie'], 0, 1). ".";
							echo '<div class="ter_opr_glo"  title="'.$wierszyk['imie'].' '.$wierszyk['nazw'].'">'.$wierszyk_imie.' '.$wierszyk['nazw'].'</div>';
							echo "<div class=\"ter_opr_dpo\">".$wierszyk['dod']."</div> ";
								
							if ((round(strtotime($datadzis) - strtotime($wierszyk['dod'])) / 86400) > 120){
								echo "<div class=\"ter_opr_pr4\">".round((strtotime($datadzis) - strtotime($wierszyk['dod'])) / 86400)."</div>";
							}else{
								if ((round(strtotime($datadzis) - strtotime($wierszyk['dod'])) / 86400) > 90){
									echo "<div class=\"ter_opr_pr3\">".round((strtotime($datadzis) - strtotime($wierszyk['dod'])) / 86400)."</div>";
								}else{
									echo "<div class=\"ter_opr_pr0\">".round((strtotime($datadzis) - strtotime($wierszyk['dod'])) / 86400) ."</div>";
								}
							}					
							//echo"</div>";
							echo "\t\t\t<div class=\"ter_opr_dzd\"><img src=\"img\datownik.jpg\" width=\"17\" height=\"17\" border=\"0\" onClick=\"displayDatePicker('t".$wierszyk['idter']."_do',this);\" /> <input id=\"terdzd\" class=\"ter_dat\" size=\"10\" maxlength=\"10\" value=\"" . $datadzis . "\" name=\"t" . $wierszyk['idter'] . "_do\"/></div>";
							//echo "<div class=\"ter_opr_br\"></div>";
													
							echo   "<div class=\"ter_opr_zcz\" title=\"czasopisma\"><label><input type=\"radio\" name=\"t".$wierszyk['idter']."_z\" value=\"Czasopisma\" />cz</label></div>";
							echo   "<div class=\"ter_opr_zcz\"><label><input type=\"radio\" name=\"t".$wierszyk['idter']."_z\" value=\"Broszury\" />br</label></div>";
							echo   "<div class=\"ter_opr_zcz\"><label><input type=\"radio\" name=\"t".$wierszyk['idter']."_z\" value=\"Książki\" />ks</label></div>";
							echo   "<div class=\"ter_opr_zcz\"><label><input type=\"radio\" name=\"t".$wierszyk['idter']."_z\" value=\"Traktaty\" />tr</label></div>";
							echo   "<div class=\"ter_opr_zcz\"><label><input type=\"radio\" name=\"t".$wierszyk['idter']."_z\" value=\"Biblia\" />bi</label></div>";
							echo   "<div class=\"ter_opr_zcz\"><label><input type=\"radio\" name=\"t".$wierszyk['idter']."_z\" value=\"Zaproszenia Pa\" />zp</label></div>"; 
							echo   "<div class=\"ter_opr_zcz\"><label><input type=\"radio\" name=\"t".$wierszyk['idter']."_z\" value=\"Zaproszenia Ko\" />zk</label></div>"; 
							echo   "<div class=\"ter_opr_zcz\"><label><input type=\"radio\" name=\"t".$wierszyk['idter']."_z\" value=\"Wiadomości Kr\" />wk</label></div>"; 
							echo   "<div class=\"ter_opr_zcz\"><label><input type=\"radio\" name=\"t".$wierszyk['idter']."_z\" value=\"Literatura\" />li</label>&nbsp;</div>";
							
							echo "<div class=\"ter_opr_pon\"><label><input type=\"checkbox\" name =\"pon_" . $wierszyk['idter'] . "\" /></label></div>";
							
						echo "</div>";
						echo "<div class=\"teren_br2\"></div>";

					}
				}
				echo"<br /><br />";
				}			
				
				
			
				//===============================================================================
				
				echo "<div class=\"teren_nag\">";
				echo "<div class=\"ter_nag\">TERENY NIEPRZYDZIELONE</div>";
				echo "<div class=\"ter_opr_lp2\">lp</div>";
				echo "<div class=\"ter_dst_naz\">nazwa terenu</div>";
				echo "<div class=\"ter_dst_dzd\">data zdania</div>";
				echo "<div class=\"ter_dst_pr0\">dni temu</div>";
				echo "<div class=\"ter_dst_dpo\">data pobranie</div>";
				echo "<div class=\"ter_dst_glo\">glosiciel</div>";
				echo "<div class=\"teren_br3\"></div>";
				echo "</div>";
					
				$sql = mysql_query("SELECT idter, trnr, trnaz, ilmsz, dziel FROM $db_trny WHERE aktv = 1 AND stus = 1 AND grp = $dane[imie]"); //dostepne tereny w danej grupie
				
				if($sql){				
				
					while ($row = mysql_fetch_assoc($sql)) {
						
						$wynik = mysql_fetch_assoc(mysql_query("SELECT idter, ddo as temu, zczym FROM $db_kart WHERE ddo = (SELECT MAX(ddo) FROM $db_kart WHERE idter = $row[idter])"));
						
						if (strtotime($wynik["temu"]) > 0){
							$temu = $wynik["temu"];
							//$temu = round((strtotime(date("Y-m-d")) - strtotime($wynik["temu"])) / 86400);
						}else{
							$temu = "nie opr.";
						}
						
						switch($wynik['zczym']){ 
						case 1: $zczym = 'CZ'; break;
						case 2:	$zczym = 'BR'; break;
						case 3:	$zczym = 'KS'; break;
						case 4:	$zczym = 'TR'; break;
						case 5:	$zczym = 'ZP'; break;
						case 6:	$zczym = 'ZK'; break;
						case 7:	$zczym = 'WK'; break;
						case 8:	$zczym = 'BI'; break;
						case 9:	$zczym = 'LI'; break;
						default: $zczym = "."; break;
						}
						
						if(date('m')>=9){					
							$prs = date('Y')."-09-01";
							$krs = date('Y')+1 ."-08-31";
						}else{
							$prs = date('Y')-1 ."-09-01";
							$krs = date('Y')."-08-31";
						}
						$pyt_razy = mysql_query("SELECT count(*) as ile_razy FROM $db_kart WHERE idter = $row[idter] AND dod >= '$prs' AND ddo <= '$krs'");				
						
						if($pyt_razy){
							$odp_razy = mysql_fetch_assoc($pyt_razy);
							$ile_razy = $odp_razy['ile_razy'];
						}else{
							$ile_razy = 0;
						}
						
						$dostepne[] = array('idter' => $row["idter"], 'trnr' => $row["trnr"], 'dzielnica' => $row["dziel"], 'trnaz' => $row['trnaz'], 'ilmsz' => $row['ilmsz'], 'temu' => $temu, 'zczym' => $zczym, 'razy' => $ile_razy);
						
					}	
					
					$dostepne_tmp = array();
					
					//--sortowanie
					
					foreach ($dostepne as $klucz => $wiersz) {
						$dostepne_tmp[1][$klucz] = $wiersz['idter'];
						$dostepne_tmp[2][$klucz] = $wiersz['trnr'];
						$dostepne_tmp[3][$klucz] = $wiersz['trnaz'];
						$dostepne_tmp[4][$klucz] = $wiersz['ilmsz'];
						$dostepne_tmp[5][$klucz] = $wiersz['temu'];
						$dostepne_tmp[6][$klucz] = $wiersz['zczym'];
						$dostepne_tmp[7][$klucz] = $wiersz['razy'];				
						$dostepne_tmp[8][$klucz] = $wiersz['dzielnica'];
					}
					
					array_multisort($dostepne_tmp[7], SORT_ASC, $dostepne_tmp[5], SORT_DESC, $dostepne_tmp[1], SORT_ASC, $dostepne);
				
					$nr=0;
					foreach($dostepne as $wierszyk){
						
						$nr++;
						
						//---NAZWA------------------------------------------------------
						if (strlen($wierszyk["trnaz"]) > 30){
							$nazwa_terenu = substr($wierszyk["trnaz"], 0, 30) . ' ...';
						}else{
							$nazwa_terenu = $wierszyk["trnaz"];
						}

						echo "\n\t<div class=\"teren\" id=\"".$wierszyk["idter"]."\">\n";
							
							echo "\t\t\t<div class=\"ter_dst_lp\">$nr</div>";
							echo "\t\t\t<div class=\"ter_dst_naz\"><label><input type=\"checkbox\" id=\"idter\"  name=\"ter_dost[]\" value=\"".$wierszyk["idter"]."\" /> &nbsp;" . $nazwa_terenu ."</label></div>";
							echo "<div class=\"ter_dst_dzd\">".$wierszyk["temu"]."</div>";
							echo "<div class=\"ter_dst_pr0\">".$wierszyk["razy"]."</div>";						
							echo "\t\t\t<div class=\"ter_dst_dpo\"><img src=\"img\datownik.jpg\" width=\"17\" height=\"17\" border=\"0\" onClick=\"displayDatePicker('t".$wierszyk['idter']."_od',this);\" /><input id =\"terdzd\" class=\"ter_dat\" size=\"10\" maxlength=\"10\" value=\"" . date("Y-m-d") . "\" name=\"t" . $wierszyk['idter'] . "_od\" /></div>";
							
							
							echo "\t<div class=\"ter_dst_glo\"><select name=\"ter_dost_glos_".$wierszyk["idter"]."\" id=\"ter_dost_glos_".$wierszyk["idter"]."\" class=\"ter_glos\"><option></option>";
								//echo "\n\t\t\t<option>wybierz</option>";
								$glosiciele = mysql_query("SELECT imie, nazw FROM $db_glos WHERE (stat = 1 AND gr = $dane[imie]) OR (gr = 0 AND stat = 1)  ORDER BY nazw, imie");
								while($glosiciel = mysql_fetch_assoc($glosiciele)){
									echo "\n\t\t\t<option>".$glosiciel['nazw']." ".$glosiciel['imie']."</option>";
								}
								echo "\n\t</select>\n</div>\n";					
							//echo "\t<div class=\"br\">&nbsp;</div>\n";
							
							//echo "<div class=\"ter_dst_glo\">".$wierszyk["trnr"]." - ".$wierszyk["ilmsz"]."mieszkań, opracowany: ".$wierszyk["razy"]."</div>";
							
							
						echo "\t</div>\n";
						echo "\t<div class=\"teren_br3\"></div>";
							
					}
				}
				
				
				

			echo "</form>\n";		
		*/
		
	}//if prawa > 1
	
}else{
	//echo "zapisywanie<br/>";
	
	if (isset($_POST['terOpracowany'])){ //zaznaczony teren pobrany (do zdania)
		//echo "<br/>jest teren do zdania<br/>";
		foreach ($_POST['terOpracowany'] as $idter){
			//echo $idter." -  opracowany dn.: ". $_POST['data_'.$idter.'_do']."<br/>";
			/*
			switch($_POST['t'.$idter.'_z']){ 
				case 'Czasopisma':		$zczym = 1; break;
				case 'Broszury':		$zczym = 2;	break;
				case 'Książki':			$zczym = 3; break;
				case 'Traktaty':		$zczym = 4;	break;
				case 'Zaproszenia Pa':	$zczym = 5; break;
				case 'Zaproszenia Ko':	$zczym = 6;	break;
				case 'Wiadomości Kr':	$zczym = 7;	break;
				case 'Biblia':			$zczym = 8;	break;
				case 'Literatura':		$zczym = 9; break;
			}
				
			$rodzaj = 1;
			$numer = $idter;
			
			// SPRAWDZENIE ILE RAZY OPRACOWANY
			//$pyt_razy = mysql_query("SELECT count(*) as ile_razy FROM $db_kart WHERE idter = $numer AND YEAR(ddo) = YEAR(NOW())");
			if(date('m')>=9){					
				$prs = date('Y')."-08-16";
				$krs = date('Y')+1 ."-08-31";
			}else{
				$prs = date('Y')-1 ."-08-16";
				$krs = date('Y')."-08-31";
			}
			$pyt_razy = mysql_query("SELECT count(*) as ile_razy FROM $db_kart WHERE idter = $row[idter] AND dod>='$prs' and ddo<='$krs'");				
				
			if($pyt_razy){
				$odp_razy = mysql_fetch_assoc($pyt_razy);
				$ile_razy = $odp_razy['ile_razy'];
			}else{
				$ile_razy = 0;
			}
			//--------------------------------------
			
			//setlocale(LC_TIME, "pl_PL");				
			//$dzd = strftime("%Y-%m-%d %H:%M:%S"); /
			$dzd = date("Y-m-d H:i:s");			
			$wpis = $_POST['w'.$idter];
			$glo = $_POST['g'.$idter];
			$do = $_POST['t'.$idter.'_do'];
			$od = $_POST['t'.$idter.'_od'];
			$opr = $_SESSION['oper'];
				
			if(isset($_POST['pon_'.$idter])){
				$zapisz = mysql_query("UPDATE $db_kart SET ddo = '$do', zczym = $zczym , rdz = $rodzaj, kart = 1,  dwpr = $dwp WHERE idwp = $wpis AND idglo = $glo AND idter = $idter AND dod = '$od'");
					
				//jeśli ponownie dodaj nowy do bazy
				$wynik = mysql_fetch_assoc(mysql_query("SELECT count(*) as ile FROM $db_kart"));					
				//ZAKŁADAM, ŻE PONOWNIE TO JUŻ KTOS MA KARTĘ - NAJPELPIEJ WSTAW POPRZEDNI STAN !!!
				$nr_wpisu = mysql_fetch_assoc(mysql_query("SELECT kart FROM $db_kart WHERE idwp = (SELECT idwp FROM $db_kart WHERE idter = $idter AND ddo = (SELECT MAX(ddo) FROM $db_kart WHERE idter = $idter))"));
				$qwydanie = mysql_query("INSERT INTO $db_kart (idwp,idter,idglo,dod,ddo,rdz,zczym,dwpr,wprp,kart) values ($wynik[ile],$idter,$glo[idglo],'$dod','0000-00-00','1','0','$dwp',$opr,$nr_wpisu[kart])");// or die (mysql_error()); ;					
				//$qwydanie = mysql_query("INSERT INTO $db_kart (idwp,idter,idglo,dod,dwpr,wprp,kart) values ($wynik[ile],$idter,$glo,'$do','$dwp',$opr,$nr_wpisu[kart])");
					
			}else{
				//$zapisz = mysql_query("UPDATE $db_kart SET ddo = '$do', zczym = $zczym , rdz = $rodzaj, ilop = $ile_razy, kart = 1,  dwpr = $dwp WHERE idwp = $wpis AND idglo = $glo AND idter = $idter AND dod = '$od'"); // 
				//$zapisz = mysql_query("UPDATE $db_kart SET ddo = '$do', rdz = $rodzaj, zczym = $zczym, kart = 1, ilop = $ile_razy, dwpr = $dwp, wprp = 1 WHERE idwp = $wpis AND idglo = $glo AND idter = $idter AND dod = '$od'"); // 
				  $zapisz = mysql_query("UPDATE $db_kart SET ddo = '$do', rdz = $rodzaj, zczym = $zczym, kart = 1,  dzdn = '$dzd', wpzd = '$opr' WHERE idwp = $wpis AND idglo = $glo AND idter = $idter AND dod = '$od'"); // 
				//														idwp, idter, idglo, dod, ddo, rdz, zczym, kart, ilop,  dwpr, wprp
				$zapisz = mysql_query("UPDATE $db_trny SET stus = 1 WHERE idter = $idter");
				
				//echo  $idter."==".$_POST['w'.$idter]."==".$_POST['g'.$idter]."==".$_POST['t'.$idter.'_do']."==".$_POST['t'.$idter.'_od']."==".$_SESSION['oper'];
			}
			
			*/
		}		
	}

	if (isset($_POST['ter_dost'])){ //zaznaczony teren pobrania (do opracowania)
		echo "<br/>jest karta terenu  dowydania<br/>";
		
		foreach ($_POST['ter_dost'] as $idter){
			$ktory = "ter_dost_glos_".$idter;
			$glos = $_POST[$ktory];
			
				if ($glos){
					$nazw = substr($glos,  0, strpos($glos, ' ')); //substr('abcdef', -1, 1)strpos($mystring, $findme)
					$imie = substr($glos, strpos($glos, ' ')+1, strlen($glos) - strpos($glos, ' ')); //substr('abcdef', -1, 1)strpos($mystring, $findme)
					//echo $idter." ".$glos."||".$imie."||".$nazw."<br/>";
					
					$dod = $_POST['t'.$idter.'_od'];
					$dwp= strftime("%Y-%m-%d %H:%M:%S");
					$opr = $_SESSION['oper'];
					$q_glo = mysql_query("SELECT * FROM $db_glos WHERE imie = '$imie' AND nazw = '$nazw'"); //znajdz glosiciela w bazie wg imienia i nazwiska					
					
					if($q_glo){ $glo = mysql_fetch_assoc($q_glo);}
					
					$wynik = mysql_fetch_assoc(mysql_query("SELECT count(*) as ile FROM $db_kart")); //idter					
					$nr_wpisu = mysql_fetch_assoc(mysql_query("SELECT kart FROM $db_kart WHERE idwp = (SELECT idwp FROM $db_kart WHERE idter = $idter AND ddo = (SELECT MAX(ddo) FROM $db_kart WHERE idter = $idter))")); //stan karty - czy jest czy brak					
					if (!empty($nr_wpisu)){
						$karta = $nr_wpisu[kart];
					}else{
						$karta = 0;
					}		
					//echo "karta: ".$karta;
					$qwydanie = mysql_query("INSERT INTO $db_kart (idwp,idter,idglo,dod,ddo,rdz,zczym,dwpr,wprp,kart, dzdn, wpzd) values ($wynik[ile],$idter,$glo[idglo],'$dod','0000-00-00','1','0','$dwp',$opr,'$karta','0000-00-00 00:00:00','0')") or die (mysql_error());
					$zwydanie = mysql_query("UPDATE $db_trny SET stus = 0 WHERE idter = $idter");
				}
				
		}
	}
	
	if(isset($grnr)){
		$sciezka = 'Location: index.php?'.$_SERVER['SCRIPT_NAME'] . '?'. $_SERVER['QUERY_STRING'];
	}else{
		$sciezka = 'Location: index.php?'.$_SERVER['SCRIPT_NAME'] . '?'. $_SERVER['QUERY_STRING'];
	}
	header($sciezka);
	
}

// http://php.net/manual/pl/function.mysql-real-escape-string.php --> sql injection

?>