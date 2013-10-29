<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php 

$form = @$this -> form;
$row = @$this -> row;



?>
<?php  if(empty($row->campaign_id)) {
    $title = 'Create Project';
  } else {
     $title = 'Project Details';
  }?>
 <h1 class="indent-60"><?php echo $title; ?></h1>
 <div class="indent-60">
  <div class="tabbable">
<ul class="nav nav-pills">
    <li class="first"><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=edit&id='.$row->campaign_id);  ?>">Project Details</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=addlevels&id='.$row->campaign_id);  ?>">Funding Levels</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=wepay&id='.$row->campaign_id);  ?>">WePay Account</a></li>
    <li class="active"><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=checks&id='.$row->campaign_id);  ?>">Checklist </a></li>
  </ul>
</div>
<?php  if($row->campaign_enabled) : ?>
    <div class="progress">
    <div class="bar bar-success" style="width: 100%;"></div>

    </div>
    <br />
<?php else : ?>
 <div class="progress">
    <div class="bar bar-success" style="width: 85%;"></div>
    <div class="bar bar-warning" style="width: 15%;"></div>
    </div>
    <br />
<?php  endif; ?>    
<?php $complete = true; ?>

<?php foreach($this->checks as $check) : ?>

<div class="alert <?php echo ($check->status ? 'alert-success' : 'alert-error'); ?>"><strong><?php echo $check->title; ?> </strong> : <?php echo $check->msg; ?>
<a href="<?php  echo $check->edit_link; ?>" class=" pull-right">edit</a>
<br class="clearfix" >
</div>
<?php if(!$check->status ) {
  $complete = $check->status;
} ?>


<?php endforeach;?>
<?php $showBackHome = false; ?>
<?php  if($complete) : ?>

<?php
 // if enabled say enabled
  if($row->campaign_enabled) { 
  	echo 'Your project is live!';
     $showBackHome = true;
}

 if($row->campaign_ready && !$row->campaign_enabled) { 
  	echo 'Your project is still pending';	
     $showBackHome = true;
}

if(!$row->campaign_ready && !$row->campaign_enabled) { 
	 ?>

<a class="btn btn-primary pull-left btn-large" href="index.php?option=com_tienda&view=campaigns&task=ready&id=<?php echo $row->campaign_id; ?>">Your project passed all the checks! Submit  project  for review.</a>
<?php } ?>

<?php  else : ?>
Your project hasn't passed all the checks and can not be put live.
<?php  endif; ?>

<?php if( $showBackHome) : ?>
<br><br>

<a class="btn btn-primary  pull-right" href="index.php">Back to Home</a>
<?php endif; ?>
</div>
