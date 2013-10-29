<?php
/**
 * @version	1.5
 * @package	Tienda
 * @author 	Daniele Rosario
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Tienda::load( 'TiendaPluginBase', 'library.plugins._base' );

if (!class_exists('Wepay')) {
    JLoader::register("Wepay", JPATH_ADMINISTRATOR . "/components/com_wepay/defines.php");
}
Wepay::load('WepayHelperWepay', 'helpers.wepay');

class plgTiendaCampaign_variable extends TiendaPluginBase
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename, 
	 *                         forcing it to be unique 
	 */
	var $_element = 'campaign_variable';
	var $_campaigns = null;
	var $_campaign = null;
	var $_payeeEmail = null;


	function __construct( &$subject, $config )
	{

		parent::__construct( $subject, $config );
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}
	
	function onStartCronHourly() {
		
	}

	function onProcessCronHourly() {

 

		$this->_campaigns = $this->getCampaignsThatEnded();
	 

		foreach($this->_campaigns as $campaign) {

			$orders = $this->getOrdersFromCampaign($campaign->campaign_id);


			foreach($orders as $order) {
				$payment = $this->getOrderPayment($order->order_id);

                     
                if($payment) {
                    $wepay_checkout = $this->doWepayCharge($order, $payment);
           

                        if(@$wepay_checkout ->checkout_id) {
                           
                             $this->processSale($payment->orderpayment_id, $wepay_checkout);  
       
                             
                           

                        } else {
                              $this->processFailed($payment->orderpayment_id, $wepay_checkout);
                        }   
                } else {

                    //do something the payment is invalid
                }
				//mark the campaign processed
                 $campaign->completed_tasks = 1;
               
              $db = JFactory::getDbo(); 
                 $result = $db->updateObject('#__tienda_campaigns',$campaign, 'campaign_id', false);
             

				
			}
		}
			
	}

	function onAfterCron() {
	
	}

    function onAfterCronHourly() {

            if ( !class_exists('Messagebottle') && count($this->_campaigns) ) {
            JLoader::register( "Messagebottle", JPATH_ADMINISTRATOR."/components/com_messagebottle/defines.php" );
            $lang = JFactory::getLanguage();
            $lang->load('com_tienda', JPATH_ADMINISTRATOR);
            $mail = Messagebottle::getClass( 'Bottle', 'helpers.bottle' );
            $mail->addRecipient( 'chris@ammonitenetworks.com' );
          //  $mail->addRecipient( 'john@brainstormstudio.com' );
	        //  $mail->addRecipient( 'lgroveman@icrowdfund.com' );		
            $mail->setSubject( 'CronJob hourly  Ran, this cron processes funded projects' );
            $mail->setBody( sprintf ( 'processed %s campaigns, %s', count($this->_campaigns), var_dump($this->_campaigns)  ) );
            $mail->setScope('2');
            $mail->setView('campaign');
            $mail->setOption('com_tienda');
            $mail->Send(); }
        }

	function getCampaignsThatEnded() {


		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__tienda_campaigns AS tbl');
        $query->where("tbl.completed_tasks = '0'");
        $query->where("tbl.fundingtype = '3'");
        $query->where("tbl.campaign_raised > '0'");
        $date = JFactory::getDate();
		$date = $date->toSql();	
        $query->where("tbl.fundingend_date <= " . $db->Quote($date));
        
     
       
        $db->setQuery($query);
    
        $items = $db->loadObjectList();
        
       
        return $items;
	}

	function getOrdersFromCampaign($campaign_id) {
		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__tienda_orders AS tbl');
        $query->where("tbl.campaign_id = " . $db->Quote($campaign_id));
        $db->setQuery($query);
        $items = $db->loadObjectList();
        return $items;
	}

	function getOrderPayment($order_id) {
		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__tienda_orderpayments AS tbl');
        $query->where("tbl.transaction_status = 'approved'");
        $query->where("tbl.order_id = " . $db->Quote($order_id));
        $db->setQuery($query);
        $item = $db->loadObject();
        return $item;
	}

	function getOrderItem($order_id) {
		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__tienda_orderitems AS tbl');
        $query->where("tbl.order_id = " . $db->Quote($order_id));
        $db->setQuery($query);
        $item = $db->loadObject();
        return $item;
	}


	/*

	*/
	function doUpdateOrderStatus($order, $checkout) {

	}


	function doWepayCharge($order, $payment) {
		$ordereditem = $this->getOrderItem($order->order_id);
		$object = WepayHelperWepay::getObjectFromAccountID($ordereditem->vendor_id);
        $wepay = new WePayLib($object -> access_token);
        // prepare checkout data
        $wepay_checkout_data = array();
        $wepay_checkout_data['account_id'] = $ordereditem->vendor_id;
        $wepay_checkout_data['order_id'] = $order->order_id;
        $wepay_checkout_data['orderpayment_id'] = $payment->orderpayment_id;
        $wepay_checkout_data['orderpayment_amount'] = $payment->orderpayment_amount;
        $long_description = JText::_('PLG_TIENDA_PAYMENT_WEPAY_LONG_DESCRIPTION_HEADER');    
        $long_description .= 'Cmapaign:  ' . $ordereditem -> orderitem_sku . ' - ' . $ordereditem -> orderitem_name . '; ';
        Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
        $wepay_checkout_data['app_fee'] = $this->makeAppFee( $payment->orderpayment_amount, $ordereditem );
        $wepay_checkout_data['long_description'] = $long_description;
        $wepay_checkout_data['preapproval_id'] = $payment->transaction_id;
       	$wepay_checkout_data['require_shipping'] = $order->order_ships;
        $wepay_checkout = $this->_wepayCheckout( $wepay_checkout_data, $wepay );
        return $wepay_checkout;
	}

	 /**
     * WePay function to create WePay checkout
     *
     * @param array   $wepay_checkout_data
     * @param object  $wepay               object
     *
     * @return JSON $checkout WePay response  *
     */
    function _wepayCheckout($wepay_checkout_data, $wepay) {

        
        $error = '';
        try {
            /*
             $response = $wepay->request('preapproval/create', array(
             'account_id'        => $account_id,
             'period'            => 'monthly',
             'end_time'          => '2013-12-25',
             'amount'            => '19.99',
             'mode'              => 'regular',
             'short_description' => 'A subscription to our magazine.',
             'redirect_uri'      => 'http://example.com/success/',
             'auto_recur'        => 'true',
             ));
             */

            $checkout = $wepay -> request('/checkout/create', array('account_id' => $wepay_checkout_data['account_id'], // ID of the account that you want the money to go to
            'short_description' => JText::_('PLG_TIENDA_PAYMENT_WEPAY_ORDER_ID') . $wepay_checkout_data['order_id'] . '; ' . JText::_('PLG_TIENDA_PAYMENT_WEPAY_ORDERPAYMENT_ID') . $wepay_checkout_data['orderpayment_id'], // a short description of what the payment is for
           // 'period' => 'once', //How often you'd like to charge the customer (daily, weekly, monthly, yearly, etc.)
            'amount' => $wepay_checkout_data['orderpayment_amount'], // dollar amount you want to charge the user
            'short_description' => $wepay_checkout_data['long_description'], 
            'mode' => 'regular', 
            //'end_time' => '2013-12-25', //TODO get a date from some time based off of the campaign end date
           // 'payer_email_message' => $this -> params -> get('payer_email_message'),
           // 'payee_email_message' => $this -> params -> get('payee_email_message'),
            'reference_id' => $wepay_checkout_data['orderpayment_id'],
            //'fee_payer' => 'payee',
            'require_shipping' => $wepay_checkout_data['require_shipping'],
            'type' => 'DONATION',
           // 'charge_tax' => 0,
            'app_fee' => $wepay_checkout_data['app_fee'],
           // 'funding_sources' => $this -> params -> get('funding_sources'),
            'preapproval_id' => $wepay_checkout_data['preapproval_id'], // the user's credit_card_id
            //'payment_method_type' => 'credit_card'
            ));

        } catch ( WePayException $e ) {// if the API call returns an error, get the error message for display later
            $error = $e -> getMessage();

        }

        if (empty($error)) {

            return $checkout;
        } else {

            return $error;

        }
    }

    function makeAppFee( $amount , $orderItem) {
        $percent = Wepay::getInstance()->get( 'app_fee', '0.05' );
     
        $campagin = TiendaHelperCampaign::getCampaignFromProduct($orderItem->product_id);
      
        if($campagin->campaign_raised >=  $campagin->campaign_goal) {
        $percent = '0.04' ;
        } else {
        $percent = '0.09' ;
        }
        $fee = $amount * $percent;
        return $fee;

    }

     function makeTiendaStatus($wepay_checkout) {
        /*
         new
         The checkout was created by the application.
         authorized
         The payer entered their payment info and confirmed the payment on WePay. WePay has successfully charged the card, so this should be considered a success state.
         reserved
         The payment has been reserved from the payer.
         captured
         The payment has been credited to the payee account.
         settled
         The payment has been settled and no further changes are possible.
         cancelled
         The payment has been cancelled by the payer, payee, or application.
         refunded
         The payment was captured and then refunded by the payer, payee, or application. The payment has been debited from the payee account.
         charged back
         The payment has been charged back by the payer and the payment has been debited from the payee account.
         failed
         The payment has failed.
         expired
         Checkouts get expired if they are still in state new after 30 minutes (ie they have been abandoned).
         */

        switch ( $wepay_checkout->state ) {
            case 'new' :
                $state = '15';
                break;
            case 'authorized' :
                $state = '17';
                break;
            case 'reserved' :
                $state = '16';
                break;
            case 'cancelled' :
                $state = '7';
                break;
            case 'refunded' :
                $state = '11';
                break;
            case 'charged back' :
                $state = '13';
                break;   
            case 'failed' :
                $state = '10';
                break;          
            case 'approved' :
                $state = '18';
                break;
            case 'failed' :
                $state = '10';
                break;
            case 'expired' :
                $state = '7';
                break;
            case 'captured' : 
                $state = '17';
                break;   
            default :
                $state = '10';
                break;
        }

        return $state;
    }

    function processSale($orderpayment_id, $wepay_checkout) {
        $errors = array();
        // =======================
        // verify & create payment
        // =======================
        // check that payment amount is correct for order_id
        Tienda::load('TiendaTableOrderPayments', 'tables.orderpayments');
        $orderpayment = JTable::getInstance('OrderPayments', 'TiendaTable');
        $orderpayment -> load($orderpayment_id);
        if (empty($orderpayment -> order_id)) {
            // TODO fail
        }

       // $orderpayment->transaction_details  = '';
        $orderpayment->transaction_id       = $wepay_checkout->checkout_id;
        $orderpayment->transaction_status   = $wepay_checkout->state;

        Tienda::load('TiendaHelperBase', 'helpers._base');
        $stored_amount = TiendaHelperBase::number($orderpayment -> get('orderpayment_amount'), array('thousands' => ''));
        //$respond_amount = TiendaHelperBase::number( $amountResponse, array( 'thousands'=>'' ) );

        // set the order's new status and update quantities if necessary
        Tienda::load('TiendaHelperOrder', 'helpers.order');
        Tienda::load('TiendaHelperCarts', 'helpers.carts');
        $order = JTable::getInstance('Orders', 'TiendaTable');
        $order -> load($orderpayment -> order_id);

        // if an error occurred
        $order -> order_state_id = $this -> makeTiendaStatus($wepay_checkout);
        // FAILED

       // if ($wepay_checkout -> state == 'approved') {
            // do post payment actions
            $setOrderPaymentReceived = true;
            TiendaHelperOrder::setOrderPaymentReceived($order ->order_id);
            // send email
         //   $send_email = true;
        //}

        // save the order
        if (!$order -> save()) {
            $errors[] = $order -> getError();
        }

        // save the orderpayment
        if (!$orderpayment -> save()) {
            $errors[] = $orderpayment -> getError();
        }

        /*if (!empty($setOrderPaymentReceived)) {
            $this -> setOrderPaymentReceived($orderpayment -> order_id);
        }*/

     /*   if ($send_email) {
            // send notice of new order
            Tienda::load("TiendaHelperBase", 'helpers._base');
            $helper = TiendaHelperBase::getInstance('Email');
            $model = Tienda::getClass("TiendaModelOrders", "models.orders");
            $model -> setId($orderpayment -> order_id);
            $order = $model -> getItem();
            $helper -> sendEmailNotices($order, 'new_order');
        }*/

        if (empty($errors)) {


            $return = JText::_('COM_TIENDA_TIENDA_WEPAY_MESSAGE_PAYMENT_SUCCESS');
            return $return;
        }

        if (!empty($errors)) {

            $string = implode("\n", $errors);
            $return = "<div class='note_pink'>" . $string . "</div>";
            return $return;
        }

    }

    function processFailed($orderpayment_id, $wepay_checkout) {
        $errors = array();
        
        // =======================
        // verify & create payment
        // =======================
        // check that payment amount is correct for order_id
        Tienda::load('TiendaTableOrderPayments', 'tables.orderpayments');
        $orderpayment = JTable::getInstance('OrderPayments', 'TiendaTable');
        $orderpayment -> load($orderpayment_id);
        if (empty($orderpayment -> order_id)) {
            // TODO fail
        }

      if (@$wepay_checkout->state) {
$status = $wepay_checkout->state;
      } else {
        $status = $wepay_checkout;

      }
        
       // $orderpayment->transaction_details  = '';
       // $orderpayment->transaction_id       = $wepay_checkout->checkout_id;
        $orderpayment->transaction_status   = $status;

        //Tienda::load('TiendaHelperBase', 'helpers._base');
        //$stored_amount = TiendaHelperBase::number($orderpayment -> get('orderpayment_amount'), array('thousands' => ''));
        //$respond_amount = TiendaHelperBase::number( $amountResponse, array( 'thousands'=>'' ) );

        // set the order's new status and update quantities if necessary
        Tienda::load('TiendaHelperOrder', 'helpers.order');
        Tienda::load('TiendaHelperCarts', 'helpers.carts');
        Tienda::load('TiendaTableOrders', 'tables.orders');
        $order = JTable::getInstance('Orders', 'TiendaTable');
        $order -> load($orderpayment -> order_id);

        // if an error occurred
        $order -> order_state_id = $this -> makeTiendaStatus($wepay_checkout);
        // FAILED

       // if ($wepay_checkout -> state == 'approved') {
            // do post payment actions
            $setOrderPaymentReceived = false;
        
            // send email
         //   $send_email = true;
        //}

        // save the order
        if (!$order -> save()) {
            $errors[] = $order -> getError();
        }

        // save the orderpayment
        if (!$orderpayment -> save()) {
            $errors[] = $orderpayment -> getError();
        }

        /*if (!empty($setOrderPaymentReceived)) {
            $this -> setOrderPaymentReceived($orderpayment -> order_id);
        }*/

     /*   if ($send_email) {
            // send notice of new order
            Tienda::load("TiendaHelperBase", 'helpers._base');
            $helper = TiendaHelperBase::getInstance('Email');
            $model = Tienda::getClass("TiendaModelOrders", "models.orders");
            $model -> setId($orderpayment -> order_id);
            $order = $model -> getItem();
            $helper -> sendEmailNotices($order, 'new_order');
        }*/

        if (empty($errors)) {


            $return = JText::_('COM_TIENDA_TIENDA_WEPAY_MESSAGE_PAYMENT_FAILED');
            return $return;
        }

        if (!empty($errors)) {

            $string = implode("\n", $errors);
            $return = "<div class='note_pink'>" . $string . "</div>";
            return $return;
        }

    }
	
}
