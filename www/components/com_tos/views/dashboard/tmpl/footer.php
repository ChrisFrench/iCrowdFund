<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
$url = "http://www.ammonitenetworks.com/";
if ($amigosid = Tos::getInstance()->get( 'amigosid', '' ))
{
    $url .= "?amigosid=".$amigosid; 
}
?>

<p align="center" <?php echo @$this->style; ?> >
	<?php echo JText::_( 'COM_TOS_POWERED_BY' )." <a href='{$url}' target='_blank'>".JText::_( 'Tos' )."</a>"; ?>
</p>

