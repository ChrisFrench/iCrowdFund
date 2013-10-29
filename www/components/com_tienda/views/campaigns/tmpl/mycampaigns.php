<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/');
JHTML::_('script', 'tienda.js', 'media/com_tienda/js/');
$state = @$this -> state;
$items = @$this -> rows;

?>








<div>
  <h1>My Projects</h1>
<div class="tabbable">  
  

  
  <ul class="nav nav-pills">
  <li class="active"><a class="pull-left" href="#active"  data-toggle="tab">Active <b class="white label label-inverse "><?php echo count(@$items['active']); ?></b></a></li>
  <li><a class="pull-left" href="#pending"  data-toggle="tab">Pending <b class="white label label-inverse "><?php echo count(@$items['pending']); ?></b></a></li>
  <li><a class="pull-left" href="#completed"  data-toggle="tab">Completed <b class="white label label-inverse "><?php echo count(@$items['completed']); ?></b></a></li>
  
</ul>
 
<div class="tab-content">
  <div class="tab-pane active" id="active">
    <?php if(empty($items['active'])) { echo 'You currently have no active projects.';} ?>
    <?php foreach($items['active']  as $item) : ?>
      <div class="well well-small">
      <div class="media">
  <a class="pull-left" href="<?php echo JRoute::_($item -> view_link); ?>">
    <img class="media-object" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumbs', '', true); ?>">
  </a>
  <div class="media-body">
    <h2 class="media-heading pull-left"><?php echo $item -> campaign_name; ?></h2><div class="control-group"><a href="<?php echo JRoute::_($item -> stats); ?>" class="pull-right btn btn-primary">Manage</a></div>
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
    <?php endforeach; ?>
  </div>
  <div class="tab-pane" id="pending">
    <?php if(empty($items['pending'])) { echo 'You currently have no pending projects.';} ?>
    <?php foreach($items['pending']  as $item) : ?>
      
      <div class="well well-small">
      <div class="media">
  <a class="pull-left" href="<?php echo JRoute::_($item -> manage); ?>">
    <img class="media-object" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumbs', '', true); ?>">
  </a>
  <div class="media-body">
    <h2 class="media-heading pull-left"><?php echo $item -> campaign_name; ?></h2><div class="control-group"><a href="<?php echo JRoute::_($item -> stats); ?>" class="pull-right btn btn-primary">Manage</a></div>
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
    <?php endforeach; ?>
  </div>
  <div class="tab-pane" id="completed">
     <?php if(empty($items['completed'])) { echo 'You currently have no completed projects.';} ?>
    <?php foreach($items['completed']  as $item) : ?>
      
      <div class="well well-small">
      <div class="media">
  <a class="pull-left" href="<?php echo JRoute::_($item -> manage); ?>">
    <img class="media-object" src="<?php echo TiendaHelperCampaign::getImage($item, 'thumbs', '', true); ?>">
  </a>
  <div class="media-body">
    <h2 class="media-heading pull-left"><?php echo $item -> campaign_name; ?></h2><div class="control-group"><a href="<?php echo JRoute::_($item -> stats); ?>" class="pull-right btn btn-primary">Manage</a></div>
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
    <?php endforeach; ?>
  </div>
</div>
</div>  
  
  <div id="myCampaigns">
    
    <div class="well well-small">
      <div class="media">
 
  <div class="media-body">
    <h2 class="media-heading pull-left">Start a new project</h2><div class="control-group"><a href="<?php echo JURI::base(); ?>start" class="pull-right btn btn-primary">Start project</a></div>
    <div class="clearfix"></div>
    <!-- Nested media object -->
    <div class="media">
    
    </div>
  </div>
</div>
</div>
      
  </div>

</div>