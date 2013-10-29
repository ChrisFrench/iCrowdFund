<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
$state = @$this -> state;
$items = @$this -> items['active'];
?>
Default
<div id="wepay" class="campaigns default span8">
<ul class="thumbnails center moduleBrowse">
  
	<?php foreach($items as $item) { ?>
		
		<li class="span3">
    <div class="thumbnail">
    	<div class="imageWrapper">
      <img class="" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumb', '', true); ?>" width="190px" height="80px" alt="">
      </div>
      <h3><?php echo $item -> campaign_name; ?></h3>
      <p><?php echo $item -> campaign_shortdescription; ?></p>
      <p><a href="<?php echo JRoute::_($item -> view_link . "&Itemid=" . $item -> itemid); ?>" class="btn btn-funding">Read More</a></p>
      <?php  echo TiendaHelperCampaign::displayCampaignStats($item, 'campaignStats', 'stats row', TRUE); ?>
    </div>
  </li>
	<?php  } ?>
	
</ul>
</div>