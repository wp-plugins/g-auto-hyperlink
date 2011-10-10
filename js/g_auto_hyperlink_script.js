jQuery(document).ready(function(){
	
	jQuery('#appearance').change(function(){
		var appearance_val = jQuery('#appearance').val();
		
		anim_reset(); 
		if(appearance_val == 'spec_cat'){
			jQuery('#spec_cat').slideDown(500);
		}
		
		if(appearance_val == 'spec_post' || appearance_val == 'spec_page'){
			jQuery('#spec_post_or_page').fadeIn(500);
		}
	});
	
	jQuery('#g-auto-hyperlink-form-add, #g-auto-hyperlink-form-edit').submit(function(){
		var keyword 			= jQuery('#keyword').val();
		var url 				= jQuery('#url').val();
		var title 				= jQuery('#title').val();
		var rel 				= jQuery('#rel').val();
		var target 				= jQuery('#target').val();
		var appearance 			= jQuery('#appearance').val();
		var spec_cat 			= jQuery('#spec_cat').val();
		var spec_post_or_page 	= jQuery('#spec_post_or_page').val();
		
		if(keyword.length > 0){
			hide_error('.keyword_error');
			
			if(url.length > 0){
				hide_error('.url_error');
				
				if(title.length > 0){
					hide_error('.title_error');
					var error = 0;
					var appearance_error_msg = '';
					
					switch(appearance){
						case "0":
								error = 1; 
								appearance_error_msg = 'Please choose an option';
							break;
						
						case "spec_cat":
								if(spec_cat == "0") {
									error = 1;
									appearance_error_msg = 'Please choose a specific category';
								}
							break;
							
						case "spec_post":
								if(spec_post_or_page == 0){
									error = 1; 
									appearance_error_msg = 'Please enter a specific post';
								}
							break;
						
						case "spec_page":
								if(spec_post_or_page == 0){
									error = 1; 
									appearance_error_msg = 'Please enter a specific page';
								}
							break;
					}
					
					if(error == 0){
						hide_error('.appearance_error');
						
						return true;
					}
					if(error == 1){
						show_error('.appearance_error', appearance_error_msg);
					}
					
				}else{
					show_error('.title_error', 'Please enter a non empty title');
				}
			}else{
				show_error('.url_error', 'Please enter a URL');
			} 
		}else{
			show_error('.keyword_error', 'Please enter a non empty keyword');
		}
		
		return false;
	});
	
});

function anim_reset(){
	jQuery('#spec_cat').slideUp(100);
	jQuery('#spec_post_or_page').fadeOut(100);
}

function show_error(elem, msg){
	jQuery(elem).fadeIn(300).html(msg);
}

function hide_error(elem){
	jQuery(elem).fadeOut(100);
}