<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php $form = @$this -> form;
$row = @$this -> row; 


?> 

<div class="tabbable">
<ul class="nav nav-pills">
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=edit&id='.$row->campaign_id);  ?>">Project Details</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=addlevels&id='.$row->campaign_id);  ?>">Funding Levels</a></li>
    <li class="active"><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=wepay&id='.$row->campaign_id);  ?>">WePay Account</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=checks&id='.$row->campaign_id);  ?>">Checks </a></li>
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

<?php else : ?>
Create Account on Wepay to receive Payments.<br>
<form method="post" action="<?php echo $this->action; ?>">
<input name="wepayTask" value="register" type="hidden">
<input name="id" type="hidden" value="<?php echo $row->campaign_id; ?>">
<button class="btn btn-large"> Connect with Wepay</button>
</form>	



<?php endif; ?>