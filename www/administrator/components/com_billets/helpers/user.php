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

Billets::load('BilletsHelperBase','helpers._base' );

class BilletsHelperUser extends DSCHelperUser
{

    /**
     * Check if is installed
     * 
     * @return unknown_type
     */
    public static function isInstalledCb()
    {
        $db = JFactory::getDBO();
        
        // Manually replace the Joomla Tables prefix. Automatically it fails
        // because the table name is between single-quotes
        $db->setQuery(str_replace('#__', $db->getPrefix(), "SHOW TABLES LIKE '#__comprofiler'"));
        $result = $db->loadObject();
        
        if($result === null) return false;
        else return true;
    }
    
    /**
     * Retrieves the values
     * @return array Array of objects containing the data from the database
     */
    public static function getNameDisplay() 
    {
        static $instance;
        
        if (!is_array($instance)) {
            $instance = array();
                $instance[0] = new stdClass();
                $instance[0]->id = 1;
                $instance[0]->title = 'Name';

                $instance[1] = new stdClass();
                $instance[1]->id = 2;
                $instance[1]->title = 'Username';
                
                $instance[2] = new stdClass();
                $instance[2]->id = 3;
                $instance[2]->title = 'Email';
        }

        return $instance;
    }
    
    /**
     * Creates a List
     * @return array Array of objects containing the data from the database
     */
    public static function getArrayListNameDisplay() {
        static $instance;
        
        if (!is_array($instance)) {
            $instance = array();
            $data = BilletsHelperUser::getNameDisplay();
            for ($i=0; $i<count($data); $i++) {
                $d = $data[$i];
                $instance[] = JHTML::_('select.option', $d->id, JText::_( $d->title ) );
            }
        }

        return $instance;

    }
    
	/**
	 * Determines whether/not an account is suspended
	 * 
	 * @param $account 		Valid Account object
	 * @return boolean
	 */
	public static function suspended( $account )
	{
		$success = false;
		if (!is_object($account))
		{
			return true;
		}
		
		if (empty($account->id) || empty($account->approved) || empty($account->enabled))
		{
			$success = true;
		}
		
		return $success;
	}
		
	/**
	 * Creates a unique username based on the provided email address
	 * 
	 * @param $email
	 * @return unknown_type
	 */
	public static function createUsernameFromEmail( $email )
	{
	    $parts = explode('@', $email);
	    $name = $parts[0];
	    
	    $n = 1;
	    while (BilletsHelperUser::usernameExists($name))
	    {
	        $name = $parts[0].$n;
	        $n++;
	    }
	    
	    return $name;
	}
	
	/**
	 * Creates a unique username 
	 * 
	 * @param $newusername
	 * @return unknown_type
	 */
	public static function createValidUsername($newusername)
	{
		$name = $newusername;
		
		$name = preg_replace("/[^a-zA-Z0-9]/", "", $name);
		
		$n = 1;
		while(BilletsHelperUser::usernameExists($name))
		{
			$name = $name.$n;
	        $n++;
		}
		
		return $name;
	}
	
	

	/**
	 * 
	 * @param $string
	 * @return unknown_type
	 */
	public static function emailExists( $string, $table='users'  ) 
	{
		switch($table)
		{
			case  'users':
			default     :
				$table = '#__users';
		}
		
		$success = false;
		$database = JFactory::getDBO();
		$string = $database->getEscaped($string);
		$query = "
			SELECT 
				*
			FROM 
				$table
			WHERE 1
			AND 
				`email` = '{$string}'
			LIMIT 1
		";
		$database->setQuery($query);
		$result = $database->loadObject();
		if ($result) {
			$success = true;
		}		
		return $result;		
	}
	
	/**
	 * Validate email address
	 * @param $string
	 * @return bool
	 */	
	public static function validateEmailAddress($email) {
		  // First, we check that there's one @ symbol, 
		  // and that the lengths are right.
		  if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
		    // Email invalid because wrong number of characters 
		    // in one section or wrong number of @ symbols.
		    return false;
		  }
		  // Split it into sections to make life easier
		  $email_array = explode("@", $email);
		  $local_array = explode(".", $email_array[0]);
		  for ($i = 0; $i < sizeof($local_array); $i++) {
		    if(!preg_match("/^(([A-Za-z0-9!#$%&'*+=?^_`{|}~-][A-Za-z0-9!#$%&↪'*+=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
		      return false;
		    }
		  }
		  // Check if domain is IP. If not, 
		  // it should be valid domain name
		  if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
		    $domain_array = explode(".", $email_array[1]);
		    if (sizeof($domain_array) < 2) {
		        return false; // Not enough parts to domain
		    }
		    for ($i = 0; $i < sizeof($domain_array); $i++) {
		      if(!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|↪([A-Za-z0-9]+))$/",$domain_array[$i])) {
		        return false;
		      }
		    }
		  }
		  return true;
	}
	

	
	

	
	/**
	 * Checks if user is logged in.
	 * @param int
	 * @return bool
	 */
	public static function isLoggedIn($userId)
	{			
		$database = JFactory::getDBO();
		$query = "
			SELECT 
				*
			FROM 
				#__session
			WHERE 1
			AND 
				`userid` = $userId
			LIMIT 1
		";
		$database->setQuery($query);
		$result = $database->loadObject();
		if ($result) {
			return true;
		}
		else
			return false;
	}
	
	
	/**
	 * Gets user id from an email 
	 * @param string
	 * @return int
	 */
	public static function getUserIdFromEmail($email)
	{
        // Initialise some variables
        $db = & JFactory::getDbo();
 
        $query = 'SELECT id FROM #__users WHERE email = ' . $db->Quote($email);
        $db->setQuery($query, 0, 1);
        return $db->loadResult();
	}
}