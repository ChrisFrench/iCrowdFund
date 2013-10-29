<?php
/**
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgUserAmbra extends JPlugin
{
    function plgUserAmbra(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage( 'com_ambra', JPATH_BASE );
        $this->loadLanguage( 'com_ambra', JPATH_ADMINISTRATOR );
    }

    /**
     *
     * @return unknown_type
     */
    function _isInstalled()
    {
        $success = false;

        jimport('joomla.filesystem.file');
        if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'defines.php'))
        {
            $success = true;
            JLoader::register('Ambra', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'defines.php'); 
	        Ambra::load( 'AmbraConfig', 'defines' );
	        Ambra::load( 'AmbraQuery', 'library.query' );
	        Ambra::load( 'AmbraHelperBase', 'helpers._base' );
            JLoader::register('AmbraHelperUser', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'helpers'.DS.'user.php');
        }

        return $success;
    }

    /**
     * Check if Amigos is installed
     *
     * @return unknown_type
     */
    function _isInstalledAmigos()
    {
        $success = false;

        jimport( 'joomla.filesystem.file' );
        $filePath = JPATH_ADMINISTRATOR.DS."components".DS."com_amigos".DS."defines.php";
        if (JFile::exists($filePath))
        {
            $success = true;
            if ( !class_exists('Amigos') )
            {
                JLoader::register( "Amigos", JPATH_ADMINISTRATOR.DS."components".DS."com_amigos".DS."defines.php" );
            }
        }
        return $success;
    }

    /**
     * This method should handle any login logic and report back to the subject
     *
     * @access  public
     * @param   array   holds the user data
     * @param   array    extra options
     * @return  boolean True on success
     * @since   1.5
     */
    function onLoginUser($user, $options)
    {
        $success = null;
        if (!$this->_isInstalled())
        {
            return $success;
        }

        // assign the userid to user['id'] (onLogin doesn't populate this field in the array)
        $user['id'] = intval( JUserHelper::getUserId( $user['username'] ) );

        $user_id = $user['id'];
		$db = JFactory::getDBO();

		//if there is no default profile then we will put the default profile set in the plugin
		$db->setQuery( "SELECT tbl.profile_id  FROM #__ambra_userdata AS tbl WHERE tbl.user_id  = '$user_id' " );
        $profile_id = $db->loadResult();

		if( !$profile_id ){
			$profile_id = $this->params->get( 'profile' );
			$db->setQuery( "UPDATE #__ambra_userdata as tbl SET tbl.profile_id = '$profile_id' WHERE tbl.user_id  = '$user_id' " );
			$db->query();
        }


        // create the point helper object
        $helper = AmbraHelperBase::getInstance('Point');

        // give the user points for logging in
        if ($helper->createLogEntry( $user['id'], 'com_user', 'onLoginUser' ))
        {
            JFactory::getApplication()->enqueueMessage( $helper->getError() );
        }

        // is the user an affiliate in Amigos?  if so, check if they've been awarded points for being one
        if ($this->_isInstalledAmigos())
        {
            if (Ambra::getClass( "AmbraHelperAmigos", 'helpers.amigos' )->isAffiliate( $user['id'] ))
            {
                if ($helper->createLogEntry( $user['id'], 'com_amigos', 'onAfterSaveAccounts' ))
                {
                    JFactory::getApplication()->enqueueMessage( $helper->getError() );
                }
            }
        }

        // does the user have an avatar?  if so, check they've been awarded points for uploading it
        if ( !class_exists('AmbraHelperUser') )
        {
        	JLoader::import( 'com_ambra.helpers.user', JPATH_ADMINISTRATOR.DS.'components' );
        }
        
        if ($avatar = AmbraHelperUser::getAvatarFilename( $user['id'] ))
        {
            if ($helper->createLogEntry( $user['id'], 'com_ambra', 'onAfterUploadAvatar' ))
            {
                JFactory::getApplication()->enqueueMessage( $helper->getError() );
            }
        }

        return $success;
    }
}
