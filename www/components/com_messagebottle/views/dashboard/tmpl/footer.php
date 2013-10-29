<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
$url = "http://www.dioscouri.com/";
if ($amigosid = Messagebottle::getInstance()->get( 'amigosid', '' ))
{
    $url .= "?amigosid=".$amigosid; 
}
?>

<p align="center" <?php echo @$this->style; ?> >
	<?php echo JText::_( 'COM_MESSAGEBOTTLE_POWERED_BY' )." <a href='{$url}' target='_blank'>".JText::_( 'Messagebottle' )."</a>"; ?>
</p>

