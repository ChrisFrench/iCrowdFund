<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
Billets::load( 'BilletsPluginBase', 'library.plugin.base' );

class plgBilletsCategoryFilterByAcl extends BilletsPluginBase 
{
	function __construct(& $subject, $config) 
	{
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
		$element = strtolower( 'com_Billets' );
		$this->loadLanguage( $element, JPATH_BASE );
		$this->loadLanguage( $element, JPATH_ADMINISTRATOR );
	}
	
	/**
	 * 
	 * @param $data
	 * @return unknown_type
	 */
	function onGetSelectListCategories( &$data ) 
	{
		$data = $this->_executeFilter( $data );
		return;		
	}
	
	/**
	 * 
	 * @param $data
	 * @return unknown_type
	 */
	function _executeFilter( $data )
	{
		if (!$this->params->get( 'acl2cats', '0' )) 
		{ 
			return $data; 
		}
		
		// param has association of Joomla ACL Ids = CSV of Billets categories, e.g.
		// 18=1,3,4,5,6
		// 25=1,2,3,4,5,6,7,8,9,10
		// Get full Param list
		$acl2cats = $this->params->get( 'acl2cats' );
		$acl2cats_params = new DSCParameter( $acl2cats );
		
		// based on user's gid
		$user = JFactory::getUser();
		$user_gid = $user->get( 'gid' );
		$user_acl2cats = explode( ',', $acl2cats_params->get( $user_gid ) );
		
		if (empty($user_acl2cats))
		{
			return $data;
		}
		
		// remove the cat from the array of objects $data
		// if it's id is in the CSV of categories to be filtered
		$newarray = array();
		if ($data) { foreach ($data as $cat) {
			// $cat = $data[$i];
			if (empty($cat->id))
			{
				$newarray[] = $cat;
			}
			elseif (!in_array( $cat->id, $user_acl2cats))
			{
				$newarray[] = $cat;
			}
		} }

		return $newarray;
	}

}
