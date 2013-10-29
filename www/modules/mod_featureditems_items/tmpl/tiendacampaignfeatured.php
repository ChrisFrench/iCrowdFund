<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
// if DSC is not loaded all is lost anyway
if (!defined('_DSC')) { return; }

// Check the registry to see if our Tienda class has been overridden
if ( !class_exists('Tienda') ) 
    JLoader::register( "Tienda", JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" );

Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
DSCTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
DSCTable::addIncludePath( JPATH_SITE.'/components/com_tienda/models' );
$item = $items[0];
$table = JModel::getInstance('Campaigns','TiendaModel');
$table = $table->getItem($item->fk_id);

?>
<div id="featured-wrapper" class="page center">
	<div id="featured-tienda-campaign" class="innerpage center ">
		<div id="campaign" class="pull-left">
			<div class="imageWrapper wrap clear">
				<?php if(@$table->video) : ?>
      	<?php  $option = array('width'=> '456', 'hieght' => '250', 'options' => 'allowfullscreen ');	  echo TiendaHelperCampaign::video(@$table->video,$option); ?>
      	<?php else : ?>
      		<a href="<?php echo JRoute::_($table->view_link); ?>"><img src="<?php echo $item->image_src; ?>"></a>	
      	<?php endif; ?>
				
				</div>
			<div class="caption captionbg white wrap clear"><a class="description" href="<?php echo JRoute::_($table->view_link); ?>"><?php echo $item->item_description; ?></a>
				
			</div>
			<div class=" full activebg white clear" > <?php  echo TiendaHelperCampaign::displayCampaignStats($table,'FeaturedCampaign'); ?> </div>
		</div>
		<div id="sectors" class="pull-right"><h5>Sectors:</h5><?php   echo TiendaHelperCampaign::getSectorsList(@$table->category_id); ?></div>
		<div id="StartProject" class="pull-right btn btn-funding "><a class="white" href="/start">Start a Project</a></div>
	</div>
</div>
