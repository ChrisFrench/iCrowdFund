<?php 
defined('_JEXEC') or die('Restricted access');
JHTML::_('stylesheet', 'menu.css', 'media/com_tienda/css/');
$display_subscriptions = Tienda::getInstance()->get( 'display_subscriptions', 1 );
$display_mydownloads = Tienda::getInstance()->get( 'display_mydownloads', 1 );
?>


<h1><?php $doc = JFactory::getDocument(); echo $doc->getTitle(); ?></h1>

<ul id="<?php echo $this->name; ?>" class="nav nav-tabs">

<?php 
foreach ($this->items as $item) {
		if( strpos( $item[1],'subscriptions' ) !== false && !$display_subscriptions )
			continue;

		if( strpos( $item[1],'productdownloads' ) !== false && !$display_mydownloads )
			continue;
			
    if ($this->hide) {
        
        if ($item[2] == 1) {
        ?>  <span class="nolink active"><?php echo $item[0]; ?></span> <?php
        } else {
        ?>  <span class="nolink"><?php echo $item[0]; ?></span> <?php    
        }
        
    } else {
        
        if ($item[2] == 1) {
        ?><li class="active"><a href="<?php echo JRoute::_( $item[1] ); ?>"><?php echo $item[0]; ?></a></li> <?php
        } else {
        ?> <li><a href="<?php echo JRoute::_( $item[1] ); ?>"><?php echo $item[0]; ?></a></li> <?php   
        }        
    }
    
}
?>
</ul>
