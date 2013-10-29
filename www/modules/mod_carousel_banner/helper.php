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

jimport('joomla.application.component.model');

class modCarouselBannerHelper
{
	static function &getList(&$params)
	{
		jimport('joomla.application.component.model');
		JModel::addIncludePath(JPATH_ROOT.'/components/com_banners/models', 'BannersModel');
		$document	= JFactory::getDocument();
		$app		= JFactory::getApplication();
		$meta_keyword = $document->getMetaData('keywords');
		$keywords	= (!empty($meta_keyword)) ? explode(',', $meta_keyword) : null;

		$model = JModel::getInstance('Banners','BannersModel',array('ignore_request'=>true));
		$client_id = (int) $params->get('cid');
		if(!empty($client_id)){
			$model->setState('filter.client_id', (int) $client_id);
		}

		$catid = $params->get('catid', array());
		if(!empty($catid)){
			$model->setState('filter.category_id', $catid);
		}
		$model->setState('list.limit', (int) $params->get('count', 1));
		$model->setState('list.start', 0);
		$model->setState('filter.ordering', $params->get('ordering'));
		$model->setState('filter.tag_search', $params->get('tag_search'));
		if(!empty($keywords)){
			$model->setState('filter.keywords', $keywords);
		}
		$model->setState('filter.language', $app->getLanguageFilter());

		$banners = $model->getItems();
		$model->impress();

		return $banners;
	}
}
