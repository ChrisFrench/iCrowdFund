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


Billets::load( 'BilletsPluginBase', 'library.plugin.base' );

/**
 * Billets Plugin
 *
 */
class plgBilletsAvatarAmbra extends BilletsPluginBase {


	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}
	
    /**
     * Check if is installed
     * 
     * @return unknown_type
     */
    function _isInstalled()
    {
        $success = false;

        jimport( 'joomla.filesystem.file' );
        $filePath = JPATH_ADMINISTRATOR.DS."components".DS."com_ambra".DS."defines.php";
        if (JFile::exists($filePath))
        {
            $success = true;
            if ( !class_exists('Ambra') )
            { 
                JLoader::register( "Ambra", JPATH_ADMINISTRATOR.DS."components".DS."com_ambra".DS."defines.php" );
            }
        }           
        return $success;
    }

	/**
	 * Method is called 
	 * before displaying a comment.
	 * Note: $comment->authorimage is available, and is already set to the default
	 * 
	 * @return 
	 * @param $row Object
	 * @param $body Object
	 * @param $user Object
	 * @param $args Object
	 */
	function onBeforeDisplayCommentAuthorImage( $comment, $data, $user ) 
	{
		$success = true;
		
		if (empty($comment->userid_from))
		{
			return $success;
		}
		
        if (!$this->_isInstalled())
        {
            return $success;    
        }
				
		if ($avatar = $this->getUserAvatar( $comment->userid_from )) {
			$comment->authorimage = $avatar;
		}

		if ($this->params->get( 'link_to_profile', '1' ))
		{
		    $app = JFactory::getApplication();
            if ($app->isAdmin())
            {
                $link = JRoute::_( "index.php?option=com_ambra&view=users&id={$comment->userid_from}", false );
                $html = "<a href='{$link}'>{$comment->authorimage}</a>";
                $comment->authorimage = $html;
            }
                else
            {
                global $Itemid;
                $link = JRoute::_( "index.php?option=com_ambra&view=users&id={$comment->userid_from}&Itemid={$Itemid}", false );
                $html = "<a href='{$link}'>{$comment->authorimage}</a>";
                $comment->authorimage = $html;
            }
		}
		
		return $success;
	}
	
	/**
	 * Returns a user's avatar
	 * @param $userBeingViewed
	 * @return unknown_type
	 */
	function getUserAvatar( $userid ) 
	{
		$success = false;
		if (!$userid) 
		{
			return $success;
		}

	    if ( !Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatarFilename( $userid ) ) 
        {
            return $success;
        }
		
	    if ( $pic = Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatar( $userid ) ) 
        {
            $success = "<img src='{$pic}' style='max-width:48px;'>";
            return $success;
        }
		
		return $success;
	}

}
