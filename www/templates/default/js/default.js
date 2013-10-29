
//Bootstrap fixes
jQuery(document).ready(function() {
	jQuery('div.control-group label').addClass('control-label');
	jQuery('img.calendar').addClass('add-on');
})

jQuery(document).ready(function(){

	jQuery('#addlevelsubmit').button();

	// Validate
	// http://bassistance.de/jquery-plugins/jquery-plugin-validation/
	// http://docs.jquery.com/Plugins/Validation/
	// http://docs.jquery.com/Plugins/Validation/validate#toptions
	
		
	  
	  // menu buttons 
	  jQuery('button#Profile').click(function(){
   document.location.href='http://projects.ammonitenetworks.com/crowdfunding/profile';
});
jQuery('button#Campaigns').click(function(){
   document.location.href='http://projects.ammonitenetworks.com/crowdfunding//my-projects';
})

		  
	  
	  
}); // end document.ready