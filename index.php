<?php
if(!isset($_SESSION)){ session_start(); }
$session_id = md5(uniqid($_SERVER['REMOTE_ADDR']));
$again = false;

	if(!isset ($_SESSION['logowanie'])){

		$_SESSION['again'] = false;
		
		if (!isset($_SESSION['login_start'])){
			//session_regenerate_id(true);
			$_SESSION['login_start'] = true;
			$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
		}else{	
			if (($_SESSION['login_start']) and ($_SESSION['ip'] == $_SERVER['REMOTE_ADDR'])){
				if((!isset($_SESSION['user'])) and (isset($_POST['login']))){
					$tmp_user=htmlspecialchars($_POST['login']);
					$tmp_user=addslashes($tmp_user);
					$_SESSION['user'] = $tmp_user;					
				}	
			}
		}
		
		if((!isset($_POST['login']) && !isset($_POST['password'])) || $_SESSION['again']){
			
			echo "<!DOCTYPE html>\n";
			echo "<html>\n";
			echo "<head>\n";
			echo "\t<meta http-equiv=\"Content-type\" content=\"text/html; charset=utf-8\">	\n";
			echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
			echo "\t<link rel=\"Shortcut icon\" href=\"ico/ficon.ico\" />";
			echo "\t<link rel=\"stylesheet\" href=\"css/z18ter_login.css\" type=\"text/css\">\n";			
			echo "\t<title>.:</title>\n";
			echo "</head>\n\n";
			echo "<body>\n";
			
			if (isset($_SESSION['user'])){
				include("incl/main_cfg.php");
				$db = db_lacz(); //main_cfg
				$baza = mysqli_select_db($db['link'],$dbOpcje);		
				$liczLnk = $db['link'] -> prepare("SELECT $tbBlok.licznik FROM $tbBlok JOIN $tbUsers ON $tbBlok.idu=$tbUsers.idu WHERE $tbUsers.usrn = ? AND $tbUsers.prw > 0");			
				$liczLnk->bind_param('s', $_SESSION['user']);
				$liczLnk->execute();
				$wynik = $liczLnk->get_result();																	
							
				if ($wynik !== false){
					$lb = $wynik->fetch_assoc();
				}
				@$db['link']->close();
			}else{
				$lb['licznik']=1;
			}
				
			if ($lb['licznik']>3){
				echo"<div id=\"middle\"><br /><br />twoje konto zostało zablokowane.<br />skontaktuj się z administratorem</div>";					
			}else{				
				echo"\t<form id=\"middle\" action=\"".$_SERVER["PHP_SELF"] ."\" method=\"POST\" name=\"flogin\">";
				echo"\t\t<input type=\"text\" name=\"login\" class=\"l_i\"/><input type=\"reset\" value=\"reset\" class=\"l_p\"/>";
				echo"\t\t<input type=\"password\" name=\"haslo\" class=\"l_i\"/><input type=\"submit\" value=\"send\" class=\"l_p\"/>";
				echo "\t\t<input type=\"hidden\" name=\"STEP\" value=\"2\" />\n";
				echo"\t</form>";
			}
						
			
			
		}else{ //istnieją dane przesłane z formularza autoryzacyjnego
			
			if (isset($_SESSION['user'])){
				
				include("incl/main_cfg.php");
				
				
				$user = $_SESSION['user'];
				$md5_pass=md5($_POST['haslo']);
								
				$db = db_lacz(); //main_cfg
				if($db['status']) { //jest połączenie
					$db['link']->select_db($dbOpcje);
					$wynik = @$db['link'] -> query("SELECT COUNT(*) AS ileuser FROM users WHERE usrn = '$user' AND pswd = '$md5_pass' AND prw > 0");					
					
					if ($wynik !== false){ //poprawna odpowiedź	czyli jest taki uzytkowanik i hasło jest zgodne					
						$dane = $wynik->fetch_assoc();
						//echo $dane['ileuser']; //tylko do testów
						if($dane['ileuser']==1){
							
							$podanie = $db['link']->prepare("SELECT $tbUsers.idu, $tbUsers.zbnr, $tbZbory.zbnaz, $tbUsers.idglo, $tbUsers.imie, $tbUsers.nazw, $tbUsers.prw FROM $tbUsers JOIN $tbZbory ON $tbUsers.zbnr=$tbZbory.zbnr WHERE $tbUsers.usrn = ? AND $tbUsers.pswd = ? AND $tbUsers.prw > 0");							
							$podanie->bind_param('ss', $user, $md5_pass);
							$podanie->execute();
							$wynik = $podanie->get_result();														
							$dane = $wynik->fetch_assoc();
							
							$_SESSION['logowanie'] = 'poprawne';
							$_SESSION['prawa'] = $dane['prw'];
							$_SESSION['oper'] = $dane['idglo'];
							//setcookie ("uzytkownik", $dane['usrn']);  /* traci ważność za godzinę */
							
							$_SESSION['kto']=$dane['imie']." ".$dane['nazw'];
							$_SESSION['zbor']=$dane['zbnaz'];
							$_SESSION['zbornr']=$dane['zbnr'];
							
							$czassesji = time()+9600;						
							
							//zapisz czas sesji w bazie
							$sesja = $db['link']->query ("UPDATE $tbUsers SET ses = $czassesji WHERE idu = '$dane[idu]' AND idglo = '$dane[idglo]' AND zbnr = '$dane[zbnr]'");
							//kasuj licznik nieprawoidłowych logowań
							@$db['link']->query("DELETE FROM $tbBlok WHERE idu=(SELECT idu FROM $tbUsers WHERE usrn = '$user' AND prw > 0)");
							if ($sesja === false){
								echo"nie zapis sesji". $db['link']->errno ."<br/>";
							}
							header('Location: index.php');													
							
							//echo $dane['imie']." ".$dane['nazw']." ".$dane['zbnaz']." ".$dane['prw']." ".$_SERVER['HTTP_HOST'];
							
							//STATYSTYKI
							//$dane['zbnr']
							$add = @$db['link'] -> query("INSERT INTO $tbBlok (idu,licznik) values ((SELECT idu FROM $tbUsers WHERE usrn = '$user' AND prw > 0),1)");
							
							unset($dane);
							unset($czassesji);
							unset($sesja);
							
							
							
						}else{ //nieprawidłowe dane logowania		
						
							 											
							
							$kiedy = date("Y-m-d H:i:s");
							$ip = $_SERVER['REMOTE_ADDR'];
							
							//sprawdzenie czy to pomyłka hasła
							$pomylka = @$db['link'] -> query("SELECT COUNT(*) AS ileuser FROM $tbUsers WHERE usrn = '$user' AND prw > 0");					
							if ($pomylka !== false){
								$info = $pomylka->fetch_assoc();
								if($info['ileuser']==1){
									echo $user."<br />źle podano <i>hasło</i>";
									$kod_uwagi = 1; // pomyłka hasła - mniejsze zagrożenie
									
									//licznik nieprawidłowych logowań
									$licznik = @$db['link'] -> query("SELECT COUNT(*) AS ile FROM $tbBlok JOIN $tbUsers ON $tbBlok.idu=$tbUsers.idu WHERE $tbUsers.usrn='$user' AND $tbUsers.prw > 0");									
									if ($licznik !== false){
										$licz = $licznik->fetch_assoc();
										if($licz['ile']>0){
											$plus = @$db['link'] -> query("UPDATE $tbBlok SET licznik=licznik+1 WHERE idu=(SELECT idu FROM $tbUsers WHERE usrn = '$user' AND prw > 0)");
										}else{
											//$idu = 2;//(SELECT idu FROM $tbUsers WHERE usrn = '$user' AND prw > 0)
											$add = @$db['link'] -> query("INSERT INTO $tbBlok (idu,licznik) values ((SELECT idu FROM $tbUsers WHERE usrn = '$user' AND prw > 0),1)");
										}
									}														
									
								}else{
									$kod_uwagi = 2; //2-ważne
									echo "nie ma takiego użytkownika<br />";
									if(!isset($_SERVER['HTTP_REFERRER'])){
										echo "rejestruj";
										//formularz do rejestracji								
										//zapis do db dodatkowej tabeli z prosbami o rejestrację. - idrej, idglo, data, status (ok, nok, wait),
										//$qrejestracja = mysql_query("INSERT INTO $db_rej (idwp,idglo,drej,stus,ip) values ($kto[idglo],$kiedy,0,'$ip')") or die (mysql_error());
									}
								}
							
							}
							
							//echo "<div class=\"middle\">log: ".$kiedy."</div>";
								
							$loguj = $db['link']->query("INSERT INTO logs (kiedy,ip,user,pswd,waga) values ('$kiedy','$ip','$_POST[login]','$_POST[haslo]',$kod_uwagi)");
							if ($loguj === false){
								echo"nie zapis ". $db['link']->errno ."<br/>";
							}							
							
							$_SESSION['again'] = true;
							
							unset($kiedy);
							unset($ip);
							unset($pomylka);
							unset($kod_uwagi);
							unset($licznik);
							unset($add);
							
							header('Location: index.php');
						}														
						
					}else{
						echo"brak pol z b.d.";
					}
				}
			}	
		} 

		echo "</body>\n</html>";
	
	}else{ //zalogowany
	
		if($_SESSION['ip'] = $_SERVER['REMOTE_ADDR']){
				
			include("incl/main_cfg.php");
			
			$db = db_lacz(); //main_cfg
			$db['link']->select_db($dbOpcje);
			$authUserLnk = $db['link']->prepare("SELECT idu, zbnr, ses FROM $tbUsers WHERE usrn = ?");
			$authUserLnk->bind_param('s', $_SESSION['user']);
			$authUserLnk->execute();
			$wynik = $authUserLnk->get_result();														
			$authUser = $wynik->fetch_assoc();		
			
			if (($authUser['ses'] > time()) and ($_SESSION['logowanie'] == 'poprawne')){// and (isset ($_COOKIE['uzytkownik'])) and ($_SESSION['logowanie'] == 'poprawne')){ //zalogowany poprawnie
	
				$sesja = $db['link']->query ("UPDATE $tbUsers SET ses = $authUser[ses] WHERE idu = '$authUser[idu]'");

				//if ($user['urzdz'] == 1){$urzadzenie = '_pc';};
				//if ($user['urzdz'] == 2){$urzadzenie = '_pda';};
				if($_GET[$strona_glowna]!='edt_krt_pdf'){
					echo "<!DOCTYPE html>\n";
					echo "<html>\n";
					echo "<head>\n";
					echo "\t<meta http-equiv=\"Content-type\" content=\"text/html; charset=utf-8\">	\n";
					echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
					echo "\t<link rel=\"Shortcut icon\" href=\"ico/ficon.ico\" />";
					//echo "\t<link rel=\"stylesheet\" href=\"css/datepicker.css\" type=\"text/css\">\n";

	//responsywne css		!!!!		
					
					echo "\t<link rel=\"Stylesheet\" type=\"text/css\" href=\"css/jquery-ui.css\" />";
					echo "\t<link rel=\"Stylesheet\" type=\"text/css\" href=\"css/z18ter_main_pc.css\" />";
					echo "\t<link rel=\"stylesheet\" media=\"(min-width: 1020px)\" href=\"css/z18ter_max.css\">";
					echo "\t<link rel=\"stylesheet\" media=\"(max-width: 1020px)\" href=\"css/z18ter_min.css\">";
					
					echo "\t<script type=\"text/javascript\" src=\"js/jquery-1.11.3.min.js\"></script>";
					echo "\t<script type=\"text/javascript\" src=\"js/jquery-ui.js\"></script>";
					echo "\t<script type=\"text/javascript\" src=\"js/z18ter.js\"></script>";			
					//echo "\t<script src=\"js/z18ter-datepicker.js\"></script>\n";
					
					echo "\t<title>.:t</title>\n";
					
					echo "</head>\n\n";
					echo "<body>\n";
					
				
					echo "<div id=\"top\">";
	//--------nagłówek----------
						echo "<header id=\"NAGLOWEK\">";
						//echo $_SERVER['HTTP_USER_AGENT'] . "<br />";
						//	$browser = get_browser(null, true);
						//	print_r($browser[browser]);
						
							if(isset($_SESSION['kto']) && isset($_SESSION['zbor'])&& isset($_SESSION['zbornr'])){
								echo $_SESSION['kto']."<br/>".$_SESSION['zbor']."(".$_SESSION['zbornr'].")";
							}
						echo"</header>";
						
						echo "<div class=\"ramka\">";					
	//--------menu----------
							echo "<nav id=\"MENU\">";
								
								//$dbUsers = 'ter_pl_407_'. $authUser['zbnr'];
								//$db['link']->select_db($dbUsers);
								
								$menuGrp =  $db['link']->query("SELECT grp, pgrp, nzw FROM $tbMenu WHERE prw <= $_SESSION[prawa] AND pgrp = 0 ORDER BY grp ASC");							
								echo "<ol id=\"menu\">";							
								while ($grupy = $menuGrp->fetch_assoc()) {
								
									echo "<li class=\"".$grupy['nzw']."\"><a href=\"#\">".$grupy['nzw']."</a>";
									echo "<ul class=\"".$grupy['nzw']."\">";
									
									$menuPgrp =  $db['link']->query("SELECT * FROM $tbMenu WHERE prw <= $_SESSION[prawa] AND grp = $grupy[grp] AND pgrp != 0 ORDER BY pgrp ASC");
									while ($pozycje = $menuPgrp->fetch_assoc()) {
										echo "<li><a href=\"".$pozycje['lnk']."\">".$pozycje['nzw']."</a></li>";
									}					
									echo "</ul></li>";
								}
								echo "</ol>";		
							echo "</nav>";
							
							$menuGrp->free();
							$menuPgrp->free();
							unset($grupy);
							unset($pozycje);						
							$db['link']->close();

	//--------dodatki----------
					echo "<aside id=\"INFORMACJE\">";
				
						//echo "Dodatkowe informacje Dodatkowe informacje Dodatkowe informacje Dodatkowe informacje Dodatkowe informacje Dodatkowe informacje";
						if (isset($_GET[$strona_glowna])){
							switch ($_GET[$strona_glowna]){
								//--analiza--	
									case 'spr_krt';
										//include('incl/analiza_kartoteki.php');
										echo "dodatkowe informqacje o analizowanej kartotece";
										break;		
								//--edycja--		
									case 'edt_krt';
										include('incl/inf_kartoteki.php');
										break;							
									case 'edt_lst';
										include('incl/inf_listy.php');
										break;							
									case 'edt_tel';
										include('incl/inf_telefonow.php');
										break;
									case 'edt_ter';
										include('incl/inf_terenow.php');
										break;
									case 'edt_glo';
										include('incl/inf_glosicieli.php');
										break;
									case 'edt_grp';
										include('incl/inf_grup.php');
										break;							
								}//switch
						}else{
						
							//LOGI
							if($_SESSION['prawa']==6){
								$db = db_lacz(); //main_cfg
								$db['link']->select_db($dbOpcje);
								$logListLnk =  $db['link']->query("SELECT * FROM $tbLogs WHERE ok=0 ORDER BY waga DESC, kiedy ASC");							
								echo"<form id=\"logs\" action=\"".$_SERVER["PHP_SELF"] ."\" method=\"POST\" name=\"logs\"><br/>";
								echo"<div class=\"nag\">BEZPIECZEŃSTWO</div>";
								while ($logList = $logListLnk->fetch_assoc()){
									if($logList['waga']>1){
										echo "<h9 class=\"nok\">";
									}else{
										echo "<h9>";
									}
									echo "<input type=\"checkbox\" name=\"nazwa\" value=\"wartość\" /> ".$logList['kiedy']." ".$logList['user']." ".$logList['pswd']."</h9><br/>";//." ".$logList['waga'] .$logList['ip']." "
								}
								echo "<input type=\"submit\" value=\"sprawdzone\" class=\"ok\"/>";
								echo "</form>";
							}
							//-LOGI END
						}
					echo "</aside>";
				
//--------treść----------
					echo "<article id=\"TRESC\">";		
				}
					if (!isset($_GET[$strona_glowna])){	
						include('incl/inf_raport.php');												
						if($_SESSION['prawa']==5){ //obwodowy
							
							//sprawozdanie z częstotliwości opracowań terenów w podanym okresie
							//sprawozdaie z równomierności opracowania terenu
							//przekroczenia terminów w podanym okresie
						}
					}else{
						switch ($_GET[$strona_glowna]){
						//--analiza--	
							case 'spr_krt';
								include('incl/analiza_kartoteki.php');
								break;		
						//--edycja--		
							case 'edt_krt';
								include('incl/edycja_kartoteki.php');
								break;
							case 'edt_krt_pdf'; //s13 PDF
								//print_r(headers_list());
								//header_remove(headers_list());
								header("Location: incl/pdf_s13.php");//header("Location: incl/pdf_s13.php?id=".$_GET['id']);
								break;
							case 'edt_lst';
								include('incl/edycja_listy.php');
								break;
							case 'edt_lst_pdf'; //lista PDF 
								header("Location: incl/pdf_lista.php?gr=".$_GET['gr']);
								break;
							case 'edt_tel';
								include('incl/edycja_telefonow.php');
								break;
							case 'edt_ter';
								include('incl/edycja_terenow.php');
								break;
							case 'edt_glo';
								include('incl/edycja_glosicieli.php');
								break;
							case 'edt_grp';
								include('incl/edycja_grup.php');
								break;
						//--opcje--		
							case 'logout';
								unset($_SESSION['user']);
								unset($_SESSION['logowanie']);
								session_destroy();
								header('Location: index.php');
								break;							
						}//switch
					}			
				echo "</article>";				
				echo "</div>";
				echo "<footer id=\"STOPKA\">.</footer>";
				echo "</div>";
			}else{
				unset($_SESSION['user']);
				unset($_SESSION['logowanie']);
				session_destroy();
				header('Location: index.php');
				break;
			}
		}//sprawdzania IP w sesji
	}
?>

</body>
</html>

