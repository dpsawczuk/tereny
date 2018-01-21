$(document).ready(function() {

 $(document).ready(function(){	
	
	$('#menu').css({'position': 'fixed','top': '250px',});
 	
	$("#menu li").hover(function(){
		$("#menu ul").stop();
		$("#menu ul").fadein(200);		
	});

	//---automatyczne zaznaczanie chk terenu po wybraniu daty
	$('#f_lista .ter_glos').change(function(){
		if($(this).val != ''){
			var nr = $(this).attr('id').substring(14,$(this).attr('name').length);				
			$('input[value='+nr+']').attr('checked', true);// $('#szukacz').val(text)			
		}else{
			$('input[value='+nr+']').attr('checked', false);
		}

	});
	
	$('#f_lista .ter_dat').change(function () {
		var nr = $(this).attr('name').substring(1,$(this).attr('name').length-3);			
		$('input[value='+nr+']').attr('checked', true)
		var dataTxT = $(this).val();
		if(!isDate(dataTxT)[0]){
			$(this).css("border", "1px solid red");
			var gdzie = '#'+nr;
			$(gdzie).append('<div class="err">'+isDate(dataTxT)[1]+'</div>');
		}else{
			$(this).css("border", "1px solid #CACFD2");
			$('.err', gdzie).empty().remove();
		}
	}); 
	
	//---walidacja
	$("#f_lista .teren").mouseout(function(){
		var nr = $(this).attr('id');
		if($('input[value='+nr+']').prop('checked')){
			if($(this).find('.ter_opr_zcz').length){
				if (!$('input[name=t'+nr+'_z]:checked').val()){			   
				   $(this).find('.ter_opr_zcz').css("border", "1px solid red");
				   if(!$(this).find('.errp').length){
					 $(this).append('<div class="errp">wybierz rodzaj publikacji z jakimi opracowano teren</div>');
				   }
				}else{
					$(this).find('.ter_opr_zcz').css("border", "1px solid #C3C3C3");
					$('.errp', this).empty().remove();
				}
			}
			if($(this).find('#ter_dost_glos_'+nr).length){
				if (!$(this).find('#ter_dost_glos_'+nr).val()){
					$(this).find('#ter_dost_glos_'+nr).css("border", "1px solid red");
					if(!$(this).find('.errp').length){
						$(this).append('<div class="errp">wybierz głosiciela, któremu przydzielono teren</div>');
					}
				}else{
					$(this).find('#ter_dost_glos_'+nr).css("border", "1px solid #C3C3C3");
					$('.errp', this).empty().remove();
				}				
			}
		}else{
			$(this).find('.ter_opr_zcz').css("border", "1px solid #C3C3C3");
			$(this).find('.ter_dat').css("border", "1px solid #CACFD2");
			$(this).find('#ter_dost_glos_'+nr).css("border", "1px solid #C3C3C3");			
			$('.err', this).empty().remove();
			$('.errp', this).empty().remove();
		}
	});
	
	
	$('#f_lista .ter_opr_zcz').mouseover(function(){		
        $('body').append('<div class="dymek"></div>');
		$('div.dymek').text($(this).find('input').attr('value')).show();
		$(this).removeAttr("title");
    })
    $('#f_lista .ter_opr_zcz').mouseout(function(){
        $('div.dymek').hide().remove();
    })
    $('#f_lista .ter_opr_zcz').mousemove(function(e){
        $('div.dymek').css('left',e.pageX - 35);
        $('div.dymek').css('top',e.pageY - 45);
    });	
	
	//---edt lst wysył spr ---------
	$('#f_lista').submit(function(event){
		if ( !$(this).find('.errp').length) {
			return;
		} 
		alert('usuń błędy!');		
		event.preventDefault();
	});
	
	
	//---lista terenów
	$('#t_lista').on('click','.teren', function(){ 
	//$("#t_lista .teren").click(function(){
		if(!$(this).find('.szczeg').length){
			var nr = $(this).attr('id');
			$(this).append('<div class="szczeg">szczegoly</div>');
			$(this).find('.szczeg').load('incl/edycja_ts.php?ter='+nr); //m_inf.php?rdz=' + popRDz + '&ter=' + popTNr
		}/*else{
			$('.szczeg', this).empty().remove();
		}*/
	});
	
	
	/* ----- szukacz terenów --- */
	$('#sterBox .szTeren').keyup(function(){
		$('#terList').text('opcja w trakcie przygotowania');
	});
	$('#terList .l_szTerenu').keyup(function(){
		$('#terList .l_wiersze').text('opcja w trakcie przygotowania');
	});
	$('#terList .l_szKto').keyup(function(){
		$('#terList .l_wiersze').text('opcja w trakcie przygotowania');
	});

	
	var stickySterBoxTop = $('#sterBox').offset().top;
	$(window).scroll(function() {
		if ($(window).scrollTop() > 85) {
			$('#sterBox').css({'position':'fixed','top':'52px','padding-bottom':'60px','box-shadow': '0px 20px 50px #fff'});
			$('#terListBox .k_wiersz_one').css({'position':'fixed','top':'160px','background-color':'#FFF'});
		}else{
			if ($(window).scrollTop() <= 85){
				$('#sterBox').css({'position':'','top':'','padding-bottom':'','box-shadow':''});
				$('#terListBox .k_wiersz_one').css({'position':'','top':'','background-color':''});
			}
		}
	});
	//-----------KARTOTEKA wybór S-13------------------------------
	$('#s13filter').click(function(){
		//alert('S-13');
		$('#sterBox .filter').css({'visibility': 'visible','display': 'block'});
	});
	
	//----------KARTOTEKA WALIDACJA PÓL------------
	/*$("#terListBox .k_wiersz").mouseout(function(){
		sprawdz($(this).attr('id'));
	}); */
	$("#terListBox .k_czym").bind({
		change:function() {		
			sprawdz($(this).closest( ".k_wiersz" ).attr('id'));
		}
	})
	$("#terListBox .k_kto").bind({
		change:function() {		
			sprawdz($(this).closest( ".k_wiersz" ).attr('id'));
		}
	})
	$("#terListBox .k_datao").bind({
		change:function() {		
			sprawdz($(this).closest( ".k_wiersz" ).attr('id'));
		}
	})
	$("#terListBox .k_datad").bind({
		change:function() {		
			sprawdz($(this).closest( ".k_wiersz" ).attr('id'));
		}
	})
	//----------------------------------------------
	
	//----------KARTOTEKA POLA DATY-----------------
	$(document).on('focus', '.k_datao', function() {
		$(this).datepicker();
	});
	$(document).on('focus', '.k_datad', function() {
		$(this).datepicker();
	});
	//----------------------------------------------
	//----------LISTA POLA DATY-----------------
	$(document).on('focus', '.l_datao', function() {
		$(this).datepicker();
	});
	$(document).on('focus', '.l_datad', function() {
		$(this).datepicker(); 
	});
	//----------------------------------------------
	//$('.l_czym').mouseover(function(){
	//	var nazwa = $(this).attr('name');
	//	$(nazwa).click();//
	//});
	$('.l_kol_naz').mouseover(function(){
		var nazwa = $(this).val();
		//$('.l_wiersz').append('<div>cała</div>');
	});
});

function sprawdz(adres){
	if($('#'+adres).find('.k_czym').length){ //k_czym - k_kto - k_datao - k_datad
		if (($('#'+adres).find('.k_datad').val() && $('#'+adres).find('.k_datad').val()!='0000-00-00' && !$('#'+adres).find('.k_czym').val()) || ($('#'+adres).find('.k_czym').val() && ($('#'+adres).find('.k_datad').val()=='0000-00-00' || !$('#'+adres).find('.k_datad').val()))) {
			$('#'+adres).css("background-color", "red");
			$('#INFORMACJE .bledy').slideDown(600);
			$('#INFORMACJE .bledy').append('<div class ="err" id="err_'+adres+'">brak daty zakończenia lub publikacji</div>');
		}else{
			$('#'+adres).css("background-color", "");
			$('#err_'+adres).remove();
		}
		if($('#'+adres).find('.k_datad').val() && !isDate($('#'+adres).find('.k_datad').val())[0]){
			$('#'+adres).css("background-color", "red");
			$('#INFORMACJE .bledy').slideDown(600);
			$('#INFORMACJE .bledy').append('<div class ="err" id="err_'+adres+'">nieprawidłowa data zakończenia</div>');
			console.log('err_'+adres);
		}
	}
	if($('#'+adres).find('.k_datao').length){ //k_czym - k_kto - k_datao - k_datad
		if (($('#'+adres).find('.k_datao').val() && !$('#'+adres).find('.k_kto').val()) || ($('#'+adres).find('.k_kto').val() && !$('#'+adres).find('.k_datao').val()) || ($('#'+adres).find('.k_datao').val() && $('#'+adres).find('.k_czym').val() && !$('#'+adres).find('.k_datad').val())){
			blad(adres,'brak daty zakończenia lub publikacji');			
		}
		if($('#'+adres).find('.k_datao').val() && !isDate($('#'+adres).find('.k_datao').val())[0]){
			blad(adres,'nieprawidłowa data rozpoczęcia - '+isDate($('#'+adres).find('.k_datao').val())[1]);			
		}
	}
	
	
};

function blad(adres, tekst){
	$('#'+adres).css("background-color", "red");
	$('#INFORMACJE .bledy').slideDown(600);
	$('#INFORMACJE .bledy').append('<div class ="err" id="err_'+adres+'">'+tekst+'</div>');	
}

function isDate(txtDate){
  var currVal = txtDate;
  if(currVal == '')
	return [false,'brak daty'];
  
  //Declare Regex  
  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //mm-dd-rrrr
  var rxDatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/; //rrrr-mm-dd
  var dtArray = currVal.match(rxDatePattern); // is format OK?

  if (dtArray == null)
     return [false,'niepoprawny format daty - powinno być rrrr-mm-dd'];
  
  //future date
  var today = new Date();
  var useDate = new Date(dtArray[1], dtArray[3] -1 , dtArray[5]);
  if (useDate  > today)
	 return [false,'nie można wybrać daty z przyszłości'];
	 
  //Checks for mm/dd/yyyy format.
  dtMonth = dtArray[3];
  dtDay= dtArray[5];
  dtYear = dtArray[1];  

  if (dtMonth < 1 || dtMonth > 12)
      return [false,'wybierz miesiąc od 1 do 12'];
  else if (dtDay < 1 || dtDay> 31)
      return [false,'wybierz dzień między 1 a 31'];
  else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return [false,'miesiac '+dtMonth+' nie ma '+dtDay+' dni'];
  else if (dtMonth == 2){
     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
     if (dtDay> 29 || (dtDay ==29 && !isleap))
          return [false,'w roku '+dtYear+' luty nie ma '+dtDay+' dni'];
  }
  return [true,''];
}
	
/* edycja głosicieli*/
	$('#INFORMACJE button').click(function(){
		var co = $(this).attr('id');
		switch(co){
			case 'btn_edt_hlp':
				$('#INFORMACJE .pomoc').css({'visibility': 'visible','display': 'block'});
				$('#INFORMACJE .edt_lst_glo').css({'display': 'none'});
				break;
			case 'btn_edt_new':
				$('#INFORMACJE .pomoc').css({'visibility': 'visibible','display': 'block'});
				$('#INFORMACJE .edt_lst_glo').css({'display': 'none'});
				break;
			case 'btn_edt_edt':
				$('#INFORMACJE .pomoc').css({'display': 'none'});
				$('#INFORMACJE .edt_lst_glo').css({'visibility': 'visible','display': 'block'}).show(200);
				break;
		}
	});

});

