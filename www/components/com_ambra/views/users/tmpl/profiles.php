<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('stylesheet', 'menu.css', 'media/com_ambra/css/'); ?>
<?php $user = JFactory::getUser(); ?>
<?php $rows = @$this->rows; ?>
<?php // $categories = @$this->categories; ?>
<?php // $userdata = @$this->userdata; ?>

<div class='componentheading'>
    <span><?php echo JText::_( "View Profile" ); ?></span>
</div>
<ul class="thumbnails">
   <?php foreach($rows as $row) : ?>
<?php $itemid_suffix = '';
    if (  $itemid = Ambra::getInstance()->get('profile_itemid')) { $itemid_suffix = '&Itemid='.$itemid; }
    $url = "index.php?option=com_ambra&view=users".$itemid_suffix; ?>

<li class="span3">
                <div class="thumbnail">
                  <img src="<?php echo Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatar( $row->id ); ?>">
                  <div class="caption">
                    <h3><?php echo $row->name;   ?></h3>
                    <p></p>
                    <p><a href="<?php echo JRoute::_( $url."&id=".$row->id ); ?>" class="btn btn-primary">Profile</a> </p>
                  </div>
                </div>
              </li>
   

   <?php endforeach; ?>
</ul>
   