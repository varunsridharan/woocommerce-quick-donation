jQuery(document).ready(function(){

	
	jQuery('.wc_qd_system_toold td').on("click", ".wcqdAjaxCall",function(){
		var autoRemove = false;
		if(jQuery(this).hasClass('wcqdAutoRemove')){
			autoRemove = true;
		}
		var Spinner = jQuery('<span class="spinner WCQDSpinner"></span>');
		var Clicked = jQuery(this);
		var POST_URL = jQuery(this).attr('href');

		jQuery(this).attr('disabled',true);
		jQuery(this).append(Spinner);
		Spinner.css('visibility','visible'); 
		Spinner.css('float','none');
		Spinner.css('margin-top','0px');
		
		jQuery.post(POST_URL, function(response) {
			Spinner.remove();
			if(autoRemove){
				Clicked.parent().html(response);
			}else{
				Clicked.next().html(response);
				Clicked.removeAttr('disabled',false);
			}
		});		
	
	});

	
});