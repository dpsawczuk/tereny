<?php
echo '<h2>edycja głosiciela</h2>';
echo '<br />';
echo '<button class="ed" id="btn_edt_hlp">pomoc</button><button class="ed" id="btn_edt_new">nowy</button><button class="ed" id="btn_edt_edt">edycja</button>';
echo '<br /><br /><br />';

echo '<div class="pomoc">';
echo 'Formularz umożliwia dodawanie nowego głosiciela oraz edytowanie danych juz zapisanego w aplikacji.<br /><br />Aby dodać nowego klknij przycisk "nowy" - formularz będzie pusty.<br /><br />Aby edytowac dane istniejącego kliknij przycisk "edycja" - zostane otwarta lista głosicieli, których dane mozna poddać edycji.';
echo '</div>';

echo '<div class="edt_lst_glo" id="LISTA">';
$glosicieli = 80;
$lb = 0;
while($lb<$glosicieli){
	echo 'Imię Nazwisko '.$lb.'<br />';
	$lb++;
}
echo '</div>';




?>