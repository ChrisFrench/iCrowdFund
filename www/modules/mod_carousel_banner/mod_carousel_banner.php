<?php
/**
 * @version		$Id: mod_carousel_banner.php 2.0
 * @Joomla 1.7  by Rony S Y Zebua
 * @Official site http://www.templateplazza.com
 * @package		Joomla 1.7.x
 * @subpackage	mod_minifrontpage
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';

require_once JPATH_ROOT . '/administrator/components/com_banners/helpers/banners.php';

$doc			= JFactory::getDocument();
$baseurl        = ''.JURI::base(true).'/';
$modulebase		= ''.JURI::base(true).'/modules/mod_carousel_banner/';

$width          = (int) $params->get( 'width',728 );
$height         = (int) $params->get( 'height',90 );
$cb_mod_id		= $module->id;

$loadJqueryOpt  = (int) $params->get('loadJquery', 0);

// Load All css
$doc->addStylesheet($modulebase.'css/jquery.jcarousel.css');
$doc->addStylesheet($modulebase.'css/skin.css');

$cssinline = '
.carouselbanner ul#mycarousel'.$cb_mod_id.' {list-style-type:none;padding:0; margin:0;}
.jcarousel-skin-tango .jcarousel-container-horizontal{width:'.$width.'px;}
.jcarousel-skin-tango .jcarousel-clip-horizontal{width:'.$width.'px;}
';

$doc->addStyleDeclaration($cssinline, 'text/css');

// Then load all JS
if( $loadJqueryOpt == 1 ) {
	$doc->addScript($modulebase.'library/jquery-1.7.1.min.js');
}
else if( $loadJqueryOpt == 2 ) {
	$doc->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
} else {
    // no jquery lib will be loaded
}

$doc->addScript($modulebase.'library/jquery.jcarousel.min.js');

$jsinline = '
function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind(\'click\', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind(\'click\', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};



jQuery.easing[\'BounceEaseOut\'] = function(p, t, b, c, d) {
	if ((t/=d) < (1/2.75)) {
		return c*(7.5625*t*t) + b;
	} else if (t < (2/2.75)) {
		return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
	} else if (t < (2.5/2.75)) {
		return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
	} else {
		return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
	}
};

jQuery.noConflict();
jQuery(document).ready(function() {
	jQuery("#mycarousel'. $cb_mod_id.'").jcarousel({
        auto: 5,
		scroll:1,
		rtl:false,
		visible:1,
        wrap: \'circular\',
		easing: \'swing\',
        animation: 1000,
        initCallback: mycarousel_initCallback
    });
});
';
$doc->addScriptDeclaration($jsinline);

BannersHelper::updateReset();
$list = &modCarouselBannerHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_carousel_banner', $params->get('layout', 'default'));
