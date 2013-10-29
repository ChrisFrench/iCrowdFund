<?php defined('_JEXEC') or die('Restricted access');

$item = $this -> row;
$options = array();
$options['num_decimals'] = 0;

 ?>
<h1 class="indent-60">Stats</h1>
<div class="indent-60" style="margin-top:15px;">

	
	<div>
		
			<div class="well well-small">
			<div class="media">
  <a class="pull-left" href="<?php echo JRoute::_($item -> manage); ?>">
    <img class="media-object" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumbs', '', true); ?>">
  </a>
  <div class="media-body">
    <h2 class="media-heading pull-left"><?php echo $item -> campaign_name; ?></h2><div class="control-group"> <a href="<?php echo JRoute::_($item -> manage); ?>" class="pull-right btn btn-inverse">Edit</a></div>
    <div class="clearfix"></div>
    <!-- Nested media object -->
    <div class="media">
    <?php echo $item -> campaign_shortdescription; ?>
    </div>
  </div>
</div>
</div>
	<div class="well well-small">
	<h2 class="pull-left">Levels</h2><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=addlevels&id='.$item->campaign_id); ?>" class="pull-right btn btn-primary">Add Level</a>
	<div class="clearfix"></div>
	<div id="campaignLevels" class=" ">
	<div>
		<table class="table table-hover table-striped" width="100%">
			<?php foreach ($item->levels as $level) :?> 
			<tr>
				<td><?php  echo TiendaHelperProduct::currency($level->price, '', $options); ?> Level</td>
				<td><?php echo $level->product_description_short; ?></td>
				<td><div class="edit_level " id="editlevel<?php echo $level->product_id; ?>">
          				      <a class="btn btn-inverse" href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=addlevels&id='.$item->campaign_id.'&level='.$level->product_id.'#addLevel'); ?>">Edit</a>
           					 </div> </td>
			</tr>
			<?php endforeach; ?>
		</table>
		
		
		
	</div>
</div> 
	</div>
	<div class="well well-small">
		<?php $return =  base64_encode(JFactory::getURI() -> toString()); ?>
	<h2 class="pull-left">Updates</h2><form method="post" action="<?php echo JUri::base(); ?>update"> <input name="catid" value="<?php echo $item->content_cat_id; ?>" type="hidden" /> <input name="return" value="<?php echo $return ?>" type="hidden" /> <button type="submit" name="submit"  class="pull-right btn btn-primary">Make Update</button></form>
	 <div class="clearfix"></div>
		<div >
			<ul><?php foreach (@$item->updates as $update) : ?> 
				<li>
      			<div>
      				<h3 class="pull-left"><?php echo $update -> title; ?></h3> 

      					<?php if ( !class_exists('Messagebottle') )  {   JLoader::register( "Messagebottle", JPATH_ADMINISTRATOR."/components/com_messagebottle/defines.php" );}


  $emailHelper =   Messagebottle::getClass('MBHelper', 'helpers.emails');

  		echo $emailHelper->emailButton($update->id, '1', $update->title, $item ->campaign_id, array('Email Update to Followers', ' Remove From Email Queue')   );
     ?>



      				  <a href="<?php echo JUri::base(); ?>update?task=article.edit&a_id=<?php echo $update->id; ?>&return=<?php echo $return ?>" class="btn btn-inverse pull-right">edit</a>
      					

      				<div class="clearfix"></div>
      				<div id=" " class="">
      					<?php echo $update -> introtext; ?>
      				</div>
      			</div>
      		</li>
			<?php endforeach; ?></ul>
		</div>
		
	</div>
	<div class="well well-small">
	<h2><?php echo JText::_('COM_TIENDA_CROWDFUNDING_BACKERS'); ?></h2>
	<ul class="thumbnails">
		<?php foreach (@$item->backers as $backer)  : ?>
 			
 			
  <li class="span2">
    <div class="thumbnail">
      <img src="<?php echo TiendaHelperCampaign::gravatar($backer, '125'); ?>" alt="">
      <h3><?php echo $backer -> name; ?> </h3>
    
    </div>
<?php endforeach; ?>
</ul>
      
	</div>	
	
	<?php 
		$this->is_indirect = true;
		echo $this->loadTemplate( 'orders' );
	?>
	</div>

</div>





