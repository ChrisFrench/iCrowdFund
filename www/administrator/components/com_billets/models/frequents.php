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


Billets::load( 'BilletsModelTaxonomies', 'models.taxonomies' );

class BilletsModelFrequents extends BilletsModelTaxonomies 
{       	
	public function getList($refresh = false)
	{
		$list = parent::getList($refresh); 
		foreach(@$list as $item)
		{
			$item->link = 'index.php?option=com_billets&view=frequents&task=edit&id='.$item->id;
		}
		return $list;
	}
}
