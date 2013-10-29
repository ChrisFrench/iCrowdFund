<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
JHTML::_('script', 'tienda_inventory_check.js', 'media/com_tienda/js/');

$item = @$this -> row;
$options=array(); $options['num_decimals'] = 0;
?>

<div id="campaignWrapper" class="full wrap campaignPage">
	<div id="campaignHeader" class="full greyTopHeader"><h1 class="indent-60"><?php echo $item -> campaign_name; ?></h1></div>
	<div id="campaignMainColumn" class="pull-left indent-60">
		


<div id="campaignLevels" class="well well-small">
	<div>
		<ul><?php foreach ($item->levels as $level) { ?> 
				<li class="clearfix">
					<div>
						<div class="pull-left">
						<h5>$<?php echo   substr($level->price,0,2); ?> Level</h5>
						<?php //<div class="backers">4 Backers</div> ?>
						</div>
						<div class="product_buy pull-right" id="product_buy_<?php echo $level->product_id; ?>">
          				      <?php echo TiendaHelperProduct::getCartButton( $level->product_id ,'campaign_buy'); ?>
           					 </div> 
         				<div class="clearfix"></div>
						<div class="clearfix  level_description_short"><?php echo $level->product_description; ?></div>

						
						
					</div>
				</li>
				
				<?php }?>
			</ul>
	</div>
</div> 



	</div>
	
	<div id="campaignLeftColumn" class="pull-left indent-10 well well-small">
		
		<img class="img-rounded" src="<?php echo TiendaHelperCampaign::getImage(@$item, 'thumb', '', TRUE); ?>"> 
		<div  class="campaignDescription rightside"><?php echo @$item -> campaign_description; ?></div>
		<?php
            $modules = JModuleHelper::getModules("campaigns_fund_right" );
            $document   = JFactory::getDocument();
            $renderer   = $document->loadRenderer('module');
            $attribs    = array();
            $attribs['style'] = '';
            foreach ( @$modules as $mod ) 
            {
                echo $renderer->render($mod, $attribs);
            }
            ?>
	</div>
</div>

<div class="clear wrap center"></div>
