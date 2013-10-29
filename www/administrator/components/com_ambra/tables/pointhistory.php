<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Ambra::load( 'AmbraTable', 'tables._base' );

class AmbraTablePointHistory extends AmbraTable 
{
	function AmbraTablePointHistory ( &$db ) 
	{
		$tbl_key 	= 'pointhistory_id';
		$tbl_suffix = 'pointhistory';
		$this->set( '_suffix', $tbl_suffix );
		$name 		= 'ambra';
		
		parent::__construct( "#__{$name}_{$tbl_suffix}", $tbl_key, $db );	
	}
	
	function check()
	{
        $nullDate = $this->_db->getNullDate();
        $helper = Ambra::getClass( "AmbraHelperBase", 'helpers._base' );
        $this->created_date = ($this->created_date != $nullDate) ? $helper->getOffsetDate( $this->created_date ) : $this->created_date;
        $this->expire_date  = ($this->expire_date != $nullDate) ? $helper->getOffsetDate( $this->expire_date ) : $this->expire_date;
		
		return true;
	}
	
	function save($isNew=false)
	{
	    if ($save = parent::save())
	    {
	        if (
	           (($isNew && $this->pointhistory_enabled) || (!$isNew && $this->pointhistory_enabled)) 
	           && empty($this->points_updated)
	           )
	        {
                // add the points to the total if they are enabled in the history
                $userdata = JTable::getInstance( 'Userdata', 'AmbraTable' );
                $userdata->load( array( 'user_id' => $this->user_id ) );
                $userdata->user_id = $this->user_id;
                $userdata->points_current = $userdata->points_current + $this->points;
                $userdata->points_total = $userdata->points_total + $this->points;
                if (!$userdata->save())
                {
                    $this->setError( $userdata->getError() );
                }
                    else
                {
                    $this->points_updated = '1';
                    parent::save();
                }

                // if this was a pointrule,
                if (!empty($this->pointrule_id))
                {
                    // do the same thing for the pointrule, which needs its counts increased 
                    $pointrule = JTable::getInstance( 'PointRules', 'AmbraTable' );
                    $pointrule->load( array( 'pointrule_id' => $this->pointrule_id ) );
                    if (!empty($pointrule->pointrule_id))
                    {
                        $pointrule->pointrule_uses = $pointrule->pointrule_uses + 1;
                        if (!$pointrule->save())
                        {
                            $this->setError( $pointrule->getError() );
                        }                    
                    }                    
                }
                
	            // if this was a pointcoupon,
                if (!empty($this->pointcoupon_id))
                {
                    // do the same thing for the pointcoupon, which needs its counts increased 
                    $pointcoupon = JTable::getInstance( 'PointCoupons', 'AmbraTable' );
                    $pointcoupon->load( array( 'pointcoupon_id' => $this->pointcoupon_id ) );
                    if (!empty($pointcoupon->pointcoupon_id))
                    {
                        $pointcoupon->pointcoupon_uses = $pointcoupon->pointcoupon_uses + 1;
                        if (!$pointcoupon->save())
                        {
                            $this->setError( $pointcoupon->getError() );
                        }                    
                    }                    
                }

	        }
	            elseif ( !$isNew && !$this->pointhistory_enabled && !empty($this->points_updated) )
	        {
	            // remove the points from the total if they are disabled in the history
	            $userdata = JTable::getInstance( 'Userdata', 'AmbraTable' );
                $userdata->load( array( 'user_id' => $this->user_id ) );
                $userdata->user_id = $this->user_id;
                $userdata->points_current = $userdata->points_current - $this->points;
                $userdata->points_total = $userdata->points_total - $this->points;
                if (!$userdata->save())
                {
                    $this->setError( $userdata->getError() );
                }
                    else
                {
                    $this->points_updated = '0';
                    parent::save();
                }
                
                // if this was a pointrule,
                if (!empty($this->pointrule_id))
                {
                    // do the same thing for the pointrule, which needs its counts decreased
                    $pointrule = JTable::getInstance( 'PointRules', 'AmbraTable' );
                    $pointrule->load( array( 'pointrule_id' => $this->pointrule_id ) );
                    if (!empty($pointrule->pointrule_id))
                    {
                        $pointrule->pointrule_uses = $pointrule->pointrule_uses - 1;
                        if (!$pointrule->save())
                        {
                            $this->setError( $pointrule->getError() );
                        }                    
                    }                    
                }
                
	            // if this was a pointcoupon,
                if (!empty($this->pointcoupon_id))
                {
                    // do the same thing for the pointcoupon, which needs its counts decreased
                    $pointcoupon = JTable::getInstance( 'PointCoupons', 'AmbraTable' );
                    $pointcoupon->load( array( 'pointcoupon_id' => $this->pointcoupon_id ) );
                    if (!empty($pointcoupon->pointcoupon_id))
                    {
                        $pointcoupon->pointcoupon_uses = $pointcoupon->pointcoupon_uses - 1;
                        if (!$pointcoupon->save())
                        {
                            $this->setError( $pointcoupon->getError() );
                        }                    
                    }                    
                }
	        }
	    }
	    return $save;
	}
	
    /**
     * 
     * @param $oid
     * @return unknown_type
     */
    function delete( $oid=null )
    {
        // if this ph record changed the user points, then change them back
        // and decrease the number of uses for the coupon/rule
        $this->load( $oid );
        if (!empty($this->points_updated))
        {
            // this ph record cause a point change for the user
            // so deduct their calue from the users total
            $userdata = JTable::getInstance( 'Userdata', 'AmbraTable' );
            $userdata->load( array( 'user_id' => $this->user_id ) );
            $userdata->user_id = $this->user_id;
            $userdata->points_current = $userdata->points_current - $this->points;
            $userdata->points_total = $userdata->points_total - $this->points;
            if (!$userdata->save())
            {
                $this->setError( $userdata->getError() );
            }
            
            // if this was a pointrule,
            if (!empty($this->pointrule_id))
            {
                // do the same thing for the pointrule, which needs its counts decreased
                $pointrule = JTable::getInstance( 'PointRules', 'AmbraTable' );
                $pointrule->load( array( 'pointrule_id' => $this->pointrule_id ) );
                if (!empty($pointrule->pointrule_id))
                {
                    $pointrule->pointrule_uses = $pointrule->pointrule_uses - 1;
                    if (!$pointrule->save())
                    {
                        $this->setError( $pointrule->getError() );
                    }                    
                }                    
            }
            
            // if this was a pointcoupon,
            if (!empty($this->pointcoupon_id))
            {
                // do the same thing for the pointcoupon, which needs its counts decreased
                $pointcoupon = JTable::getInstance( 'PointCoupons', 'AmbraTable' );
                $pointcoupon->load( array( 'pointcoupon_id' => $this->pointcoupon_id ) );
                if (!empty($pointcoupon->pointcoupon_id))
                {
                    $pointcoupon->pointcoupon_uses = $pointcoupon->pointcoupon_uses - 1;
                    if (!$pointcoupon->save())
                    {
                        $this->setError( $pointcoupon->getError() );
                    }                    
                }                    
            }
        }
        
        return parent::delete( $oid );
    }
}
