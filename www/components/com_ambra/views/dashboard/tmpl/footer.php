<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
$url = "http://www.dioscouri.com/";
if ($amigosid = Ambra::getInstance()->get( 'amigosid', '' ))
{
    $url .= "?amigosid=".$amigosid; 
}
?>
<?php if(Ambra::getInstance()->get( 'show_linkback', '' )) : ?>
<p align="center">
	<?php echo JText::_( 'Powered by' )." <a href='{$url}' target='_blank'>".JText::_( 'Ambra User Manager' )."</a>"; ?>
</p>
<?php endif; ?>

