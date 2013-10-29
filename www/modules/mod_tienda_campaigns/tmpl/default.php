<div id="mod-tienda-campaigns" class="campaigns center default full">
	
	<ul class="thumbnails center moduleBrowse">
  
	<?php foreach($items as $item) { ?>
		
		<li class="span3">
    <div class="thumbnail">
    	<div class="imageWrapper"><a href="<?php echo JRoute::_( $item->view_link."&Itemid=".$item->itemid); ?>" class="">
      <img class="" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumb','',true); ?>"  alt="">
    </a>
      </div>
      <h3><a href="<?php echo JRoute::_( $item->view_link."&Itemid=".$item->itemid); ?>" class=""><?php echo  TiendaHelperCampaign::character_limiter($item->campaign_name,'20'); ?></a></h3>
      <div class="shortDesc"><?php echo $item->campaign_shortdescription; ?></div>
      <div><a href="<?php echo JRoute::_( $item->view_link."&Itemid=".$item->itemid); ?>" class=" thumbReadmore">Read More</a></div>
      <?php  echo TiendaHelperCampaign::displayCampaignStats($item, 'campaignStats', 'stats row',  TRUE); ?>
    </div>
  </li>
	<?php  } ?>
	
</ul>

	

</div>