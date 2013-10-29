<?php
DSC::loadBootstrap('2.2.2', FALSE); 
JHTML::_('stylesheet', 'templates/default/css/default.css');

$item = @$this->cartobj->items[0];
$subtotal = @$this->cartobj->subtotal;
$state = @$this->state;
$router = new TiendaHelperRoute();
$quantities = array();
$options = array();
$options['num_decimals'] = 0;

?>
<script type="text/javascript">
tiendaJQ(function(){
	tiendaJQ("#btnContinueFund").on("click", function(e){
		Dsc.newModal("<?php echo JText::_("COM_TIENDA_VALIDATING")?>");
	
	    var price = tiendaJQ("input[name='price']").val();
		var url = com_tienda.jbase+"index.php?option=com_tienda&controller=campaigns&task=validateFundingPrice&format=raw";
	    var a = new Request({
	        url: url,
	        method:"post",
	        data:{"price": price},
	        onSuccess: function(response){
	            var resp = JSON.decode(response, false);
	            if (resp.error != '1')
	            {
				//	self.close();
					window.location.href = "<?php echo JRoute::_( 'index.php?option=com_tienda&view=opc&Itemid='.$router->findItemid( array('view'=>'opc') ) );?>?tmpl=component";
	            }
	            else
	            {
	            	var m = tiendaJQ("#msg");
	            	m.html(resp.msg);
	            	tiendaJQ("dd.notice", m).removeClass("fade");
	            	
		            document.body.removeChild( document.getElementById('dscModal') );
	            }
	        }
	    }).send();
	    return false;
	});
});
</script>

<div class="container">
    <?php if (!empty($item)) {
		$c = TiendaHelperCampaign::getCampaignFromProduct($item->product_id);
		Tienda::load( "TiendaModelCampaigns", "models.campaigns" );
    	$m = new TiendaModelCampaigns();
		$m -> setId($c->campaign_id);
		$campaign = $m -> getItem( true, false );
    ?>
    <form action="<?php echo JRoute::_('index.php?option=com_tienda&view=carts' ); ?>" method="post" name="adminForm" enctype="multipart/form-data">
	    <div class="well">
	      <h2>How much would you like to contribute?</h2>
	      <div class="input-prepend input-append">
		      <span class="add-on">$</span>
		      <input name="price"  style="text-align: right;" value="<?php echo @TiendaHelperProduct::currency($item->product_price, '', array("num_decimals" => 0, "pre" => "")); ?>" type="text" size="28" maxlength="250" />
		      <span class="add-on">.00</span>
	      </div>
	      <div class="fundButton">
			<input type="button" class="btn btn-large btn-funding" id="btnContinueFund" value="Continue" />	
	      </div>
	      <div class="clearfix"></div>
	      <div id="msg"></div>
	      <div class="clearfix"></div>
	 	</div>
    </form>
 	


    <div class="well">
      <h2>Selected level</h2>
      <?php
      	// find currently selected level
      	$act_level = null;
		foreach ($campaign->levels as $level) {
			if( $level->product_id == $item->product_id)
			{
				$act_level = $level;
				break;
			}
		}
		?>
      <div>
        <div class="pull-left">
        <h5><?php  echo TiendaHelperProduct::currency($act_level -> price, '', $options); ?> Level</h5>
        <?php // <div class="backers">4 Backers</div> ?>
        </div>
         <div class="clearfix"></div>
        <div class="clearfix  level_description_short"><?php echo $act_level -> product_description_short; ?></div>
      </div>
 	</div> 	

    <div class="well">
      <h2>Funding levels</h2>
    <div class="clear wrap"></div>
    <div id="campaignLevels" class=" clear right-block full">
        <?php if(count(@$campaign->levels)) : ?>
      <ul><?php foreach ($campaign->levels as $level) :
      		if( (int)($level -> price * 100) == 100 ) continue;      	
      	?> 
        
        <li class="clearfix">
          <div>
            <div class="pull-left">
            <h5><?php  echo TiendaHelperProduct::currency($level -> price, '', $options); ?> Level</h5>
            <?php // <div class="backers">4 Backers</div> ?>
            </div>
            <div class="product_buy pull-right" id="product_buy_<?php echo $level -> product_id; ?>" data-lb="1">
            <?php echo TiendaHelperProduct::getCartButton($level -> product_id, 'campaign_buy'); ?>
			</div> 
                <div class="clearfix"></div>
            <div class="clearfix  level_description_short"><h5><?php echo $level -> product_description_short; ?></h5></div>
           	<div class="clearfix  level_description"><?php echo $level -> product_description; ?></div>
            
            
          </div>
        </li>
        
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
 	</div> 	
    <?php } else { ?>
    <p><?php echo JText::_('COM_TIENDA_NO_ITEMS_IN_YOUR_CART'); ?></p>
    <?php } ?>


</div>

 <div class="well"> By clicking 'Continue,' You agree to the iCrowdFund Terms and

Conditions.  As a supporter, you are contributing or donating to a 

project and not making a purchase and any rewards are managed by 

the creator of the project and cannot be guaranteed by iCrowdFund.</div>
