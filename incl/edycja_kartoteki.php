<?php

	if($_SESSION['prawa'] > 1){		

		echo '<h2>';
			echo 'edycja kartoteki';			
		echo '</h2>';
		
		if($_SESSION['prawa'] >=4){
			
			if((!isset($_POST['zapisz']))){
			
				$db = db_lacz(); //main_cfg
				$db['link']->select_db($dbZbTeren. $_SESSION['zbornr']);
				
				
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
						case 0: $grnr = 0; break;
						default: echo "błąd"; break;
					}
				}else{
					/*
					$pytanko = @$db['link'] -> query("SELECT gr FROM $tbGlos WHERE idglo = $_SESSION[oper]");
					$dane = $pytanko->fetch_assoc();
					$grnr = $dane['gr'];
					$pytanko->free();
					*/
					$grnr = 0;
				}
				
				$pytanko1 = $db['link']->query("SELECT MAX(gr) as lbGrup FROM $tbGlos");			
				$dane_gr = $pytanko1->fetch_assoc();
				
				if($grnr>0){
					$pytanko2 = @$db['link'] -> query("SELECT COUNT(*) AS lb_terenow FROM $tbTereny WHERE grp = $grnr AND aktv = 1");
					$dane_str = $pytanko2->fetch_assoc();
				}else{
					$pytanko2 = @$db['link'] -> query("SELECT COUNT(*) AS lb_terenow FROM $tbTereny WHERE aktv = 1");
					$dane_str = $pytanko2->fetch_assoc();
				}
				
				$na_stronie = 5;
			
				if (isset($_GET['str'])){
					if(is_numeric($_GET['str'])){
						if($_GET['str']>0){
							$str = $_GET['str'];
						}else{
							$str = 0;
						}
					}else{
						$str = 0;
					}
				}else{
					$str = 0;
				}
				
				if($str<1){$str = 0;}
				if($str>ceil($dane_str['lb_terenow'] / $na_stronie)-1){$str = ceil($dane_str['lb_terenow'] / $na_stronie)-1;}
				
			
				echo '<div ID="sterBox">';
					echo '<div class="sterBoxLeft">';
				
						echo '<h3>grupa';					
						for($gr=0;$gr<=$dane_gr['lbGrup'];$gr++){
							if($gr==$grnr){
								echo '<button class="grnrch" />'. $gr .'</button>';	
							}else{
								echo '<button class="grnr" onClick="window.location.href = \'index.php?g=edt_krt&grupa='. $gr .'&str=1\'" />'. $gr .'</button>';
							}
							
						}			
						
						echo '<br />strona';										
						for($strona=0;$strona<=(ceil($dane_str['lb_terenow'] / $na_stronie)-1);$strona++){
							if($strona==$str){
								echo '<button class="grnrch" />'. $strona .'</button>';
							}else{
								echo '<button class="grnr" onClick="window.location.href = \'index.php?g=edt_krt&grupa='. $grnr .'&str='. $strona .'\'" />'. $strona .'</button>';	
							}
							
						}
						unset($gr);
						unset($dane);					
						unset($strona);
						unset($dane_gr);
						unset($dane_str);					
						$pytanko1->free();	
						$pytanko2->free();
						//$db['link']->close();
						
						echo '</h3>';
					echo '</div>';	
					
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
					echo "\n<form action=\"" . $_SERVER['SCRIPT_NAME'] . '?'. $_SERVER['QUERY_STRING'] . "\" method=\"POST\" ID=\"FsterBox\">\n";
					echo '<div class="sterBoxRight">';
						echo '<input class="knefel" type="submit" value="zapisz" name="zapisz" />';
						echo "<input class=\"knefel\" type=\"button\" value=\"s-13 pdf\" id=\"s13filter\"/><br />"; //onClick=\"window.location.href = 'index.php?g=edt_krt_pdf'\"
						echo '<input type="text" class="szTeren" /><input type="reset" value="szukaj" class="szGuzik" />';
						echo '<div class="filter" style="display: none; visibility: hidden; width: 250px; height: 100px; outline: red 1px solid;">';
						echo '<br />formularz filtra<br /><br />';
						echo "<input class=\"knefel\" type=\"button\" value=\"utwórz PDF s-13\" onClick=\"window.location.href = 'index.php?g=edt_krt_pdf'\"'/><br />";
						echo '</div>';
					echo '</div>';
					
					echo '<div class="br"></div>';
				echo '</div><br />';
				
				//----------------------------------------------------------------------------------------------------------------------------
				
				echo '<div id="terListBox">';
					
					if(date('m')>9){					
						$prs = date('Y')-2 ."-08-16"; //od kwietnia, bo jak od sierpnia (-08-16) to była pusta tabelka i nie wiadomo co ostatnio było podawane
						$krs = date("Y-m-d"); //"date('Y')+1 ."-08-31";
					}else{
						$prs = date('Y')-2 ."-08-16"; // UWAGA <- ABY WYŚWIETLAL NIE TYLKO OSTATNI ROK SLUZBOWY DALEM MINUS 2 LATA !!!!
						$krs = date("Y-m-d"); //date('Y')."-08-31";
					}
					
					$strTa = $str*$na_stronie;
					if($grnr>0){
						$pytanko = $db['link']->query("SELECT $tbGlos.imie, $tbGlos.idglo, $tbS13.ddo, $tbS13.idglo, $tbS13.idter, $tbTereny.idter, $tbTereny.trnaz FROM $tbTereny, $tbS13, $tbGlos WHERE $tbGlos.imie = $grnr AND $tbGlos.idglo = $tbS13.idglo AND $tbS13.idter = $tbTereny.idter ORDER BY $tbTereny.trnaz LIMIT $strTa, $na_stronie");
						//$pytanko = $db['link']->query("SELECT idter, trnaz FROM $tbTereny WHERE grp = $grnr AND aktv = 1 ORDER BY trnaz LIMIT $strTa, $na_stronie");
					}else{
						$pytanko = $db['link']->query("SELECT idter, trnaz FROM $tbTereny WHERE aktv = 1 ORDER BY trnaz LIMIT $strTa, $na_stronie");
					}
					
					//$pytanko = $db['link']->query("SELECT idter, trnaz FROM $tbTereny WHERE grp = $grnr");	
					//$pytanko = $db['link']->query("SELECT idwp, idter, idglo, dod, ddo, zczym FROM $tbS13 WHERE idter = 13");	

													//SELECT $tbBlok.licznik FROM $tbBlok JOIN $tbUsers ON $tbBlok.idu=$tbUsers.idu WHERE $tbUsers.usrn = ? AND $tbUsers.prw > 0			
			
					while($dane = $pytanko->fetch_assoc()){
						//echo $dane['idwp'] .' '. $dane['idter'] .' '. $dane['idglo'] .' '. $dane['dod'] .' '. $dane['ddo'] .' '. $dane['zczym'] .'<br />';
						//echo $dane['idter'] .' '. $dane['trnaz'] .'<br />';
						
						echo"<div id=\"nr_ter\" class=\"k_kolumna\">";
							
							//-------NAGŁÓWEK  z nazwą terenu ---------
							if (strlen($dane["trnaz"]) > 20){ $nazwa_terenu = substr($dane["trnaz"], 0, 20) .'...';
							}else{ $nazwa_terenu = $dane["trnaz"]; }				
							echo"<div class=\"k_wiersz_one\">".$nazwa_terenu."</div>";
				
							echo"<input type=\"hidden\" name=\"k_teren[]\" value=\"". $dane["idter"] ."\" />";
							
							
							//-------DANE o opracowaniach ---------
							
							//$pytanko_opr = $db['link']->query("SELECT idter,idglo,dod,ddo,zczym FROM $tbS13 WHERE idter = $dane[idter] AND dod >= '$prs' AND ddo <= '$krs'"); // AND dod >= '$prs' AND ddo <= '$krs'
							$pytanko_opr = $db['link']->query("SELECT $tbS13.idter,$tbS13.idglo,$tbS13.dod,$tbS13.ddo,$tbS13.zczym,$tbGlos.imie,$tbGlos.nazw FROM $tbS13 JOIN $tbGlos ON $tbS13.idglo=$tbGlos.idglo WHERE idter = $dane[idter] AND dod >= '$prs' AND ddo <= '$krs' ORDER BY dod ASC"); // AND dod >= '$prs' AND ddo <= '$krs'
							
							$wpis=1;
							while($wpis<=20){
								$daneTerenu = $pytanko_opr->fetch_assoc();
								echo"<div class=\"k_wiersz\" id=\"w_".$dane["idter"]."_".$wpis."\">";
									
									// ------ PUBLIKACJA ------------
									$czym = array(1=>'cz',2=>'br',3=>'ks',4=>'tr',5=>'zp',6=>'zk',7=>'wk',8=>'bi',9=>'li');
									
									if($daneTerenu['zczym']=='' OR $daneTerenu['zczym']==0){
										echo "<select name=\"k_".$dane["idter"]."_czym[$wpis]\" class=\"k_czym\">";
											echo"<option></option>";										
											for($x=1;$x<=9;$x++){
												echo"<option";
												//if($teren_wiersz['zczym']==$x){echo" selected";}
												echo">".$czym[$x]."</option>";
											}						
										echo "</select>";	
									}else{
										if($daneTerenu['zczym']<>0){
											echo"<div class=\"k_czym_d\">". $czym[$daneTerenu['zczym']] ."</div>";	
										}else{
											echo '&nbsp;';
										}
										
									}
									
									
									//--------GŁOSICIEL------------
									if($daneTerenu['idglo']==''){
										echo "<select name=\"k_".$dane["idter"]."_kto[$wpis]\" class=\"k_kto\"><option></option>";							
											$pyt_glosiciele = $db['link']->query("SELECT idglo, imie, nazw FROM $tbGlos WHERE (stat = 1) OR (gr = 0 AND stat = 1) ORDER BY nazw, imie"); //WHERE (stat = 1 AND gr = $grnr)
											$ztejgr=0;
											while($glosiciel = $pyt_glosiciele->fetch_assoc()){
												echo "<option>".$glosiciel['nazw']." ".$glosiciel['imie']."</option>";	
												//if($daneTerenu['idglo']==$glosiciel['idglo']){ $ztejgr=1; }												
											}/*
											if($ztejgr!=1){
												$glosiciel_innagr = mysql_fetch_assoc(mysql_query("SELECT imie, nazw FROM $db_glos WHERE idglo = '$daneTerenu[idglo]'"));
												echo "<option>".$glosiciel_innagr['nazw']." ".$glosiciel_innagr['imie']."</option>";
											}*/
										echo "</select>";
									}else{
										echo"<div class=\"k_kto_d\">". $daneTerenu['imie'] ." ". $daneTerenu['nazw'] ."</div>";
									}
									
									echo"<br />";
									
									//--------DATY------------
									if($daneTerenu['dod']=='' OR $daneTerenu['dod']==0){
										echo"<input name=\"k_".$dane["idter"]."_dod[$wpis]\" class=\"k_datao\" value=\"".$daneTerenu["dod"]."\" />"; //type=\"date\" 
										// DATEPICKER -> http://www.devarticles.com/c/a/Web-Style-Sheets/Creating-a-Simple-Date-Picker-with-JavaScript-and-CSS/
									}else{
										echo"<div class=\"k_datao_d\">".$daneTerenu["dod"]."</div>";
									}
									if($daneTerenu['ddo']=='' OR $daneTerenu['ddo']==0){
										echo"<input name=\"k_".$dane["idter"]."_ddo[$wpis]\" class=\"k_datad\" value=\"".$daneTerenu["ddo"]."\" />";
										echo"<input type=\"hidden\" name=\"k_".$dane["idter"]."_wpis[]\" value=\"". $wpis ."\" />";										
									}else{
										echo"<div class=\"k_datad_d\">".$daneTerenu["ddo"]."</div><div class=\"tbr\"></div>";
									}
									
								echo '</div>';//koniec pojedynczego wiersza
								$wpis++;
							}//koniec while'a z odczytem kolejnych opracowań (wierszy)
						echo '</div>';//koniec kolumny
					}
					echo "<div class=\"tbr\"></div>";
					echo "</form>";
					echo "<br /><br />";
					
				echo '</div>';
				echo "<br /><br /><br /><br />";

				$pytanko->free();
				$pytanko_opr->free();
				$pyt_glosiciele->free();
				unset($prs);
				unset($krs);
				unset($dane_gr);
				unset($dane_str);
				unset($dane);
				unset($daneTerenu);
				unset($wpis);
				unset($x);
				unset($ztejgr);
				unset($glosiciel);
				
				
			}else{ //------------------ZAPISYWANIE DANYCH DO BAZY-----------------------------------------------------------------------------
				
				//----KTO I KIEDY ZAPISUJE----
				$opr = $_SESSION['oper'];
				
				//----------------------------
					
				if (isset($_POST['k_teren'])){ //zaznaczony teren pobrania (do opracowania)
					foreach ($_POST['k_teren'] as $idter){
						$dwp = date("Y-m-d H:i:s");
						//echo "<br />-----------------<br />".$idter."<br />-----------------<br />";						
						foreach ($_POST['k_'.$idter.'_wpis'] as $wpis){							
							
							if(!empty($_POST['k_'.$idter.'_dod'][$wpis]) AND !empty($_POST['k_'.$idter.'_kto'][$wpis])){
								
								$dod  = $_POST['k_'.$idter.'_dod'][$wpis];						
								$kto  = $_POST['k_'.$idter.'_kto'][$wpis];
									$nazw = substr($kto,  0, strpos($kto, ' '));
									$imie = substr($kto, strpos($kto, ' ')+1, strlen($kto) - strpos($kto, ' '));
									$q_glo = $db['link']->query("SELECT idglo FROM $tbGlos WHERE imie = '$imie' AND nazw = '$nazw'"); //znajdz glosiciela w bazie wg imienia i nazwiska					
									if($q_glo!=false){ $glo = $q_glo->fetch_assoc();}								
								
								$pytanko = $db['link']->query("SELECT count(*) as ile FROM $tbS13");
								$idWpisu = $pytanko->fetch_assoc();
								
								$pytanko = $db['link']->query("SELECT kart FROM $tbS13 WHERE id = (SELECT id FROM $tbS13 WHERE idter = $idter AND ddo = (SELECT MAX(ddo) FROM $tbS13 WHERE idter = $idter))"); //stan karty - czy jest czy brak					
								if($pytanko!=false){$karta =  $pytanko->fetch_assoc();$pytanko->free();
								}else{ $karta['kart'] = 0;}
								
								//echo $wpis." ".$kto."<>".$nazw."+".$imie." (".$glo['idglo'].") => ";
								//echo $kto." => ";
								
								if(!empty($_POST['k_'.$idter.'_ddo'][$wpis]) AND !empty($_POST['k_'.$idter.'_czym'][$wpis])){
									if($_POST['k_'.$idter.'_dod'][$wpis] != '0000-00-00'){
										//jest data od i data do -> nowy wpis
										$ddo  = $_POST['k_'.$idter.'_ddo'][$wpis];
										$czym = array('cz'=>1,'br'=>2,'ks'=>3,'tr'=>4,'zp'=>5,'zk'=>6,'wk'=>7,'bi'=>8,'li'=>9);									
										$zczym = $czym[$_POST['k_'.$idter.'_czym'][$wpis]];
																				
										$zapisz = $db['link']->query("INSERT INTO $tbS13 (idwp,idter,idglo,dod,ddo,rdz,zczym,dwpr,wprp,kart, dzdn, wpzd) values ($idWpisu[ile],$idter,$glo[idglo],'$dod','$ddo','1','$zczym','$dwp',$opr,'$karta[kart]','$dwp','$opr')"); 													
										$zapisz = $db['link']->query("UPDATE $tbTereny SET stus = 1 WHERE idter = $idter");
										
										//echo $_POST['k_'.$idter.'_dod'][$wpis]." = ".$_POST['k_'.$idter.'_ddo'][$wpis]." (".$zczym.")<br/>";	
									}
									
								}else{
									//jest data od -> nowy wpis																		
																		
									$zapisz = $db['link']->query("INSERT INTO $tbS13 (idwp,idter,idglo,dod,ddo,rdz,zczym,dwpr,wprp,kart, dzdn, wpzd) values ($idWpisu[ile],$idter,$glo[idglo],'$dod','0000-00-00','1','0','$dwp',$opr,'$karta[kart]','0000-00-00 00:00:00','0')");
									$zapisz = $db['link']->query("UPDATE $tbTereny SET stus = 0 WHERE idter = $idter");
									
									//echo $_POST['k_'.$idter.'_dod'][$wpis]." = 0000-00-00<br/>";
								}
								
							}else{
								if(!empty($_POST['k_'.$idter.'_ddo'][$wpis]) AND !empty($_POST['k_'.$idter.'_czym'][$wpis]) AND !array_key_exists($wpis,$_POST['k_'.$idter.'_dod'])){									
									//było
									
									$czym = array('cz'=>1,'br'=>2,'ks'=>3,'tr'=>4,'zp'=>5,'zk'=>6,'wk'=>7,'bi'=>8,'li'=>9);									
									$zczym = $czym[$_POST['k_'.$idter.'_czym'][$wpis]];
									$ddo  = $_POST['k_'.$idter.'_ddo'][$wpis];
									
									$pytanko = $db['link']->query("SELECT id FROM $tbS13 WHERE idter = $idter AND ddo = '0000-00-00'");
									$idWpis =  $pytanko->fetch_assoc();
									$pytanko->free();
									
									$pytanko = $db['link']->query("SELECT kart FROM $tbS13 WHERE id = (SELECT id FROM $tbS13 WHERE idter = $idter AND ddo = (SELECT MAX(ddo) FROM $tbS13 WHERE idter = $idter))"); //stan karty - czy jest czy brak					
									if($pytanko!=false){$karta =  $pytanko->fetch_assoc();$pytanko->free();
									}else{ $karta['kart'] = 0;}

									$zapisz = $db['link']->query("UPDATE $tbS13 SET ddo = '$ddo', rdz = 1, zczym = $zczym, kart = $karta[kart],  dzdn = '$dwp', wpzd = '$opr' WHERE id = $idWpis[id] AND idter = $idter");									
																		
									//echo "0  było  (".$idWpis['id'].") = ".$_POST['k_'.$idter.'_ddo'][$wpis]." (".$zczym.") k-".$karta['kart']."<br/>";										
								} 
							}
						}						
					}
				}
			}	
		}else{//jeśli prawa <4
			echo "<br /><br />";
		}
	}else{
		echo "nie zostałes upoważniony do modyfikacji tego zasobu.";
	}
?>