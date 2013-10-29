<?php defined('_JEXEC') or die('Restricted access'); ?>

	<?php
		$defines = Featureditems::getInstance();
		$img_file = "dioscouri_logo_transparent.png";
		$img_path = "../media/com_featureditems/images";

		$url = "http://www.dioscouri.com/";
		if ($amigosid = $defines->get( 'amigosid', '' ))
		{
			$url .= "?amigosid=".$amigosid;
		}
	?>

	<table style="margin-bottom: 5px; width: 100%; border-top: thin solid #e5e5e5;">
	<tbody>
	<tr>
		<td style="text-align: left; width: 33%;">
			<a href="<?php echo $url; ?>" target="_blank"><?php echo JText::_( 'COM_FEATUREDITEMS_DIOSCOURI_SUPPORT_CENTER' ); ?></a>
			<br/>
			<a href="http://twitter.com/dioscouri" target="_blank"><?php echo JText::_( "COM_FEATUREDITEMS_FOLLOW_US_ON_TWITTER" ); ?></a>
			<br/>
			<a href="http://extensions.joomla.org/extensions/owner/dioscouri" target="_blank"><?php echo JText::_( "COM_FEATUREDITEMS_LEAVE_JED_FEEDBACK" ); ?></a>
		</td>
		<td style="text-align: center; width: 33%;">
			<?php echo JText::_( "Featureditems" ); ?>: <?php echo JText::_( "COM_FEATUREDITEMS_DESC" ); ?>
			<br/>
			<?php echo JText::_( "COM_FEATUREDITEMS_COPYRIGHT" ); ?>: <?php echo $defines->getCopyrightYear(); ?> &copy; <a href="<?php echo $url; ?>" target="_blank">Dioscouri Design</a>
			<br/>
			<?php echo JText::_( "COM_FEATUREDITEMS_VERSION" ); ?>: <?php echo $defines->getVersion(); ?>
		</td>
		<td style="text-align: right; width: 33%;">
			<a href="<?php echo $url; ?>" target="_blank"><img src="<?php echo $img_path."/".$img_file;?>"></img></a>
		</td>
	</tr>
	</tbody>
	</table>
