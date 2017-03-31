$(window).load(function(){
	$(document).find('input.form-control').each(function(){
		if($(this).val() != ""){
			$(this).closest('div.float-group').find('.lbl-float').addClass('active');
			$(this).addClass('active');
		}
	});
});

$(document).find('.form-control').each(function(){
	if($(this).val() != ""){
		$(this).closest('div.float-group').find('.lbl-float').addClass('active');
		$(this).addClass('active');
	}
});

$(document).on('keyup', '.form-control', function(){
	if($(this).val() != ""){
		$(this).closest('div.float-group').find('.lbl-float').addClass('active');
		$(this).addClass('active');
	} else{
		$(this).closest('div.float-group').find('.lbl-float').removeClass('active');
		$(this).removeClass('active');
	}
});

$(document).on('click', '.lbl-float', function(){
	$(this).closest('div.float-group').find('.form-control').focus();
});