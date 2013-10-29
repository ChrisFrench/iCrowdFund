<?php defined('_JEXEC') or die('Restricted access');
// if DSC is not loaded all is lost anyway
if (!defined('_DSC')) {
	return;
}

// Check the registry to see if our Tienda class has been overridden
if (!class_exists('Tienda'))
	JLoader::register("Tienda", JPATH_ADMINISTRATOR . "/components/com_tienda/defines.php");

Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tienda/tables');
JModel::addIncludePath(JPATH_SITE . '/components/com_tienda/models');
Tienda::load('TiendaModelCampaigns', 'helpers.campaign');

?>

<div id="staffpicks-wrapper" class="page center">
	<div id="staffpicks-tienda-campaigns" class="innerpage center ">
		<ul class="thumbnails center staffPicks">
		<?php foreach($items as $item) { 
			$model = JModel::getInstance('Campaigns','TiendaModel'); ; $campaign =  $model->getItem($item->fk_id); ?>
		<li class="span3">
  		  <div class="thumbnail">
  		  	<div class="imageWrapper">
     		 <img src="<?php echo $item -> image_src; ?>" alt="">
     		 </div>
    		 <h3><?php echo $item -> item_short_title; ?></h3>
     		 <div class="shortDesc"><?php echo $item -> item_description; ?></div>
      		 <p><a href="<?php echo $campaign -> view_link; ?>" class="thumbReadmore">Read More</a></p>
        <?php  echo TiendaHelperCampaign::displayCampaignStats($item, 'campaignStats', 'stats row',  TRUE); ?>
        </div>
       </li>

	<?php  } ?>
</ul>
</div>
</div>