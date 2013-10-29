<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
$state = @$this -> state;
$items = @$this -> items['active'];
?>


<div id="tienda" class="campaigns default span8">
<ul class="thumbnails center moduleBrowse">
  
	<?php 
if(count($items)) {
  foreach($items as $item) { ?>
		<li class="span3">
    <div class="thumbnail">
      <div class="imageWrapper"><a href="<?php echo JRoute::_( $item->view_link."&Itemid=".$item->itemid); ?>" class="">
      <img class="" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumb','',true); ?>"  alt="">
    </a>
      </div>
      <h3><a href="<?php echo JRoute::_( $item->view_link."&Itemid=".$item->itemid); ?>"><?php echo  TiendaHelperCampaign::character_limiter($item->campaign_name,'20'); ?></a></h3>
      <p class="shortDesc"><?php echo $item->campaign_shortdescription; ?></p>
      <p><a href="<?php echo JRoute::_( $item->view_link."&Itemid=".$item->itemid); ?>" class=" thumbReadmore">Read More</a></p>
      <?php  echo TiendaHelperCampaign::displayCampaignStats($item, 'campaignStats', 'stats row',  TRUE); ?>
    </div>
  </li>
	<?php  } }?>
	
</ul>
</div>