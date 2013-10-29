<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('script', 'jquery.limit.js', 'media/com_tienda/js/');
JHTML::_('script', 'add_campaign.js', 'media/com_tienda/js/');
JHTML::_('script', 'jquery-ui.js', 'http://code.jquery.com/ui/1.10.1/'); 
JHTML::_('stylesheet', 'jquery-ui.css', 'http://code.jquery.com/ui/1.10.1/themes/base/');
/*JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
 JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
 $state = @$this->state;
 $items = @$this->items;
 */
$form = @$this -> form;
$row = @$this -> row;

?>


<form action="/<?php echo  @$form['action'] ;?>" id="addCampiagn" name="addCampiagn"  method="post" enctype="multipart/form-data" >
  <?php  if(empty($row->campaign_id)) {
    $title = 'Create Project';
  } else {
    $title = 'Project Details';
  }?>
 <h1 class="indent-60 lubefont"><?php echo $title; ?></h1>
<div class="indent-60">
  <div class=" tabbable"> <!-- Only required for left/right tabs -->

     <?php  if(empty($row->campaign_id)) : ?>
   <ul class="nav nav-pills">
    <li class="active first"><a>Project Details</a></li>
    <li class="disabled"><a>Funding Levels</a></li>
    <li class="disabled"><a>WePay Account</a></li>
    <li class="disabled last"><a>Checklist</a></li>
  </ul>
  <?php else :?>
    <ul class="nav nav-pills">
    <li class="active first"><a >Project Details</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=addlevels&id='.$row->campaign_id);  ?>" >Funding Levels</a></li>
    <li class=""><a >WePay Account </a></li>
    <li class=" last"><a >Checklist</a></li>
  </ul>
   <?php endif ;?>
  
</div>

<br>
    <div class="progress">
    <div class="bar bar-success" style="width: 15%;"></div>
    <div class="bar bar-warning" style="width: 85%;"></div>
    </div><br>

  <fieldset>
 
    <div class="row">
      <div class="span5" id="createformleft">
        <div class="formrow">
        <label for="campaign_name"><?php echo JText::_('COM_TIENDA_CAMPAIGN_TITLE'.$row->type); ?></label>
    <input type="text" name="campaign_name" id="campaign_name" size="48" maxlength="250" value="<?php echo @$row -> campaign_name; ?>" class="marginbottom20" />
  </div>

  <div class="formrow">
     
      <?php
     
      //TODO not hard code this and figure out a better way. maybe two different forms?
       if($row->type == 2) : ?>
      <label for="category_id" >Sector: <span class="selected">Charity</span></label>
      <input name="category_id" id="category_id" value="13" type="hidden" size="28" maxlength="250" />
     
    <?php endif; ?>
     <?php if($row->type == 3) : ?>
      <label for="category_id" >Sector: <span class="selected">Important Cause</span></label>
      <input name="category_id" id="category_id" value="14" type="hidden" size="28" maxlength="250" />
    <?php endif; ?>
    <?php if($row->type != 3 && $row->type != 2) : ?>
    <label for="category_id" >Sector</label>
     <?php  echo TiendaHelperCampaign::getSectorsSelectListForm(@$row, 'category_id'); ?>
   <?php endif; ?>
</div>
  <div class="formrow">
      <label for="campaign_goal" >Funding Goal:</label>
        <div class="input-prepend input-append">
        <span class="add-on">$</span>
            <input name="campaign_goal" id="campaign_goal" value="<?php echo @$row -> campaign_goal; ?>" type="text" size="28" maxlength="250" />
            <span class="add-on">.00</span>
    </div>
  </div>
  <div class="formrow">
     <label for="campaign_fundingtype" >Funding Type:</label>
     <a href="#" id="fundingTypeTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="All or Nothing, if  project doesn't meet its goal  donations are not charged to the supporters.  Standard: Standard projects are funded as they go and will donate to regardless if final goal is met." data-original-title="Funding Options"><i class="icon-white icon-info-sign"></i></a>
     <?php  echo TiendaHelperCampaign::campaignFundingType(@$row -> fundingtype, 'fundingtype', $attribs = array('class' => 'inputbox'), $idtag = null, $allowAny = false, $title = 'COM_TIENDA_SELECT_RANGE' , $row->type ); ?>
  </div>
  <div class="formrow">
    <label>Project Launch Date:</label>     
  <div class="input-append">
  
  <input type="text" id="fundingstart_date" name="fundingstart_date" value="<?php echo @$row -> fundingstart_date; ?>" />
  <span class="add-on"><label for="fundingstart_date"><i class="icon-calendar"></i></label></span>

  <script>
  jQuery(function() {
    jQuery( "#fundingstart_date" ).datepicker({ minDate: 0, maxDate: "+3M +10D" });
  });
  </script>
   </div>
  </div>
  <div class="formrow">   
