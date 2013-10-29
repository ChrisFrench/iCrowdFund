<?php
/**
 * @version	1.5
 * @package	Wepay
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


jimport('joomla.filesystem.file');
Wepay::load("TiendaHelperProduct", 'com_tienda.helpers.product');
class WepayHelperCampaign extends DSCHelper {
	public $campaigns = array();

	/**
	 * Gets a category's image
	 *
	 * @param $id
	 * @param $by
	 * @param $alt
	 * @param $type
	 * @param $url
	 * @return unknown_type
	 */

	public static function getImage($row, $type = 'thumb', $alt = '', $url = false) {
		switch($type) {
			case "full" :
				$path = Wepay::getPath('media') . '/campaigns/images/' . $row -> campaign_id;
				$uri = Wepay::getUrl('media') . 'campaigns/images/' . $row -> campaign_id;
				break;
			case "thumb" :
			default :
				$path = Wepay::getPath('media') . '/campaigns/images/' . $row -> campaign_id . '/thumbs';
				$uri = Wepay::getUrl('media') . 'campaigns/images/' . $row -> campaign_id . '/thumbs';
				break;
		}
		
		$id = $row -> campaign_full_image;

		$tmpl = "";
		if (strpos($id, '.')) {
			// then this is a filename, return the full img tag if file exists, otherwise use a default image
			$src = $uri . '/' . $id;

			//$src = (JFile::exists($path . '/' . $id)) ? Wepay::getUrl('media') . 'campaigns/images/'.$row->campaign_id .'/'. $id : '/media/com_tienda/images/noimage.png';

			// if url is true, just return the url of the file and not the whole img tag
			$tmpl = ($url) ? $src : "<img src='" . $src . "' alt='" . JText::_($alt) . "' title='" . JText::_($alt) . "' align='middle' border='0' />";

		}

		return $tmpl;
	}
	
	public static function video($url,$option = array()) {
		$video = WepayHelperCampaign::checkProvider($url);
		
		switch ($video['type']) {
			case 'youtube':
				
			return	WepayHelperCampaign::youtube($video['url'],$option);
				break;
			
			case 'vimeo':
			return	WepayHelperCampaign::vimeo($video['url'],$option );
				break;
			default:
				
				break;
		}
		
	}
	
	public static function youtube($url, $option = array()) {
		if(empty($option)){
		 $option = array('width'=> '530', 'hieght' => '335', 'options' => 'allowfullscreen ');	
		}
		
		$html = '';
		$html .= '<iframe width="'.$option['width'].'" height="'.$option['hieght'].'" src="'.$url.'" frameborder="0" '.$option['options'].' ></iframe>';
		
		return $html;
		
	}
	
	public static function vimeo($url, $option = array()) {
		if(empty($option)){
		 $option = array('width'=> '530', 'hieght' => '335', 'options' => 'webkitAllowFullScreen mozallowfullscreen allowFullScreen ');	
		}
		$html ='<iframe src="'.$url.'" width="'.$option['width'].'" height="'.$option['hieght'].'" frameborder="0" '.$option['options'].' ></iframe>';
		return $html;
	}
	
	public static function checkProvider($url) {
		
		$type = array();
		if(strpos($url,'youtube.com/watch?v')) {
			parse_str( parse_url( $url, PHP_URL_QUERY ), $vars );
			
			$url = 'http://www.youtube.com/embed/' . $vars['v']; 
			$type['type'] = 'youtube' ;
		}
		if(strpos($url,'vimeo.com')) {

			//TODO quick and dirty this isn't very advanced
			$id = preg_replace ( '/[^0-9]/', '', $url );
			
			$url = "http://player.vimeo.com/video/{$id}?portrait=0&amp;badge=0";
			$type['type'] = 'vimeo' ;
		}
		
		
		$type['url'] = $url ;
		
		
		return $type;
	}

	public static function gravatar($user, $size = "40", $default = 'wavatar') {

		$grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($user -> email))) . "?d=" . urlencode($default) . "&s=" . $size;
		return $grav_url;
	}

	public static function percent($amount, $goal, $max = '0') {
		$count1 = $amount / $goal;
		$count2 = $count1 * 100;
		$count = number_format($count2, 0);
		if ($max) {
			if ($count > $max && $max) {
				return $max;
			}
		}
		return $count;
	}

	public static function daysRemaining($start, $end, $string = NULL, $small = NULL) {

		$date1 = new DateTime();
		
		$date2 = new DateTime($end);
		if ($date1 > $date2) {
			return '<div class="completed">COMPLETED </div>';
		}
		$interval = $date1 -> diff($date2);
		if($interval -> days == 1 && $small) {
			return '<div class="ends">ENDS </div><div class="today">TODAY</div>';
		}
		if ($interval -> days < 1) {
			return WepayHelperCampaign::get_timespan_string($date1, $date2);
		}

		if ($string && $small) {
			return '<div class="daysnumber">' . $interval -> days . '</div>' . ($interval -> days < 1 ? '<div class="day"><span class="day"> Day</span></div>' : '<div class="days"><span class="days"> Days</span></div>');
		}
		if ($string) {
			return '<div class="daysnumber">' . $interval -> days . '</div>' . ($interval -> days < 1 ? '<div class="day"><span class="day"> Day</span> to go</div>' : '<div class="days"><span class="days"> Days</span> to go</div>');
		}

		return $interval -> days;

		//printf("%d years, %d months, %d days\n", $years, $months, $days);

	}
	public static function campaignBackersStore($campaign_id = null, $user_id = null, $order_id = null,$product_id = null ) {
		JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_tienda/models' );
		$model = JModel::getInstance( 'CampaignBackers', 'WepayModel' );
		$table = $model->getTable();
		$keys = array();
		if($campaign_id) {
			$keys['campaign_id'] = $campaign_id;
		} 
		if($user_id) {
			$keys['user_id'] = $user_id;
		} 
		if($order_id) {
			$keys['order_id'] = $order_id;
		} 
		if($product_id) {
			$keys['product_id'] = $product_id;
		} 
		if($table->load($keys)) {
			return false;
		} 
		$table->bind($keys); //
		
		if($table->store()) {
			return $table;
		} 
		
	}
	
	public static function getCampaignFromProduct($pid) {
		$db = JFactory::getDBO();
		$query = "SELECT c.*  FROM #__tienda_products as p INNER JOIN #__tienda_campaignproducts as cp ON p.product_id = cp.product_id
	 INNER JOIN #__tienda_campaigns as c ON cp.campaign_id = c.campaign_id WHERE p.product_id = '{$pid}' LIMIT 1";
		$db -> setQuery($query);
		$cat = $db -> loadObject();
		return $cat;
	}

	public static function displayCampaignStats($campaign, $id = "campaignStats", $class = 'stats row', $progresbar = FALSE, $refresh = NULL) {
		if (empty($campaign -> campaign_id))
			return;
		if ($refresh) {
			//do a bunch of queries to  get the exact amounts
		}
		$options = array();
		$options['num_decimals'] = 0;
		$percent = WepayHelperCampaign::percent($campaign -> campaign_raised, $campaign -> campaign_goal);
		$days = WepayHelperCampaign::daysRemaining($campaign -> fundingstart_date, $campaign -> fundingend_date, TRUE, TRUE);
		$amount = WepayHelperProduct::currency($campaign -> campaign_raised, '', $options);
		if($progresbar) {
			$html = '<div class="progress progress-thin"><div class="bar thinbar" style="width: '.$percent.'%;"></div></div>';
		} else {
			$html = '';
		}
		$html .= '<div id="' . $id . $campaign -> campaign_id . '" class="campaign-stats table">';			
		$html .= '<ul id="list' . $id . $campaign -> campaign_id . '" class="' . $class . '">';
		$html .= '<li id="raised' . $campaign -> campaign_id . '" class="raised cell" ><div class="raisedNumber">' . $percent . '%</div><div class="raisedText"> Raised</div></li>';
		$html .= '<li id="amount' . $campaign -> campaign_id . '" class="amount cell" ><div class="amountNumber">' . $amount . '</div><div class="raisedText"> Pledged</div></li>';
		$html .= '<li id="daysleft' . $campaign -> campaign_id . '" class="daysLeft last cell" ><div class="daysNumber">' . $days . '</div><div class="raisedText"></div></li>';
		$html .= '</ul>';
		$html .= '</div>';
		return $html;

	}
	
	public static function displayCampaignStatsFull($campaign, $id = "campaignStatsFull", $class = 'stats row', $progresbar = TRUE, $refresh = NULL) {
		if (empty($campaign -> campaign_id))
			return;
		if ($refresh) {
			//do a bunch of queries to  get the exact amounts
		}
		$options = array();
		$options['num_decimals'] = 0;
		$percent = WepayHelperCampaign::percent($campaign -> campaign_raised, $campaign -> campaign_goal);
		$days = WepayHelperCampaign::daysRemaining($campaign -> fundingstart_date, $campaign -> fundingend_date, TRUE, TRUE);
		$amount = WepayHelperProduct::currency($campaign -> campaign_raised, '', $options);
		if($progresbar) {
			$html = '<div class="progress progress-thin"><div class="bar thinbar" style="width: '.$percent.'%;"></div></div>';
		} else {
			$html = '';
		}
		$html .= '<div id="' . $id . $campaign -> campaign_id . '" class="campaign-stats-full table">';			
		$html .= '<ul id="list' . $id . $campaign -> campaign_id . '" class="' . $class . '">';
		$html .= '<li id="raised' . $campaign -> campaign_id . '" class="raised cell" ><div class="raisedNumber">' . $percent . '%</div><div class="raisedText text"> Raised</div></li>';
		$html .= '<li id="amount' . $campaign -> campaign_id . '" class="amount cell" ><div class="amountNumber">' . $amount . '</div><div class="raisedText text"> Pledged</div></li>';
		$html .= '<li id="daysleft' . $campaign -> campaign_id . '" class="daysLeft cell" ><div class="daysNumber">' . $days . '</div><div class="raisedText text"></div></li>';
		$html .= '<li id="backers' . $campaign -> campaign_id . '" class="backers cell" ><div class="backersNumber">' . count(@$campaign->backers) . '</div><div class="backersText text"> Backers</div></li>';
		$html .= '<li id="updates' . $campaign -> campaign_id . '" class="backers last cell" ><div class="updatesNumber">' . count(@$campaign->updates) . '</div><div class="updatesText text"> Updates</div></li>';
		
		$html .= '</ul>';
		$html .= '</div>';
		return $html;

	}


	public static function getSectors() {
		JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_categories/tables' );
		JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_categories/models' );
		$model = JModel::getInstance('Categories','CategoriesModel');
		$model->setState('list.select','a.id, a.title, a.alias, a.note, a.published, a.params');
		$app	= JFactory::getApplication();
		$app -> setUserState('com_categories.categories.filter.extension', 'com_tienda.campaigns');
		$items = $model -> getItems();
		return $items;
	}

	public static function getSectorsSelectList($selected, $name = 'filter_range', $attribs = array('class' => 'inputbox'), $idtag = null, $allowAny = false, $title = 'Select Category') {

		$items = WepayHelperCampaign::getSectors();
		$list = array();
		if ($allowAny) {
			$list[] = DSCSelect::option('', "- " . JText::_($title) . " -");
		}
		foreach ($items as $item) {
			$list[] = JHTML::_('select.option', $item -> id, $item -> title);

		}

		return DSCSelect::genericlist($list, $name, $attribs, 'value', 'text', $selected, $idtag);
	}

	public static function getSectorsList($active = 0, $id = 'SectorList', $class = "sectors", $links = true) {
		$items = WepayHelperCampaign::getSectors();
		$html = '<ul id="' . $id . '" class="' . $class . '">';
		$isactive = '';
		
		foreach ($items as $item) {
			if ($active == $item -> id) {$isactive = ' active activebg white ';
			}
			$registry = new JRegistry();
			$registry->loadString($item->params);
			$item->params = $registry->toArray();
			if(@$item -> params['Itemid']) {
				$link = JRoute::_('index.php?Itemid='.@$item -> params['Itemid']);
			} else {
				$link = JRoute::_('index.php?option=com_tienda&view=campaigns&category_id='.$item -> id);
			}
			
			
			$html .= '<li id="' . $item -> id . '" class="sectorlist' . $isactive . '"><a href="'.$link.'">' . $item -> title . '</a></li>';
			$isactive = '';
		}
		$html .= '</ul>';
		return $html;
	}

	/**
	 * Finds the prev & next items in the list
	 *
	 * @param $id   product id
	 * @return array( 'prev', 'next' )
	 */
	function getSurrounding($id) {
		$return = array();

		$prev = intval(JRequest::getVar("prev"));
		$next = intval(JRequest::getVar("next"));
		if ($prev || $next) {
			$return["prev"] = $prev;
			$return["next"] = $next;
			return $return;
		}

		$app = JFactory::getApplication();
		JModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tienda/models');
		$model = JModel::getInstance('Campaigns', 'WepayModel');
		$ns = $app -> getName() . '::' . 'com.tienda.model.' . $model -> getTable() -> get('_suffix');
		$state = array();

		$state['limit'] = $app -> getUserStateFromRequest('global.list.limit', 'limit', $app -> getCfg('list_limit'), 'int');
		$state['limitstart'] = $app -> getUserStateFromRequest($ns . 'limitstart', 'limitstart', 0, 'int');
		$state['filter'] = $app -> getUserStateFromRequest($ns . '.filter', 'filter', '', 'string');
		$state['direction'] = $app -> getUserStateFromRequest($ns . '.filter_direction', 'filter_direction', 'ASC', 'word');

		$state['order'] = $app -> getUserStateFromRequest($ns . '.filter_order', 'filter_order', 'tbl.lft', 'cmd');
		$state['filter_id_from'] = $app -> getUserStateFromRequest($ns . 'id_from', 'filter_id_from', '', '');
		$state['filter_id_to'] = $app -> getUserStateFromRequest($ns . 'id_to', 'filter_id_to', '', '');
		$state['filter_name'] = $app -> getUserStateFromRequest($ns . 'name', 'filter_name', '', '');
		$state['filter_parentid'] = $app -> getUserStateFromRequest($ns . 'parentid', 'filter_parentid', '', '');
		$state['filter_enabled'] = $app -> getUserStateFromRequest($ns . 'enabled', 'filter_enabled', '', '');

		foreach (@$state as $key => $value) {
			$model -> setState($key, $value);
		}
		$rowset = $model -> getList();

		$found = false;
		$prev_id = '';
		$next_id = '';

		for ($i = 0; $i < count($rowset) && empty($found); $i++) {
			$row = $rowset[$i];
			if ($row -> category_id == $id) {
				$found = true;
				$prev_num = $i - 1;
				$next_num = $i + 1;
				if (isset($rowset[$prev_num] -> category_id)) { $prev_id = $rowset[$prev_num] -> category_id;
				}
				if (isset($rowset[$next_num] -> category_id)) { $next_id = $rowset[$next_num] -> category_id;
				}

			}
		}

		$return["prev"] = $prev_id;
		$return["next"] = $next_id;
		return $return;
	}

	/* Taken from http://www.php.net/manual/en/datetime.diff.php#107434  Thanks sgmurphy19*/
	public static function get_timespan_string($older, $newer) {
		$Y1 = $older -> format('Y');
		$Y2 = $newer -> format('Y');
		$Y = $Y2 - $Y1;

		$m1 = $older -> format('m');
		$m2 = $newer -> format('m');
		$m = $m2 - $m1;

		$d1 = $older -> format('d');
		$d2 = $newer -> format('d');
		$d = $d2 - $d1;

		$H1 = $older -> format('H');
		$H2 = $newer -> format('H');
		$H = $H2 - $H1;

		$i1 = $older -> format('i');
		$i2 = $newer -> format('i');
		$i = $i2 - $i1;

		$s1 = $older -> format('s');
		$s2 = $newer -> format('s');
		$s = $s2 - $s1;

		if ($s < 0) {
			$i = $i - 1;
			$s = $s + 60;
		}
		if ($i < 0) {
			$H = $H - 1;
			$i = $i + 60;
		}
		if ($H < 0) {
			$d = $d - 1;
			$H = $H + 24;
		}
		if ($d < 0) {
			$m = $m - 1;
			$d = $d + WepayHelperCampaign::get_days_for_previous_month($m2, $Y2);
		}
		if ($m < 0) {
			$Y = $Y - 1;
			$m = $m + 12;
		}
		$timespan_string = WepayHelperCampaign::create_timespan_string($Y, $m, $d, $H, $i, $s);
		return $timespan_string;
	}

	public static function get_days_for_previous_month($current_month, $current_year) {
		$previous_month = $current_month - 1;
		if ($current_month == 1) {
			$current_year = $current_year - 1;
			//going from January to previous December
			$previous_month = 12;
		}
		if ($previous_month == 11 || $previous_month == 9 || $previous_month == 6 || $previous_month == 4) {
			return 30;
		} else if ($previous_month == 2) {
			if (($current_year % 4) == 0) {//remainder 0 for leap years
				return 29;
			} else {
				return 28;
			}
		} else {
			return 31;
		}
	}

	public static function create_timespan_string($Y, $m, $d, $H, $i, $s) {
		$timespan_string = '';
		$found_first_diff = false;
		/*if ($Y >= 1) {
			$found_first_diff = true;
			$timespan_string .= WepayHelperCampaign::pluralize($Y, 'year') . ' ';
		}
		if ($m >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= WepayHelperCampaign::pluralize($m, 'month') . ' ';
		}
		if ($d >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= WepayHelperCampaign::pluralize($d, 'day') . ' ';
		}*/
		if ($H >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= WepayHelperCampaign::pluralize($H, 'Hr.') . ' ';
		}
		if ($i >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= WepayHelperCampaign::pluralize($i, 'Min.') . ' ';
		}
		//if ($found_first_diff) {
		//	$timespan_string .= 'and ';
		//}
		//$timespan_string .= WepayHelperCampaign::pluralize($s, 'second');
		return $timespan_string . ' remaining';
	}

	public static function pluralize($count, $text) {
		return $count . (($count == 1) ? (" $text") : (" ${text}s"));
	}

}
