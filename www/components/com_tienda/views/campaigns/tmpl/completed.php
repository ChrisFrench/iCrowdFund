<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
$state = @$this -> state;
$items = @$this -> rows;

$rows = array_chunk($items, 3);


?>
<style>
.table { display: table; }
 .table-cell { display: table-cell; width: 220px; padding: 10px; }
 .table-row { display: table-row; margin-bottom: 10px;}
</style>

<div id="tienda" class="campaigns default span10">
  <h1>Completed Projects</h1>


<div class="thumbnails table center moduleBrowse">
  <?php foreach ($rows as $items) : ?>
  <div class="table-row">
  <?php 

  foreach($items as $item) : ?>
    <div class=" table-cell">
    <div class="thumbnail">
      <div class="imageWrapper"><a href="<?php echo JRoute::_( $item->view_link."&Itemid=".$item->itemid); ?>" class="">
      <img class="" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumb','',true); ?>"  alt="">
    </a>
      </div>
      <h3><a href="<?php echo JRoute::_( $item->view_link."&Itemid=".$item->itemid); ?>"><?php echo  TiendaHelperCampaign::character_limiter($item->campaign_name,'20'); ?></a></h3>
      <div class="shortDesc"><?php echo $item->campaign_shortdescription; ?></div>
      <p><a href="<?php echo JRoute::_( $item->view_link."&Itemid=".$item->itemid); ?>" class=" thumbReadmore">Read More</a></p>
      <?php  echo TiendaHelperCampaign::displayCampaignStats($item, 'campaignStats', 'stats row',  TRUE); ?>
    </div>
  </div>
  <?php   endforeach; ?>
  </div>
  <?php  endforeach; ?>
</div>




