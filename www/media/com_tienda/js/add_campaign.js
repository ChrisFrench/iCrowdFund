
jQuery(document).ready(function() {
        jQuery("#product_description_short").limit({
            limit: 125,
            id_result: "counter",
            alertClass: "alert"
            });
         
         jQuery("#product_description").limit({
            limit: 1000,
            id_result: "longcounter",
            alertClass: "alert"
            });   
          jQuery("#campaign_shortdescription").limit({
            limit: 125,
            id_result: "counter",
            alertClass: "alert"
            });    
          
            
            
            
         });
