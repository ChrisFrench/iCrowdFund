<?php defined('_JEXEC') or die('Restricted access');

 ?>

	<?php
		$img_file = "dioscouri_logo_transparent.png";
		$img_path = "../media/com_billets/images";

		$url = "http://www.dioscouri.com/";
		if ($amigosid = Billets::getInstance()->get( 'amigosid', '' ))
		{
			$url .= "?amigosid=".$amigosid;
		}
	?>

	<table style="margin-bottom: 5px; width: 100%; border-top: thin solid #e5e5e5;">
	<tbody>
	<tr>
		<td style="text-align: left; width: 33%;">
			<a href="<?php echo $url; ?>" target="_blank"><?php echo JText::_('COM_BILLETS_DIOSCOURI_COM_SUPPORT_CENTER'); ?></a>
			<br/>
			<a href="http://twitter.com/dioscouri" target="_blank"><?php echo JText::_('COM_BILLETS_FOLLOW_US_ON_TWITTER'); ?></a>
			<br/>
			<a href="http://extensions.joomla.org/extensions/owner/dioscouri" target="_blank"><?php echo JText::_('COM_BILLETS_LEAVE_JED_FEEDBACK'); ?></a>
		</td>
		<td style="text-align: center; width: 33%;">
			<?php echo JText::_('COM_BILLETS_BILLETS'); ?>: <?php echo JText::_('COM_BILLETS_BILLETS_DESC'); ?>
			<br/>
			<?php echo JText::_('COM_BILLETS_COPYRIGHT'); ?>: <?php echo Billets::getInstance()->getCopyrightYear(); ?> &copy; <a href="<?php echo $url; ?>" target="_blank">Dioscouri Design</a>
			<br/>
			<?php echo JText::_('COM_BILLETS_VERSION'); ?>: <?php echo Billets::getInstance()->getVersion(); ?>
            <br/>
            <?php echo sprintf( JText::_('COM_BILLETS_PHP_VERSION_LINE'), Billets::getInstance()->getMinPhp(), Billets::getInstance()->getServerPhp() );?>
		</td>
		<td style="text-align: right; width: 33%;">
			<a href="<?php echo $url; ?>" target="_blank"><img src="<?php echo $img_path."/".$img_file;?>"></img></a>
		</td>
	</tr>
	</tbody>
	</table>
