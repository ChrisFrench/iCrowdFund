<?php defined('_JEXEC') or die('Restricted access');

//JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
 JHTML::_('script', 'jquery.limit.js', 'media/com_tienda/js/');
  JHTML::_('script', 'add_campaign.js', 'media/com_tienda/js/');
 /*$state = @$this->state;
 $items = @$this->items;
 */
$form = @$this -> form;
$row = @$this -> row;
$active = @$this -> active;
 $options = array();
$options['num_decimals'] = 0;
?>
<?php  if(empty($row->campaign_id)) {
    $title = 'Create Project';
  } else {
    $title = 'Project Details';
  }?>
 <h1 class="indent-60 lubefont"><?php echo $title; ?></h1>
 <div class="indent-60">
 <div class="tabbable">
<ul class="nav nav-pills">
    <li class="first"><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=edit&id='.$row->campaign_id);  ?>">Project Details</a></li>
    <li class="active"><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=addlevels&id='.$row->campaign_id);  ?>">Funding Levels</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=wepay&id='.$row->campaign_id);  ?>">WePay Account</a></li>
    <li class="last"><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=checks&id='.$row->campaign_id);  ?>">Checklist </a></li>
  </ul>
</div>
<br>
    <div class="progress">
    <div class="bar bar-success" style="width: 45%;"></div>
    <div class="bar bar-warning" style="width: 55%;"></div>
    </div>
    <br>

<div class="well" >
	<div class="media">
    <img class="media-object pull-left" src="<?php echo TiendaHelperCampaign::getImage($row, 'thumb', '', true); ?>"  alt="">
   
    <div class="media-body">
    <h4 class="media-heading pull-left"><?php echo $row -> campaign_name; ?></h4>
    
    <div class="clearfix"></div>
    <!-- Nested media object -->
    <div class="media">
  		<?php echo $row -> campaign_shortdescription; ?>
    </div>
    </div>
    </div>
</div>
<div class=" well well-small">
	<form class="form-horizontal" action="<?php echo JRoute::_($this ->action); ?>" id="addLevel" name="addLevel"  method="post" enctype="multipart/form-data" >
       <form>
    <fieldset>

    <legend>Add Funding Levels</legend>
    <div class="pull-left" style="margin-left: 0px; margin-right: 0px; width: 276px; margin-right:10px;">
    	<label>Amount</label>
    <div class="input-prepend input-append">
	<span class="add-on">$</span>
      <input type="text" name="product_price" id="product_price" <?php if ( @$active->price) :?>  value="<?php echo @$active->price; ?>" disabled=disabled <?php endif; ?> size="25" maxlength="8" />
      <span class="add-on">.00</span>
	</div>
    
	<label>Title</label>
	<textarea id="product_description_short" name="product_description_short" style="margin-left: 0px; margin-right: 0px; width: 276px; height:233px;"><?php echo @$active->product_description_short; ?></textarea>
	<div id="counter"></div>
	</div>
	 <div class="pull-left" style="margin-left: 0px; margin-right: 0px; width: 476px;">
	<label>Description</label>
	<textarea id="product_description" name="product_description" style="margin-left: 0px; margin-right: 0px; width: 476px; height:290px;"><?php echo @$active->product_description; ?></textarea>
	
    <div id="longcounter"></div>
    <div id="longdesc_mess">If there are any add'l sales tax or shipping charges, mention that here.</div>    
    <input type="hidden" name="product_name" id="product_name" value="<?php echo @$active->product_name; ?>" size="48" maxlength="250" />
    <input type="hidden" name="product_model" id="product_model" value="<?php echo @$active->product_model; ?>" size="48" maxlength="250" />
    <input type="hidden" name="product_sku" id="product_sku" value="<?php echo @$active->product_sku; ?>" size="48" maxlength="250" />
    <?php if(@$active->product_id) : ?>
    <input type="hidden" name="product_id" id="product_id" value="<?php echo @$active->product_id; ?>" size="48" maxlength="250" />
    <input type="hidden" name="id" id="id" value="<?php echo @$active->product_id; ?>" size="48" maxlength="250" />
    <?php endif; ?>
    <!-- campaign-info-->
    <input type="hidden" name="campaign_name" value="<?php echo $row -> campaign_name; ?>" />
    <input type="hidden" name="campaign_id" value="<?php echo $row -> campaign_id; ?>" /> 
    
    </div>
    <br clear="both" class="clearfix" /><br />
    <div class="pull-left" style="margin-left: 0px; margin-right: 0px; width: 226px;">
    <div class="indent-10">	
    <label>Options</label>
  
  	 QTY Available: <input type="text" name="product_quantity" value="<?php echo @$active->product_quantity ?>" size="5" placeholder="if unlimited, leave blank" class="input-medium" maxlength="11" />
    
    <label><strong>This level requires shipping</strong></label>
    <label class="radio inline"><input class="input"  type="radio" <?php if (empty($active->product_ships)) { echo "checked='checked'"; } ?> value="0" name="product_ships" id="product_ships0"/>NO</label>
	<label class="radio inline indent-10"><input class="input"  type="radio" <?php if (!empty($active->product_ships)) { echo "checked='checked'"; } ?> value="1" name="product_ships" id="product_ships1"/>YES</label>
	<div class="clearfix"><br></div>
	<button  type="submit" class="btn btn-funding " data-loading-text="Loading..."><?php if(@$active->product_id) : ?>Edit Level <?php else : ?>Add Level <?php endif; ?></button>
	</div>

    </div>
    </form>

</div>
<div class="pull-left"> When you are done adding levels, click Next.</div>
<a class="btn btn-primary pull-right" href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=wepay&id='.$row->campaign_id); ?>">Next</a>
<div class="clearfix"></div>
<br>
<h2 class="pull-left">Funding Levels</h2>
<div class="clearfix"></div>
<?php $i = 1 ; foreach(@$this -> products as $level) : ?>

<div class=" well well-small">
	<div><div id="ID" class="pull-left indent-10"><?php echo $i; ?></div>
		<div class="price pull-left indent-10"><h5><?php  echo TiendaHelperProduct::currency($level->price, '', $options); ?> -  </h5></div>
		<div class="price-desc  indent-20"><h5 class=" indent-20"> <?php  echo $level->product_description_short; ?></h5><div class="productdesc" style="width:90%;"><?php  echo $level->product_description; ?></div></div>
       
        <div class="edit_level pull-right" id="editlevel<?php echo $level->product_id; ?>">
                              <a class="btn btn-inverse" href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=addlevels&id='.$row -> campaign_id.'&level='.$level->product_id.'#addLevel'); ?>">Edit</a>
                             </div>
                              <br clear="both">
		</div>
</div>


<?php $i++; endforeach; ?>
	</div>

