<?php
/**
 * @package Ambra
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class AmbraControllerPointHistory extends AmbraController 
{
    function __construct() 
    {
        DSCAcl::validateUser(JText::_('COM_AMBRA_REDIRECT_LOGIN'), 'index.php?option=com_ambra&view=login');

        
        parent::__construct();
        $this->set('suffix', 'pointhistory');
    }

    /**
     * Sets the model's state
     * 
     * @return array()
     */
    function _setModelState()
    {
        $state = parent::_setModelState();      
        $app = JFactory::getApplication();
        $model = $this->getModel( $this->get('suffix') );
        $ns = $this->getNamespace();

        $state['order']     = $app->getUserStateFromRequest($ns.'.filter_order', 'filter_order', 'tbl.created_date', 'cmd');
        $state['direction'] = $app->getUserStateFromRequest($ns.'.filter_direction', 'filter_direction', 'DESC', 'word');
        $state['filter_id_from']    = $app->getUserStateFromRequest($ns.'id_from', 'filter_id_from', '', 'int');
        $state['filter_id_to']      = $app->getUserStateFromRequest($ns.'id_to', 'filter_id_to', '', 'int');
        $state['filter_name']       = $app->getUserStateFromRequest($ns.'name', 'filter_name', '', 'string');
        $state['filter_enabled']    = $app->getUserStateFromRequest($ns.'enabled', 'filter_enabled', '', 'int');
        $state['filter_date_from'] = $app->getUserStateFromRequest($ns.'date_from', 'filter_date_from', '', 'date');
        $state['filter_date_to'] = $app->getUserStateFromRequest($ns.'date_to', 'filter_date_to', '', 'date');
        $state['filter_datetype']   = $app->getUserStateFromRequest($ns.'datetype', 'filter_datetype', '', '');
        $state['filter_points_from']    = $app->getUserStateFromRequest($ns.'points_from', 'filter_points_from', '', 'int');
        $state['filter_points_to']      = $app->getUserStateFromRequest($ns.'points_to', 'filter_points_to', '', 'int');
        
        foreach (@$state as $key=>$value)
        {
            $model->setState( $key, $value );   
        }
        return $state;
    }
    
    /**
     * Displays a user profile page
     * @see ambra/site/AmbraController#display($cachable)
     */
    function display($cachable=false, $urlparams = false)
    {
        $model  = $this->getModel( $this->get('suffix') );
        $this->_setModelState();

        $id = $model->getId();
        if (empty($id))
        {
            $id = JFactory::getUser()->id;
        }
        $model->setId( $id );
        $model->setState( 'filter_user', $id );
        
        $view = $this->getView( 'pointhistory', 'html' );
        $view->set( '_controller', 'pointhistory' );
        $view->set( '_view', 'pointhistory' );
        $view->set( '_doTask', true);
        $view->set( 'hidemenu', false);
        $view->setModel( $model, true );
        $view->setLayout( 'default' );
        $view->assign( 'user_id', $id );

        $view->assign( 'doCoupons', false );
        if ($id == JFactory::getUser()->id)
        {
            // display the submit coupon form if there are any coupons created by the admin
            $couponsModel = Ambra::getClass( "AmbraModelPointCoupons", 'models.pointcoupons' );
            $couponsModel->setState( 'select', 'COUNT(*)' );
            $couponsModel->setState( 'filter_enabled', '1' );
            if ($coupons = $couponsModel->getResult() && AmbraConfig::getInstance()->get('display_coupon_form', '1'))
            {
                $view->assign( 'doCoupons', true );
            }
        }
        
        $view->display();
        $this->footer();
    }
    
    /**
     * Verifies and submits a coupon code
     */
    function submitcoupon()
    {
        JRequest::checkToken() or jexit( 'Invalid Token' );
        $code = JRequest::getVar( 'pointcoupon_code' );
        if (empty($code))
        {
            $code = JRequest::getVar( 'pointcoupon_code_module' );
        }
        $redirect = JRoute::_( "index.php?option=com_ambra&view=pointhistory", false );
                
        $model  = Ambra::getClass( "AmbraModelPointCoupons", 'models.pointcoupons' );
        $row = $model->getTable();
        $row->load( array( 'pointcoupon_code'=>$code ) );
                
        // does coupon code even exist
        if (empty($row->pointcoupon_id))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "Code Does Not Exist" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        // is coupon enabled?
        if (empty($row->pointcoupon_enabled))
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "Invalid Code" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        // is coupon code expired?
        $date = JFactory::getDate();
        if ($row->expire_date < $date->toMySQL())
        {
            $this->messagetype  = 'notice';
            $this->message      = JText::_( "Code Expired" );
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }

        // Use the CouponHelper::createLogEntry method
        $couponHelper = Ambra::getClass( "AmbraHelperCoupon", "helpers.coupon" );
        if (!$couponHelper->createLogEntry( JFactory::getUser()->id, $row->pointcoupon_id ))
        {
            // get error
            $this->messagetype  = 'notice';
            $this->message      = $couponHelper->getError();
            $this->setRedirect( $redirect, $this->message, $this->messagetype );
            return;
        }
        
        // redirect with message
        $this->messagetype  = '';
        $this->message      = JText::_( "Code Accepted" );
        $this->setRedirect( $redirect, $this->message, $this->messagetype );
        JFactory::getApplication()->enqueueMessage( $couponHelper->getError() );
        return;
    }
}