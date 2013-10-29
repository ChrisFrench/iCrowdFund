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

Ambra::load( 'AmbraHelperBase', 'helpers._base' );

class AmbraHelperProfile extends AmbraHelperBase
{
    /**
     * Gets the maximum number of points a user is allowed per day
     * 
     * @param unknown_type $user_id
     * @return unknown_type
     */
    function getMaxPointsPerDay( $profile_id )
    {
        static $profiles;
        
        if (empty($profiles) || !is_array($profiles))
        {
            $profiles = array();
        }
        
        if (empty($profiles[$profile_id]))
        {
            
            JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
            $profile = JTable::getInstance('Profiles', 'AmbraTable');
            $profile->load( array( 'profile_id'=>$profile_id ) );
            //as set at profile level
            if (is_numeric($profile->profile_max_points_per_day))
            {
                $profiles[$profile_id] = $profile->profile_max_points_per_day;
                return $profiles[$profile_id];
            }
            
            // system-wide, based on max_daily_points
            $profiles[$profile_id] = AmbraConfig::getInstance()->get('max_daily_points');
        }
        
        return $profiles[$profile_id];   
    }
    
    /**
     * 
     * @param $id
     * @param $by
     * @param $alt
     * @param $type
     * @param $url
     * @return unknown_type
     */
    function getImage( $id, $options = array( 'alt'=>'', 'width'=>'48px', 'height'=>'48px', 'url'=>false ) )
    {
        jimport('joomla.filesystem.file');
        
        $path = 'images';
        $url = @$options['url'];  
        
        $tmpl = "";
        if (strpos($id, '.'))
        {
            // then this is a filename, return the full img tag if file exists, otherwise use a default image
            $src = (JFile::exists( Ambra::getPath( $path ).DS.$id))
                ? Ambra::getUrl( $path ).$id : JURI::root(true).'/media/com_ambra/images/profiles.png';

            $alt = (!empty($options['alt'])) ? $options['alt'] : 'Profile';
             
            // if url is true, just return the url of the file and not the whole img tag
            $tmpl = ($url)
                ? $src : "<img style='max-width: ".$options['width']."; max-height: ".$options['height'].";' src='".$src."' alt='".JText::_( $alt )."' title='".JText::_( $alt )."' name='".JText::_( $alt )."' align='center' border='0' />";

        }
            else
        {
            if (!empty($id))
            {
                // load the item, get the filename, create tmpl
                JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
                $row = JTable::getInstance('Profiles', 'AmbraTable');
                $row->load( (int) $id );

                if (empty($row->profile_id))
                {
                    $id = 'profiles.png';
                } 
                    else
                {
                    $id = $row->profile_img;    
                }
                
                // then this is a filename, return the full img tag if file exists, otherwise use a default image
                $src = (JFile::exists( Ambra::getPath( $path ).DS.$id))
                    ? Ambra::getUrl( $path ).$id : JURI::root(true).'/media/com_ambra/images/profiles.png';
    
                $alt = (!empty($options['alt'])) ? $options['alt'] : 'Profile';
                 
                // if url is true, just return the url of the file and not the whole img tag
                $tmpl = ($url)
                    ? $src : "<img style='max-width: ".$options['width']."; max-height: ".$options['height'].";' src='".$src."' alt='".JText::_( $alt )."' title='".JText::_( $alt )."' name='".JText::_( $alt )."' align='center' border='0' />";
            }           
        }
        return $tmpl;
    }
}