<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgContentAmbraProfile extends JPlugin 
{	
    /**
     * Check if is installed
     * 
     * @return unknown_type
     */
    function _isInstalled()
    {
        $success = false;

        jimport( 'joomla.filesystem.file' );
        $filePath = JPATH_ADMINISTRATOR."/components/com_ambra/defines.php";
        if (JFile::exists($filePath))
        {
            $success = true;
            if ( !class_exists('Ambra') )
            { 
                JLoader::register('Ambra', JPATH_ADMINISTRATOR.'/components/com_ambra/defines.php');
            }
        }           
        return $success;
    }
	
	/**
	 * Ambra prepare content method
	 *
	 * Method is called by the view
	 *
	 * @param 	object		The article object.  Note $article->text is also available
	 * @param 	object		The article params
	 * @param 	int			The 'page' number
	 */
	function onContentPrepare( $context, &$article, &$params, $limitstart )
	{
        // Check whether the plugin should process or not
        if ( JString::strpos( $article->text, 'ambraprofile' ) === false )
        {
            return true;
        }

        // Search for this tag in the content
        $regex = "#{ambraprofile(.*?)}(.*?){/ambraprofile}#s";
            // regex returns this array:
            // $match[0] = {ambraprofile JUGA6 || !MANGA6} This is the text to show if in JUGA6 or NOT in MANGA6 {/ambraprofile}
            // $match[1] = JUGA6 || !MANGA6
            // $match[2] = This is the text to show if in JUGA6 or NOT in MANGA6
        
        // process the article text
        $article->text = preg_replace_callback( $regex, array('plgContentAmbraProfile', 'process'), $article->text );
	}
	
    /**
     * Callback method for processing the plugin tags 
     * @param $match
     */
    function process( $match ) 
    {
        $return = '';
        
        // regex returns this array:
        // $match[0] = {juga JUGA6 || !MANGA6} This is the text to show if in JUGA6 or NOT in MANGA6 {/juga}
        // $match[1] = JUGA6 || !MANGA6
        // $match[2] = This is the text to show if in JUGA6 or NOT in MANGA6

        // if processing match[1] returns true, then return = $match[2]
        // else return = null
        if ( $this->analyze( $match[1] ) ) {
            $return = $match[2];
        }

        return $return;
    }
    
    /**
     * Method for analyzing an entire statement inside a tag
     * @param $statement
     */
    function analyze( $statement )
    {
        $return = false;
        
        // regex returns this array:
        // $match[0] = {juga JUGA6 || !MANGA6} This is the text to show if in JUGA6 or NOT in MANGA6 {/juga}
        // $match[1] = JUGA6 || !MANGA6
        // $match[2] = This is the text to show if in JUGA6 or NOT in MANGA6
        
        // first, explode by ||
        // using a for loop, check if any isTrue
        // when encountering any isTrue, break for loop and return true

        if ($statement) 
        {
            $items = explode("||", trim($statement) );
            for ($i=0; $i<count($items) && !$return; $i++) {
                $item = $items[$i];
                $return = $this->isTrue( $item );
            }
        }
        
        return $return;
    }
    
    /**
     * Method for analyzing an individual statement from a tag
     * @param $item
     */
    function isTrue( $item )
    {
        $return = false;
        $results = array();
        $user = JFactory::getUser();

        // explode by &&
        // html_entity_decode() will also handle &amp;&amp; that was noticed in 
        // J! version 1.5.17 and on 
        $values = explode("&&", trim (html_entity_decode ( $item ) ) );
        
        // for each, check if user is/is-not in group (accounting for !JUGA6)
        for ($i=0; $i<count($values); $i++) 
        {
            $value = $values[$i];
            $negoffset = strpos($value, '!');
            
            // store each result in the array $results
            // by default, result for this one is false
            $results[$i] = false;
            // if no file exists, move on and let this remain = false
            // First check that Juga is installed, and if not, then return
            if ( !JFile::exists( JPATH_ADMINISTRATOR.'/components/com_ambra/defines.php' ) ) {
                break;
            }
        
            if ($negoffset === false) 
            {
                // no "!" found, check if the user is a member of this group ($value), if so, result=true
                $typeid = $this->getId( $value );
                if ($typeid == $this->getProfileId($user->id))
                {
                    $results[$i] = true;
                }
            } 
                else 
            {
                // "!" found, check if the user is a member of this group($value), and if so, result=false
                $results[$i] = true;
                // trim value of the !
                $value = substr($value, $negoffset+1);
                echo "value: ".$value."<br/>";
                $typeid = $this->getId( $value );
                // if user is in group and value has ! before it (e.g. !MANGA6) then result for this one = false, otherwise true
                if ($typeid == $this->getProfileId($user->id))
                {
                    $results[$i] = false;
                }
            }
        }
        
        // if all results are true, return true
        if (!in_array(false, $results, true)) {
            $return = true;
        }
        
        return $return;
    }
	
    /**
     * Returns a type id
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
    function getId( $value ) 
    {
        static $items;
        
        if (!is_numeric($value)) 
        {
            $id = intval( $this->getIdFromTitle( $value ) );
        } 
          else 
        {
            $id = intval($value);
        }
        
        if (empty($items[$id])) 
        {
            $items[$id] = $id;
        }

        return $items[$id];
    }
        
    /**
     * Returns a type id
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
    function getIdFromTitle( $title ) 
    {
        $database = JFactory::getDBO();
        
        $title = trim( strtolower($title) );
        
        $query = "
            SELECT
                db.profile_id
            FROM
                #__ambra_profiles AS db
            WHERE 
                LOWER( profile_name ) = '$title'
            LIMIT 1
        ";

        $database->setQuery( $query );
        $data = $database->loadResult();
        return $data;
    }
    
    /**
     * Returns a type id
     * @param mixed Boolean
     * @param mixed Boolean
     * @return array
     */
    function getProfileId( $user_id ) 
    {
        $database = JFactory::getDBO();
        
        $query = "
            SELECT
                db.profile_id
            FROM
                #__ambra_userdata AS db
            WHERE 
                db.user_id = '$user_id'
            LIMIT 1
        ";

        $database->setQuery( $query );
        if ($data = $database->loadResult())
        {
            return $data;    
        }
        return 0;
        
    }
}
