<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
$url = "http://www.dioscouri.com/";
if ($amigosid = Extendform::getInstance()->get( 'amigosid', '' ))
{
    $url .= "?amigosid=".$amigosid; 
}
?>

<p align="center" <?php echo @$this->style; ?> >
	<?php echo JText::_( 'COM_SAMPLE_POWERED_BY' )." <a href='{$url}' target='_blank'>".JText::_( 'Extendform' )."</a>"; ?>
</p>

