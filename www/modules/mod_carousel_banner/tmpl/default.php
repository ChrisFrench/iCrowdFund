<?php
/**
 * @version		$Id: mod_carousel_banner.php 2.0
 * @Joomla 1.7  by Rony S Y Zebua
 * @Official site http://www.templateplazza.com
 * @package		Joomla 1.7.x
 * @subpackage	mod_carousel_banner
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';

?>

<div class="carouselbanner<?php echo $moduleclass_sfx ?>">
<!-- Carousel Banner Module - Another Quality Freebie from TemplatePlazza.com -->
<div id="wrap">
<div id="mycarousel<?php echo $cb_mod_id; ?>" class="jcarousel-skin-tango">
<ul>

<?php foreach($list as $item):?>
		<li>
		<?php 
        $link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);
		if($item->type==1) :
			// Text based banners
			echo str_replace(array('{CLICKURL}', '{NAME}'), array($link, $item->name), $item->custombannercode);
		else:
			 
            $imageurl = $item->params->get('imageurl');
            $width = $params->get('width');
			$height = $params->get('height');
            
			if (BannerHelper::isImage($imageurl)) :
				// Image based banner
				$alt = $item->params->get('alt');
				$alt = $alt ? $alt : $item->name ;
				$alt = $alt ? $alt : JText::_('MOD_CAROUSEL_BANNER_BANNER') ;
				if ($item->clickurl) :
					// Wrap the banner in a link
					$target = $params->get('target', 1);
					if ($target == 1) :
						// Open in a new window
      ?>
						<a
							href="<?php echo $link; ?>" target="_blank"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php elseif ($target == 2): ?>
						<?php // open in a popup window?>
						<a
							href="javascript:void window.open('<?php echo $link;?>', '',
								'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
								return false"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php else :?>
						<?php // open in parent window?>
						<a
							href="<?php echo $link;?>"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php endif;?>
				<?php else :?>
					<?php // Just display the image if no link specified?>
					<img
						src="<?php echo $baseurl . $imageurl;?>"
						alt="<?php echo $alt;?>"
						<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
						<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
					/>
				<?php endif;?>
                
			<?php elseif (BannerHelper::isFlash($imageurl)) :?>
				<object
					classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
					codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
					<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
					<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
				>
					<param name="movie" value="<?php echo $imageurl;?>" />
					<embed
						src="<?php echo $imageurl;?>"
						loop="false"
						pluginspage="http://www.macromedia.com/go/get/flashplayer"
						type="application/x-shockwave-flash"
						<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
						<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
					/>
				</object>
			<?php endif;?>
		<?php endif;?>
		</li>
<?php endforeach; ?>
 </ul>
 </div>
 </div><!-- end wrap -->
</div>
