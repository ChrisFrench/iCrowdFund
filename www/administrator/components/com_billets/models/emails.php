<?php
/**
 * @version	1.5
 * @package	Billets
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2011 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Billets::load( 'BilletsModelBase', 'models._base' );

class BilletsModelEmails extends BilletsModelBase 
{
	// The prefix of the email language constants that we should fetch
	var $email_prefix = 'COM_BILLETS_BILLETS_EMAIL';
	
	function getTable($name='', $prefix='BilletsTable', $options = array())
	{
		JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
		$table = JTable::getInstance( 'Config', 'BilletsTable' );
		return $table;
	}
	
	public function getList($refresh = false)
	{
		jimport('joomla.language.helper');
		
		$list = JLanguageHelper::createLanguageList(JLanguageHelper::detectLanguage());
		
		foreach ($list as $l)
		{
			$l['link'] = "index.php?option=com_billets&view=emails&task=edit&id=".$l['value'];
			$item = new JObject();
			
			foreach ($l as $k => $v)
			{
				$item->$k = $v; 
			}
			$result[] = $item;			
		}
		
		return $result;
	}
	
	public function getItem( $id = 'en-GB') 
	{
        if (empty( $this->_item ))
        {
	    
    		$lang = JLanguage::getInstance($id);
    		// Load only site language (Email language constants should be only there)
    		$lang->load('com_billets', JPATH_ADMINISTRATOR, $id, true);
    		
    		$temp_paths = $lang->getPaths('com_billets');
    		foreach($temp_paths as $p => $k){
    			$path = $p;
    		}
    		
    		$result = new JObject();
    		$result->name = $lang->getName();
    		$result->code = $lang->getTag();
    		$result->path = $path;
    		
    		$result->strings = array();
    		
    		// Load File and Take only the constants that contains "BILLETS_EMAIL_"
    		$file = new JRegistry();
    		$file->loadFile($path);
    		$strings = $file->toArray();
    		$result_strings = array();
    		foreach($strings as $k =>$v){
    			// Only if it is a prefix!
    			if(stripos( $k, 'COM_BILLETS_BILLETS_EMAIL') === 0)
    				$result_strings[$k] = $v;
    		}
    		$result->strings = array('file' => $path,'strings' => $result_strings);
    		
    		
    		$this->_item = $result;
        }
		
		return $this->_item;
		
	}
}