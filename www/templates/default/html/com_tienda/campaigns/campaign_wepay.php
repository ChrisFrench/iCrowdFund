<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php $form = @$this -> form;
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
    <li class="active"><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=wepay&id='.$row->campaign_id);  ?>">WePay Account</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=checks&id='.$row->campaign_id);  ?>">Checklist </a></li>
  </ul>
</div>
<br>
    <div class="progress">
    <div class="bar bar-success" style="width: 75%;"></div>
    <div class="bar bar-warning" style="width: 25%;"></div>
    </div>

<?php if($row->wepay_account_id) : ?>
<br />
<div class="alert alert-success">	
Your Project is connected to wepay
</div>
<br>
<a class="btn btn-primary pull-right" href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=checks&id='.$row->campaign_id);  ?>">Next </a>

<?php else : ?>
Click here to connect this project to Wepay.<br>
<form method="POST" action="<?php echo JRoute::_($this->action); ?>">
<input name="wepayTask" value="register" type="hidden">
<input name="id" type="hidden" value="<?php echo $row->campaign_id; ?>">
<button class="btn btn-large"> Connect with Wepay</button>
</form>	



<?php endif; ?>
</div>

