<?php
/**
 * @version 1.5
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.event.plugin');

class plgContentAmbra extends JPlugin 
{
    /**
     * Constructor
     *
     * For php4 compatability we must not use the __constructor as a constructor for plugins
     * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
     * This causes problems with cross-referencing necessary for the observer design pattern.
     *
     * @param object $subject The object to observe
     * @param   array  $config  An array that holds the plugin configuration
     * @since 1.5
     */
    function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
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
     * @param   object      The article object.  Note $article->text is also available
     * @param   object      The article params
     * @param   int         The 'page' number
     */
    function onPrepareContent( &$article, &$params, $limitstart )
    {
        $success = true;
        
        if (!$this->_isInstalled())
        {
            return $success;    
        }
        
        if (!$this->isIncluded($article))
        {
            return $success;
        }
        
        if (!$this->params->get( 'onpreparecontent' ))
        {
            return $success;
        }
        
        if ($avatar = $this->getUserAvatar( $article->created_by )) 
        {
            if ($this->params->get( 'link_to_profile', '1' ))
            {
                $itemid = $this->params->get( 'itemid' );
                $link = JRoute::_( "index.php?option=com_ambra&view=users&id={$article->created_by}&Itemid={$itemid}", false );
                $html = "<a href='{$link}' style='float: right; display: block;'>{$avatar}</a>";
            }
                else
            {
                $html = "<div style='float: right; display: block;'>{$avatar}</div>";
            }
            $article->text = $html.$article->text;
        }

        return $success;
    }
    
    /**
     * Ambra after display title method
     *
     * Method is called by the view and the results are imploded and displayed in a placeholder
     *
     * @param   object      The article object.  Note $article->text is also available
     * @param   object      The article params
     * @param   int         The 'page' number
     * @return  string
     */
    function onAfterDisplayTitle( &$article, &$params, $limitstart )
    {
        $success = null;
        
        if (!$this->_isInstalled())
        {
            return $success;    
        }
        
        if (!$this->isIncluded($article))
        {
            return $success;
        }
        
        if (!$this->params->get( 'onafterdisplaytitle' ))
        {
            return $success;
        }
        
        $avatar = false;
        if (!empty($article->created_by))
        {
            $avatar = $this->getUserAvatar( $article->created_by );
        }
        
        if ($avatar)
        {
            if ($this->params->get( 'link_to_profile', '1' ))
            {
                $itemid = $this->params->get( 'itemid' );
                $link = JRoute::_( "index.php?option=com_ambra&view=users&id={$article->created_by}&Itemid={$itemid}", false );
                $html = "<a href='{$link}' style='float: right; display: inline; margin-top: -24px;'>{$avatar}</a>";
            }
                else
            {
                $html = "<div style='float: right; display: inline; margin-top: -24px;'>{$avatar}</div>";
            }
            $success = $html;
        }

        return $success;
    }

    /**
     * Determines if this article is included in params
     * @param $article
     * @return unknown_type
     */
    function isIncluded( $article )
    {
        $result = false;
        
        // Something conflicted on one user's French site.  Not sure what, but this fixed it
        $plugin = JPluginHelper::getPlugin('content', 'ambra');
        $pluginParams = new DSCParameter( $plugin->params );
        $this->params = $pluginParams;
        
        $sections_string = trim( $this->params->get('sections') );
        $sections_array = explode(",", $sections_string);
        $categories_string = trim( $this->params->get('categories') );
        $categories_array = explode(",", $categories_string);
        
        // check article's section and category
        if (!empty($article->sectionid) && in_array($article->sectionid, $sections_array))
        {
            $result = true;
        }
        if (!empty($article->catid) && in_array($article->catid, $categories_array))
        {
            $result = true;
        }
        
        return $result;
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
            $success = "<img src='{$pic}' style='max-width:48px; padding-right: 3px;'>";
            return $success;
        }
        
        return $success;
    }
    
}
