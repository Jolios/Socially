$(document).ready(function(){
	$('#submit_post_profile').click(function() {
		$('#form_profile').submit();
		$("#post_form").modal('hide');

	});

	$('#input_rechercher').keypress(function(e){
		var key=e.which;
		if(key==13){
			$('#form_rech').submit();
			return false;
		}
	});

	$('#inputRech').focus(function() {
		$(this).animate({width: '250px'},500);
	});

	$('.btn_holder').on('click',function(){
		if($('#inputRech').val()){
		document.form_rech_h.submit();
		}
	})
});
