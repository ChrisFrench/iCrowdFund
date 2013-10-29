<?php
/**
 * @version	1.5
 * @package	Tos
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/
defined('_JEXEC') or die('Restricted access'); ?>

	<?php
	

		$img_file = "dioscouri_logo_transparent.png";
		$img_path = "../media/dioscouri/images";

		$url = "http://www.dioscouri.com/";
		$amigosid = '33753';
		
		$url .= "?amigosid=".$amigosid;
		
	?>

	<table style="margin-bottom: 5px; width: 100%; border-top: thin solid #e5e5e5;">
	<tbody>
	<tr>
		<td style="text-align: left; width: 33%;">
				Proudly built by <a href="http://ammonitenetworks.com" target="_blank">Ammonite Networks</a>, on the <a href="<?php echo $url; ?>" target="_blank">Dioscouri Design</a> Development Library.  
		</td>
		<td style="text-align: center; width: 33%;">
			<?php echo JText::_( "Tos" ); ?>: <?php echo JText::_( "COM_TOS_DESC" ); ?>
			<br/>
			<?php echo JText::_( "COM_TOS_COPYRIGHT" ); ?>: <?php echo Tos::getInstance()->getCopyrightYear(); ?> &copy; 
			<br/>
			<?php echo JText::_( "COM_TOS_VERSION" ); ?>: <?php echo Tos::getInstance()->getVersion(); ?>
		</td>
		<td style="text-align: right; width: 33%;">
		
		</td>
	</tr>
	</tbody>
	</table>