<label>Project Length</label>
<div id="daysRadios" class="clearfix">
  <label class="radio pull-left" style="width: 65px;">
  <input type="radio" name="days" id="days30" value="30" checked>
  30 Days
  </label>
  <label class="radio pull-left" style="width: 65px;">
  <input type="radio" name="days" id="days60" value="60" >
  60 Days
  </label>
  <label class="radio pull-left" style="width: 65px;">
  <input type="radio" name="days" id="days90" value="90" >
  90 Days
  </label>
  <label class="radio pull-left" style="width: 65px;">
  <input type="radio" name="days" id="days120" value="120" >
  120 Days
  </label>
<?php if($this->type == 2) : ?>
 <label class="radio pull-left" style="width: 65px;">
  <input type="radio" name="days" id="oneyear" value="360" >
 One Year
  </label>
 <label class="radio pull-left" style="width: 65px;">
  <input type="radio" name="days" id="unlimited" value="0" >
 Unlimited
  </label>
 <?php endif; ?> 

</div>
  </div>
  
    <?php if(@$row -> campaign_full_image) : ?> 
    <div class="formrow">
    <label for="campaign_full_image"><?php echo JText::_('Campaign Image'); ?>:</label> 
  <ul class="thumbnails"> <li><?php echo TiendaHelperCampaign::getImage($row, 'thumb'); ?></li>
    </ul> 
    <input type="text" disabled="disabled" name="campaign_full_image" id="campaign_full_image" size="48" maxlength="250" value="<?php echo @$row -> campaign_full_image; ?>" />
  </div>
    <?php endif ; ?>
    <div class="formrow">
  <label for="campaign_full_image_new">Upload New Image:</label>
        <a href="#" id="imageTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="Images will be resized to 560 pixels wide, 420 Pixels tall.  Supported Types are .jpg, .png, .gif" data-original-title="Project Image"><i class="icon-white icon-info-sign"></i></a>
  <input name="campaign_full_image_new" type="file" class="input-medium" />
</div>
<div class="formrow">
  <label for="Video" >Video Link</label>
     <a href="#" id="videoTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="Paste in a link to your video  hosted on YouTube or Vimeo. <br> If you add a video, your image will not appear" data-original-title="Video"><i class="icon-white icon-info-sign"></i></a><input type="text" name="video" id="video" size="48" maxlength="1050" value="<?php echo @$row -> video; ?>" />
   </div>

     <?php if($this->type == 2 || $this->type == 3 ) : ?>
    
    <?php endif; ?>
 </div>
  
    


    <div class="span5"><label for="campaign_description"><?php echo JText::_('Short Description'); ?>:</label>
    <textarea rows="5" style="width:500px;" id="campaign_shortdescription" name="campaign_shortdescription"><?php echo @$row->campaign_shortdescription ?></textarea>
    <div id="counter"></div>
    <label for="campaign_description"><?php echo JText::_('Long Description'); ?>:  <a href="#" id="longTIP" class="btn btn-info" rel="popover" data-placement="top" data-content="You can insert images, and text. This will show on the project detail page" data-original-title="Long Description"><i class="icon-white icon-info-sign"></i></a>
   </label>  
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
    <input id="user_id_id" type="hidden" value="<?php echo @$row->user_id; ?>" name="user_id">
    <input id="type" type="hidden" value="<?php echo @$this->type; ?>" name="type">
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="step2" value="addlevels" />
    </div>
    </div>
    
     <?php  if(empty($row->campaign_id)) :?>
   <button type="submit" class="btn btn-primary clearfix">Create Project, Go to add Levels</button>
  <?php else :?>
   <button type="submit" class="btn btn-primary clearfix pull-right">Next</button>
  <?php endif ;?>
              
    
  </fieldset>
</div>
</form>

<script>
  jQuery('#imageTIP').popover();
  jQuery('#videoTIP').popover();
  jQuery('#fundingTypeTIP').popover();
  jQuery('#longTIP').popover();
</script>
