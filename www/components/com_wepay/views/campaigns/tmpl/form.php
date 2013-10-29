<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('script', 'jquery.limit.js', 'media/com_tienda/js/');
  JHTML::_('script', 'add_campaign.js', 'media/com_tienda/js/');
/*JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
 JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
 $state = @$this->state;
 $items = @$this->items;
 */
$form = @$this -> form;
$row = @$this -> row;

?>


<form action="<?php echo  @$form['action'] ;?>" id="addCampiagn" name="addCampiagn"  method="post" enctype="multipart/form-data" >
  <?php  if(empty($row->campaign_id)) {
    $title = 'Create Project';
  } else {
    $title = 'Update Project';
  }?>
 <h1><?php echo $title; ?></h1>

  <div class="tabbable"> <!-- Only required for left/right tabs -->

     <?php  if(empty($row->campaign_id)) : ?>
   <ul class="nav nav-pills">
    <li class="active"><a >Project Details</a></li>
    <li class="disabled"><a >Funding Levels  </a></li>
    <li class="disabled"><a >WePay Account </a></li>
    <li class="disabled"><a >Checks </a></li>
  </ul>
  <?php else :?>
    <ul class="nav nav-pills">
    <li class="active"><a >Project Details</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=addlevels&id='.$row->campaign_id);  ?>" >Funding Levels  </a></li>
    <li class="disabled"><a >WePay Account </a></li>
    <li class="disabled"><a >Checks </a></li>
  </ul>
   <?php endif ;?>
  
</div><br>
    <div class="progress">
    <div class="bar bar-success" style="width: 15%;"></div>
    <div class="bar bar-warning" style="width: 85%;"></div>
    </div><br>

  <fieldset>
 
  	<div class="row">
  		<div class="span5"><label for="campaign_name" >Name of your Project</label>
    <input type="text" name="campaign_name" id="campaign_name" size="48" maxlength="250" value="<?php echo @$row -> campaign_name; ?>" />
     <label for="category_id" >Sector</label>
     <?php  echo TiendaHelperCampaign::getSectorsSelectList(@$row -> category_id, 'category_id'); ?>
      <label for="campaign_goal" >Funding Goal</label>
        <div class="input-prepend input-append">
				<span class="add-on">$</span>
   				  <input name="campaign_goal" id="campaign_goal" value="<?php echo @$row -> campaign_goal; ?>" type="text" size="28" maxlength="250" />
   	   			<span class="add-on">.00</span>
		</div>
	<label>Start Funding Date:</label>
	<div class="input-append">
	<?php echo JHTML::calendar(@$row -> fundingstart_date, "fundingstart_date", "fundingstart_date", '%Y-%m-%d %H:%M:%S', array('size' => 25)); ?>
    </div>
	<label>Stop Funding Date:</label>
	<div class="input-append">
    <?php echo JHTML::calendar(@$row -> fundingend_date, "fundingend_date", "fundingend_date", '%Y-%m-%d %H:%M:%S', array('size' => 25)); ?>
    </div>
    <?php if(@$row -> campaign_full_image) : ?> 
    <label for="campaign_full_image"><?php echo JText::_('Campaign Image'); ?>:</label> 
	<ul class="thumbnails"> <li><?php echo TiendaHelperCampaign::getImage($row, 'thumb'); ?></li>
    </ul>	
    <input type="text" disabled="disabled" name="campaign_full_image" id="campaign_full_image" size="48" maxlength="250" value="<?php echo @$row -> campaign_full_image; ?>" />
    <?php endif ; ?>
	<label for="campaign_full_image_new">Upload New Image:</label>
        <a href="#" id="imageTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="Images will be resized to 560 pixels wide, 420 Pixels tall.  Supported Types are .jpg, .png, .gif" data-original-title="Project Image"><i class="icon-white icon-info-sign"></i></a>
	<input name="campaign_full_image_new" type="file" class="input-medium" />
	<label for="Video" >Video Link</label>
     <a href="#" id="videoTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="Youtube or Vimeo, http://www.youtube.com/watch?v=000000000, http://vimeo.com/000000000" data-original-title="Video"><i class="icon-white icon-video"></i></a><input type="text" name="video" id="video" size="48" maxlength="1050" value="<?php echo @$row -> video; ?>" />
    </div>
  	<div class="span5"><label for="campaign_description"><?php echo JText::_('Short Description'); ?>:</label>
    <textarea rows="5" style="width:500px;" id="campaign_shortdescription" name="campaign_shortdescription"><?php echo @$row->campaign_shortdescription ?></textarea>
    <div id="counter"></div>
    <label for="campaign_description"><?php echo JText::_('COM_TIENDA_DESCRIPTION'); ?>:</label>
    <?php
    $editor = JFactory::getEditor();
$params = array( 'smilies'=> '0' ,
                 'style'  => '1' ,  
                 'layer'  => '0' , 
                 'table'  => '0' ,
                 'clear_entities'=>'0'
                 );
echo $editor->display( 'campaign_description', @$row->campaign_description, '500', '500', '20', '20', false, null, null, null, $params );		
	
	
	?>
    <div id="longcounter"></div>
    <input type="hidden" name="validate" value="<?php echo @$form['validate'] ;?>" />			
    <input type="hidden" name="id" value="<?php echo @$row->campaign_id; ?>" />
    <input id="user_id_id" type="hidden" value="<?php echo @$row->user_id?>" name="user_id">
      <input type="hidden" name="task" value="save" />
    <input type="hidden" name="step2" value="addlevels" />
    </div>
  	</div>
    
     <?php  if(empty($row->campaign_id)) :?>
   <button type="submit" class="btn clearfix">Create Project, Go to add Levels</button>
  <?php else :?>
   <button type="submit" class="btn clearfix">Save Edits</button>
  <?php endif ;?>
    					
    
  </fieldset>
</form>

<script>
	jQuery('#imageTIP').popover();
	jQuery('#videoTIP').popover();
</script>
