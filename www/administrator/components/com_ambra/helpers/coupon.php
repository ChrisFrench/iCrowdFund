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

class AmbraHelperCoupon extends AmbraHelperBase
{
    /**
     * Creates a pointhistory log entry 
     * for a coupon
     * if the user parameters allow for it 
     * 
     * @param int $user_id          Valid user id number
     * @param int $pointcoupon_id   Coupon ID   
     * @return true if OK, false if fail, null if no action; all with a message in the error object 
     */
    function createLogEntry( $user_id, $pointcoupon_id )
    {
        // is user_id valid?
        if (empty(JFactory::getUser( $user_id )->id ))
        {
            $this->setError( "Invalid User" );
            return false;
        }
        
        // get the user's userdata
        jimport( 'joomla.application.component.model' );
        JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'tables' );
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
        $model = JModel::getInstance('Users', 'AmbraModel');
        $model->setId( $user_id );
        $userdata = $model->getItem();
        
        // has the user equaled or exceeded their lifetime max?
        $max_points = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getMaxPoints( JFactory::getUser( $user_id )->id );
        if ($max_points != '-1' && $userdata->points_total > $max_points)
        {
            $this->setError( "User Exceeded Max Points" );
            return false;            
        } 
                
        // has the user equaled or exceeded their daily max?
        $pointhistory_today = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getTodayPoints( JFactory::getUser( $user_id )->id );
        $max_points_per_day = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getMaxPointsPerDay( JFactory::getUser( $user_id )->id );
        if ($max_points_per_day != '-1' && $pointhistory_today > $max_points_per_day)
        {
            $this->setError( "User Exceeded Max Points for the Day" );
            return false;            
        }         

        $model  = Ambra::getClass( "AmbraModelPointCoupons", 'models.pointcoupons' );
        $pointcoupon = $model->getTable();
        $pointcoupon->load( array( 'pointcoupon_id'=>$pointcoupon_id ) );
        
        // does coupon code even exist
        if (empty($pointcoupon->pointcoupon_id))
        {
            $this->setError( JText::_("Code Does Not Exist") );
            return false;
        }

        // begin checking the coupon's uses
        $errors = array(); // track errors
        $points = 0;
        
        // has the pointcoupon equaled or exceeded its max uses?
        if ($pointcoupon->pointcoupon_uses_max > '-1' && $pointcoupon->pointcoupon_uses >= $pointcoupon->pointcoupon_uses_max)
        {
            $this->setError( JText::_("Code Exceeds Max Uses") );
            return false;
        }
        
        // has the user equaled or exceeded the pointcoupon's user-limits (total & per day)?
        $user_uses = $this->getUses( $pointcoupon->pointcoupon_id, $user_id, 'total' );
        if ($user_uses >= $pointcoupon->pointcoupon_uses_per_user && $pointcoupon->pointcoupon_uses_per_user > '-1')
        {
            $this->setError( JText::_("Max Code Uses Reached") );
            return false;
        }

        $user_uses_today = $this->getUses( $pointcoupon->pointcoupon_id, $user_id, 'today' );
        if ($user_uses_today >= $pointcoupon->pointcoupon_uses_per_user_per_day && $pointcoupon->pointcoupon_uses_per_user_per_day > '-1')
        {
            $this->setError( JText::_("Max Code Uses Per Day Reached") );
            return false;
        }
                    
        // if here, all OK
        // create a pointhistory table object
        $pointhistory = JTable::getInstance('PointHistory', 'AmbraTable');
        // set properties
        $pointhistory->user_id = $user_id;
        $pointhistory->pointcoupon_id = $pointcoupon->pointcoupon_id;
        $pointhistory->points = $pointcoupon->pointcoupon_value;
        $pointhistory->points_updated = 0;
        $pointhistory->pointhistory_enabled = 1;
        $pointhistory->pointhistory_name = JText::_( "Using a coupon" );
        
        // TODO When do points expire?
        
        // save it and move on
        if (!$pointhistory->save())
        {
            $errors[] = $pointhistory->getError(); 
        }
            else
        {
            // track the number of points?
            $points = $points + $pointhistory->points;
            $event = $pointhistory->pointhistory_name;
        }
        
        /************************************************
         * YOU END HERE
         */
        
        if (!empty($errors))
        {
            $this->setError( implode( '<br/>', $errors ) );
            return false;
        }
        
        if (empty($errors) && !empty($points))
        {
            if ($points == '1') { $string = 'point'; } else { $string = 'points'; }
            $lang =& JFactory::getLanguage();
            $lang->load( 'com_ambra', JPATH_ADMINISTRATOR );
            $this->setError( sprintf( JText::_( "You have been awarded x $string for x" ), $points, $event ) );
            return true;
        }
        
        // shouldn't end up here
        $this->setError( JText::_( 'Something Wrong Happened' ) );
        return false;   
    }
    
    /**
     * Gets the number of uses for a pointcoupon
     * 
     * @param $pointcoupon_id
     * @param $user_id
     * @param $type
     * @return unknown_type
     */
    function getUses( $pointcoupon_id, $user_id=null, $type='total' )
    {
        JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ambra'.DS.'models' );
        $model = JModel::getInstance('PointHistory', 'AmbraModel');
        $model->setState( 'filter_enabled', 1 );
        $model->setState( 'filter_coupon', $pointcoupon_id );
        switch ($type)
        {
            case "today":
                $today = Ambra::getClass( "AmbraHelperBase", "helpers._base" )->getToday();
                $model->setState( 'filter_date_from', $today );
                break;
            default:
                break;
        }
        
        if (!empty($user_id))
        {
            $model->setState( 'filter_user', $user_id );
        }
        
        $count = $model->getTotal();
        return $count;
    }
}