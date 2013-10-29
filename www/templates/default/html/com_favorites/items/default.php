<?php
if ( !class_exists('Tienda') ) 
    JLoader::register( "Tienda", JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" );
Tienda::load( "TiendaHelperProduct", 'helpers.product' );
Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
Favorites::load( 'FavoritesHelperFavorites', 'helpers.favorites' );
$helper = new FavoritesHelperFavorites();

$list = $this -> items;






?>

<div id="Favorites">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1><?php echo $this -> escape($this -> params -> get('page_heading')); ?>
	</h1>
<?php endif; ?>
<br><br><br>
<?php if (!empty($list)) : ?>
<?php foreach($list  as $item) :  $item = $item->project ;  ?>
	<?php if(@$item->view_link && @$item -> campaign_name): ?>
			<div class="well well-small">
			<div class="media">
  <a class="pull-left" href="<?php echo JRoute::_($item -> view_link); ?>">
    <img class="media-object" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumbs', '', true); ?>">
  </a>
  <div class="media-body">
    <h2 class="media-heading pull-left"><a href="<?php echo JRoute::_($item -> view_link); ?>"><?php echo $item -> campaign_name; ?></a></h2>
    <div class="clearfix"></div>
    <!-- Nested media object -->
    <div class="media">
    	<div class="description">
    <?php echo $item -> campaign_shortdescription; ?>
    </div>
    <div>
    	 <?php  echo TiendaHelperCampaign::displayCampaignStatsFull($item); ?>
    </div>
    </div>
  </div>
</div>
</div>
<?php endif; ?>
		<?php endforeach; ?>
<?php else : ?>
No Followed Projects yet.
<?php endif ?>


</div>


