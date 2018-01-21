<?php
$dane = array('imię','nazwisko','funkcja','status','grupa','login','hasło');
echo '<br /><br /><br /><br /><br />';

echo '<div class="lewy">';
	echo '<form action ="" method="post" id="form">';
	echo '<div class="opis">imię</div><div class="pole"><input type="text" class="tekst"></div><div class="br"></div>';
	echo '<div class="opis">nazwisko</div><div class="pole"><input type="text" class="tekst"></div><div class="br"></div>';
	echo '<div class="opis">funkcja</div><div class="pole"><input type="checkbox" name="funkcja" value="pionier" id="pionier" /><label for="pionier">pionier</label><input type="checkbox" name="funkcja" value="sluga" id="sluga"/><label for="sluga">sługa </label><input type="checkbox" name="funkcja" value="starszy" id="starszy"/><label for="starszy">starszy</label></div><div class="br"></div>';
	echo '<div class="opis">status</div><div class="pole"><input type="radio" name="status" value="czynny" checked="checked" id="gl_czynny"/><label for="gl_czynny">czynny</label><input type="radio" name="status" value="archiwalny" id="gl_arch"/><label for="gl_arch">archiwalny</label></div><div class="br"></div>';
	echo '<div class="opis">grupa</div><div class="pole">';
	
	$db = db_lacz(); //main_cfg
	$db['link']->select_db($dbOpcje);
	$podanie = $db['link']->prepare("SELECT zbgr FROM $tbUsers JOIN $tbZbory ON $tbUsers.zbnr=$tbZbory.zbnr WHERE $tbUsers.zbnr = ? AND $tbUsers.prw > 0");							
	$podanie->bind_param('s', $_SESSION['zbornr']);
	$podanie->execute();
	$wynik = $podanie->get_result();														
	$dane = $wynik->fetch_assoc();
	
	for($gr=1;$gr<=$dane['zbgr'];$gr++){
		echo '<input type="radio" name="grupa" value="'.$gr.'" id="gr_'.$gr.'"/><label for="gr_'.$gr.'">'.$gr.'</label>';
	}
	echo '</div><div class="br"></div>';
	echo '<div class="opis">login</div><div class="pole"><input type="text" class="tekst"></div><div class="br"></div>';
	echo '<div class="opis">hasło</div><div class="pole"><input type="text" class="tekst"></div><div class="br"></div>';
	echo '<div class="opis">prawa</div><div class="pole"><input type="radio" name="prawa" value="teren" checked="checked" id="pr_ter"/><label for="pr_ter">teren</label><input type="radio" name="prawa" value="grupa" id="pr_gr"/><label for="pr_gr">grupa</label><input type="radio" name="prawa" value="nadzorca" id="pr_ns"/><label for="pr_ns">nadzorca</label><input type="radio" name="prawa" value="admin" id="pr_op"/><label for="pr_op">administrator</label></div><div class="br"></div>';
	echo '</form>';
echo '</div>';
	
	$wynik->free();
	unset($dane);						
	$db['link']->close();
	
echo '<div class="prawy">';
	echo '<div class="edt_lst_glo" id="LISTA">';
	$glosicieli = 80;
	$lb = 0;
	while($lb<$glosicieli){
		echo '&lArr; Imię Nazwisko '.$lb.'<br />';
		$lb++;
	}
	echo '</div>';
echo '</div>';
?>