<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php 

$form = @$this -> form;
$row = @$this -> row;



?>
<ul class="nav nav-pills">
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=edit&id='.$row->campaign_id);  ?>">Project Details</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=addlevels&id='.$row->campaign_id);  ?>">Funding Levels</a></li>
    <li class=""><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=wepay&id='.$row->campaign_id);  ?>">WePay Account</a></li>
    <li class="active"><a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=checks&id='.$row->campaign_id);  ?>">Checks </a></li>
  </ul>
 <div class="progress">
    <div class="bar bar-success" style="width: 85%;"></div>
    <div class="bar bar-warning" style="width: 15%;"></div>
    </div>
    <br />

<?php foreach($this->checks as $check) : ?>

<div class="alert <?php echo ($check->status ? 'alert-success' : 'alert-error'); ?>"><strong><?php echo $check->title; ?> </strong> : <?php echo $check->msg; ?>
<a href="<?php  echo $check->edit_link; ?>" class=" pull-right">edit</a>
<br class="clearfix" >
</div>



<?php endforeach;?>


