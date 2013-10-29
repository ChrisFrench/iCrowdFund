<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
$state = @$this -> state;
$items = @$this -> rows;
?>

<div>
	<h1>Campaigns I've Funded</h1>
	
	<div>
		<?php foreach($items  as $item) : ?>
			<div class="well well-small">
			<div class="media">
  <a class="pull-left" href="<?php echo JRoute::_(  $item -> view_link ); ?>">
    <img class="media-object" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumbs', '', true); ?>">
  </a>
  <div class="media-body">
    <h2 class="media-heading pull-left"><?php echo  $item->campaign_name; ?></h2> <a href="<?php echo JRoute::_(  $item -> view_link ); ?>" class="pull-right btn btn-primary">View</a>
    <div class="clearfix"></div>
    <!-- Nested media object -->
    <div class="media">
    <?php echo $item -> campaign_shortdescription; ?>
    </div>
  </div>
</div>
</div>
		<?php endforeach; ?>	
	</div>

</div>