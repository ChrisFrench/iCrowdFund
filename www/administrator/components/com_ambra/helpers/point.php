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

class AmbraHelperPoint extends AmbraHelperBase
{
    /**
     * Creates a pointhistory log entry 
     * if the user parameters allow for it 
     * 
     * @param int $user_id  Valid user id number
     * @param str $scope    Generally the com_whatever
     * @param str $event    the event name as set in _pointhistory
     * @return true if OK, false if fail, null if no action; all with a message in the error object 
     */
    function createLogEntry( $user_id, $scope, $event )
    {
    	
            
        // is user_id valid?
        if (empty(JFactory::getUser( $user_id )->id ))
        {   
            $this->setError( "Invalid User" );
            return false;
        }
       
        // TODO is scope + event pairing valid?
        
        // get the user's userdata
        jimport( 'joomla.application.component.model' );
        JTable::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/tables' );
        
        
        $max_points = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getMaxPoints( JFactory::getUser( $user_id )->id );
         
        JModel::addIncludePath( JPATH_ADMINISTRATOR.'/components/com_ambra/models' );
        $model = JModel::getInstance('Users', 'AmbraModel');
        $model->setId( $user_id );
       
        $manual_approval = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getManualApproval($user_id);
        $userdata = $model->getItem();
        FB::log($userdata);
        // has the user equaled or exceeded their lifetime max?
        $max_points = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getMaxPoints( JFactory::getUser( $user_id )->id );
        if ($max_points != '-1' && $userdata->points_total > $max_points)
        {         die("here");
            $this->setError( JText::_( "User Exceeded Max Points" ) );
            return false;            
        } 
                
        // has the user equaled or exceeded their daily max?
        $pointhistory_today = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getTodayPoints( JFactory::getUser( $user_id )->id );
        $max_points_per_day = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->getMaxPointsPerDay( JFactory::getUser( $user_id )->id );
        if ($max_points_per_day != '-1' && $pointhistory_today > $max_points_per_day)
        { 
            $this->setError( JText::_( "User Exceeded Max Points for the Day" ) );
            return false;            
        }         
        
        // get the enabled, not expired pointrules for this scope + event where profile_id = '0' (all profiles) OR profile_id = this user's profile
        // (by using filter_pointprofile instead of filter_profile)
        $today = Ambra::getClass( "AmbraHelperBase", "helpers._base" )->getToday();
        $model = JModel::getInstance('PointRules', 'AmbraModel');
        $model->setState( 'filter_enabled', 1 );
        $model->setState( 'filter_datetype', 'expires' );
        $model->setState( 'filter_date_from', $today );
        $model->setState( 'filter_scope', $scope );
        $model->setState( 'filter_event', $event );
        $model->setState( 'filter_pointprofile', $userdata->profile_id );
       
        if (!$pointrules = $model->getList())
        { 
            $this->setError( JText::_( 'No Valid Points Found for this Event' ) );
            return false;
        }
        
        // foreach pointrule
        $ruleHelper = Ambra::getClass( "AmbraHelperRule", "helpers.rule" );
        $errors = array(); // track errors
        $points = 0;
        foreach ($pointrules as $pointrule)
        { 
            // has the pointrule equaled or exceeded its max uses?
            if ($pointrule->pointrule_uses_max > '-1' && $pointrule->pointrule_uses >= $pointrule->pointrule_uses_max)
            {
                // skip it
                continue;
            }
            
            // has the user equaled or exceeded the pointrule's user-limits (total & per day)?
            $user_uses = $ruleHelper->getUses( $pointrule->pointrule_id, $user_id, 'total' );
            if ($user_uses >= $pointrule->pointrule_uses_per_user && $pointrule->pointrule_uses_per_user > '-1')
            {
                // skip it
                continue;
            }

            $user_uses_today = $ruleHelper->getUses( $pointrule->pointrule_id, $user_id, 'today' );
            if ($user_uses_today >= $pointrule->pointrule_uses_per_user_per_day && $pointrule->pointrule_uses_per_user_per_day > '-1')
            {
                // skip it
                continue;
            }

    
            // if here, all OK
            // create a pointhistory table object
            $pointhistory = JTable::getInstance('PointHistory', 'AmbraTable');
           
            // set properties
            $pointhistory->user_id = $user_id;
            $pointhistory->pointrule_id = $pointrule->pointrule_id;
            $pointhistory->points = $pointrule->pointrule_value;
            $pointhistory->points_updated = 0;
            $expirationperiod = Ambra::getInstance()->get('expirationperiod', '');
            $expirationperiod = (int)$expirationperiod;
            $orgDate = date('Y-m-d');
            $cd = strtotime($orgDate);
			$retDate = date('Y-m-d', mktime(0,0,0,date('m',$cd)+$expirationperiod,date('d',$cd),date('Y',$cd)));
			$pointhistory->expire_date=$retDate;
            if ($pointrule->pointrule_auto_approve == 1)
            {
                // enable pointhistory
                $pointhistory->pointhistory_enabled = 1;
            }
                else
            {
            	if (empty($manual_approval))
            	{
                	$pointhistory->pointhistory_enabled = 1;
            	}
            }
            FB::log($pointhistory);
           
            // TODO When do points expire?
            
            // save it and move on
            if (!$pointhistory->save())
            {
                $errors[] = $pointhistory->getError(); 
            }
                else
            {
            	
                // track the number of points?
                $event = $pointrule->pointrule_name;
                $points = $points + $pointhistory->points;
               
            }
        }
        
        if (!empty($errors))
        
        {
            $this->setError( implode( '<br/>', $errors ) );
            return false;
        }

        
        if (empty($errors) && !empty($points))
        {
        	
     
            if ($points == '1') { $string = 'point'; } else { $string = 'points'; }
            $lang = JFactory::getLanguage();
            $lang->load( 'com_ambra', JPATH_ADMINISTRATOR );
            $login_note		= Ambra::getInstance()->get('login_point_notification', '');
            $avatar_note	= Ambra::getInstance()->get('avatar_point_notification', '');
            $affiliate_note	= Ambra::getInstance()->get('affiliate_point_notification', '');
            $productcomment_point_notification	= Ambra::getInstance()->get('productcomment_point_notification', '');
            $purchase_point_notification	= Ambra::getInstance()->get('purchase_point_notification', '');
            if(($login_note && $event=="Logging In")||($avatar_note && $event=="Uploading an Avatar")||($affiliate_note && $event=="Becoming an Affiliate")||($productcomment_point_notification && $event=="Leaving Comments on product")||($purchase_point_notification &&  $event=="doCompletedOrderTasks" ))
            {
            
            	
            $this->setError( sprintf( JText::_( "You have been awarded x $string for x" ), $points, $event ) );
           return true;
            }
            else
            {
            	return false;
            }
        }
        
        // shouldn't end up here
        $this->setError( JText::_( 'Something Wrong Happened' ) );
        return false;   
    }
    
    /**
     * Gets the full URL to the article describing the program
     * 
     * @return boolean
     */
    function getArticle()
    {
        if (!$article_points = Ambra::getInstance()->get('article_points')) 
        {
            return false;
        }
        
        $article_points_suffix = "";
        if ($article_points_itemid = Ambra::getInstance()->get('article_points_itemid')) 
        {
            $article_points_suffix = "&Itemid=".$article_points_itemid;
        }
        
        $url = "index.php?option=com_content&view=article&id=".$article_points.$article_points_suffix;
        return $url;
    }
}