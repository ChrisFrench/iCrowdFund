<?php
/**
 *
 *
 * @version 2.5
 * @package Tienda
 * @author  Bojan Nisevic
 * @link  http://www.boyansoftware.com
 * @copyright Copyright (C) 2012 CrowdFunding. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/* ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Tienda::load('TiendaPaymentPlugin', 'library.plugins.payment');

if (!class_exists('Wepay')) {
    JLoader::register("Wepay", JPATH_ADMINISTRATOR . "/components/com_wepay/defines.php");
}
Wepay::load('WepayHelperWepay', 'helpers.wepay');
Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
class plgTiendaPayment_wepay_authorize_variable extends TiendaPaymentPlugin {
    /**
     *
     *
     * @var $_element  string  Should always correspond with the plugin's filename,
     *                         forcing it to be unique
     */
    var $_element = 'payment_wepay_authorize_variable';

    /**
     * Constructor
     *
     * @param object  $subject The object to observe
     * @param array   $config  An array that holds the plugin configuration
     * @since 1.5
     */
    function __construct(&$subject, $config) {
        parent::__construct($subject, $config);
        $language = JFactory::getLanguage();
        $language -> load('plg_tienda_' . $this -> _element, JPATH_ADMINISTRATOR, 'en-GB', true);
        $language -> load('plg_tienda_' . $this -> _element, JPATH_ADMINISTRATOR, null, true);
    }

    /************************************
     * Note to 3pd:
     *
     * The methods between here
     * and the next comment block are
     * yours to modify
     *
     ************************************/

    /**
     * Determines if this payment option is valid for this order
     *
     * @param $element
     * @param $order
     * @return unknown_type
     */
    function onGetPaymentOptions($element, $order) {
        //lets check if we have geozones and stuff from base
        $found = parent::onGetPaymentOptions($element, $order);
        //is so lets see if the product we are checking out  wants this wepay type?
	FB::log($found);     

   if ($found) {
            $orderitems = $order -> getItems();
            foreach ($orderitems as $item) {
	FB::log($item->campaign->fundingtype, 'order items');   
                 //THIS LOGIC IS BACKWARDS, Instead of checking  for what this funding level is we need to check against the ones it is not
                if($item->campaign->fundingtype == 1 || $item->campaign->fundingtype == 2 || $item->campaign->fundingtype == 4 ) {
                   $found = false; 
                }
            }
        }

        return $found;

    }

    /**
     * Prepares the payment form
     * and returns HTML Form to be displayed to the user
     * generally will have a message saying, 'confirm entries, then click complete order'
     *
     * Submit button target for onsite payments & return URL for offsite payments should be:
     * index.php?option=com_tienda&view=checkout&task=confirmPayment&orderpayment_type=xxxxxx
     * where xxxxxxx = $_element = the plugin's filename
     *
     * @param unknown $data array       form post data
     * @return string   HTML to display
     */
    function _prePayment($data) {
        // prepare the payment form
        $vars = new JObject();
        $vars -> url = JRoute::_("index.php?option=com_tienda&view=checkout");
        $vars -> order_id = $data['order_id'];
        $vars -> orderpayment_id = $data['orderpayment_id'];
        $vars -> orderpayment_amount = $data['orderpayment_amount'];
        $vars -> orderpayment_type = $this -> _element;

        $html = $this -> _getLayout('prepayment', $vars);

        return $html;
    }

    /**
     * Payment plugins should override this function
     * to customize the one-line summary that is displayed
     * during the new OPC
     *
     * @param unknown_type $data
     * @return NULL
     */
    protected function _getSummary($data) {
        $vars = new JObject();
        $vars -> message = 'Checking out via Wepay, Credit Card';

        $html = $this -> _getLayout('summary', $vars);

        return $html;
    }

    /**
     * Processes the payment form
     * and returns HTML to be displayed to the user
     * generally with a success/failed message
     *
     * @param unknown $data array       form post data
     * @return string   HTML to display
     */
    function _postPayment($data) {
        // Process the payment
        $vars = new JObject();
	FB::log($data);    
         JRequest::setVar('tmpl', 'component');
        $app = JFactory::getApplication();
        $paction = JRequest::getVar('paction');

        switch ( $paction ) {
            case 'process_recurring' :
                // TODO Complete this
                // $this->_processRecurringPayment();
                $app -> close();
                break;
            case 'process' :
                $vars -> message = $this -> _process();
                $html = $this -> _getLayout('message', $vars);
                break;
            default :
                $vars -> message = JText::_('COM_TIENDA_INVALID_ACTION');
                $html = $this -> _getLayout('message', $vars);
                break;
        }

        return $html;

    }

    /**
     * Prepares variables and
     * Renders the form for collecting payment info
     *
     * @return unknown_type
     */
    function _renderForm($data = null) {
        $html = $this -> _getLayout('form');
        return $html;
    }

    /**
     * Verifies that all the required form fields are completed
     * if any fail verification, set
     * $object->error = true
     * $object->message .= '<li>x item failed verification</li>'
     *
     * @param unknown $submitted_values array   post data
     * @return unknown_type
     */
    function _verifyForm($data) {
         $session = JFactory::getSession();
        // Include the JLog class.
        $object = new JObject();
        $object -> error = false;
        $object -> message = '';

  

        if(strlen(@$data['anonymous'])){
             
              $session->set('tienda.order.anonymous', '1');
              $anonymous = $session->get('tienda.order.anonymous');
             
             }

        foreach ($data as $key => $value) {
            switch ( $key ) {

                case "cardnum" :
                    if (!isset($data[$key]) || !JString::strlen($data[$key])) {
                        $object -> error = true;
                        $object -> message .= "<li>" . JText::_('PLG_TIENDA_PAYMENT_WEPAY_CARD_NUMBER_INVALID') . "</li>";
                    }
                    break;
                case "cardexp" :
                    if (!isset($data[$key]) || JString::strlen($data[$key]) != 4) {
                        $object -> error = true;
                        $object -> message .= "<li>" . JText::_('PLG_TIENDA_PAYMENT_WEPAY_CARD_EXPIRATION_DATE_INVALID') . "</li>";
                    }
                    break;
                case "cardcvv" :
                    if (!isset($data[$key]) || !JString::strlen($data[$key])) {
                        $object -> error = true;
                        $object -> message .= "<li>" . JText::_('PLG_TIENDA_PAYMENT_WEPAY_CARD_CVV_INVALID') . "</li>";
                    }
                    break;
                default :
                    break;
            }
        }
        /* after the normal checks to make sure we have all the right data for the Credit Card, lets check against wepay and than create the creditcard ID,
         *
         *    this way we get error checking while adding the card and we don't need to pass the CC between posts it only happens  one time.
         */

        if ($object -> error == false) {

            
            $account_id = $session -> get('tienda.wepay.vendor_id');
          
            $BillingAddress = unserialize($session -> get('tienda.opc.billingAddress'));
           
            $app = JFactory::getApplication();

            $vendor = WepayHelperWepay::getObjectFromAccountID($account_id);

            // WePayLib is defined in defines.php of wepay component, we use the vendors access token to card the card on their behalf
            $wepay = new WePayLib($vendor -> access_token);
            //we need the billing address to authorize the card.
            //for some reason the order isn't being created
            /* $order = unserialize( $session->get( 'tienda.opc.order' ) );
      

             // prepare CC data
             $wepay_cc_info = array();
             $wepay_cc_info['user_name'] = $order->_billing_address->first_name . ' ' . $order->_billing_address->last_name;
             $wepay_cc_info['email'] = JFactory::getUser()->email;
             $wepay_cc_info['cc_number'] = str_replace( " ", "", str_replace( "-", "", $data['cardnum'] ) );
             $wepay_cc_info['cvv'] = $data['cardcvv'];// should setup textbox to accept only three digits
             $wepay_cc_info['expiration_month'] = $data['cardexp_month'];
             $wepay_cc_info['expiration_year'] = $data['cardexp_year'];
             $address = array(
             'address1'  => $order->_billing_address->address_1. " ".$order->_billing_address->address_2,
             'city'      => $order->_billing_address->city,
             'state'     => $order->_billing_address->zone_code,
             'country'   => $order->_billing_address->country_code,
             'zip'       => $order->_billing_address->postal_code
             ); */

             $email = JFactory::getUser()->email;

            if(empty($email)) {
               $email =  $session->get('tienda.opc.guestemail');
            }

            // prepare CC data
            $wepay_cc_info = array();


            $wepay_cc_info['user_name'] = $BillingAddress -> first_name . ' ' . $BillingAddress -> last_name;
            $wepay_cc_info['email'] = $email;
            $wepay_cc_info['cc_number'] = str_replace(" ", "", str_replace("-", "", $data['cardnum']));
            $wepay_cc_info['cvv'] = $data['cardcvv'];
            // should setup textbox to accept only three digits
            $wepay_cc_info['expiration_month'] = $data['cardexp_month'];
            $wepay_cc_info['expiration_year'] = $data['cardexp_year'];
            $address = array('address1' => $BillingAddress -> address_1 . " " . $BillingAddress -> address_2, 'city' => $BillingAddress -> city, 'state' => $this -> getTwoLetterStateCode($BillingAddress -> zone_id), 'country' => $this -> getTwoLetterCountryCode($BillingAddress -> country_id), 'zip' => $BillingAddress -> postal_code);

            $wepay_cc_info['address'] = $address;
           
            // credit card to AUTHORIZE the card
            $wepay_credit_card = $this -> _getWePayCCID($wepay_cc_info, $wepay);
            if ($wepay_credit_card -> credit_card_id) {
                //if we have a credit card id we have an authorized card, this is not a CC # it is a secure ID
                $session -> set('tienda.wepay.credit_card_id', $wepay_credit_card -> credit_card_id);
                $session -> set('tienda.wepay.credit_card_state', $wepay_credit_card -> state);

            } else {
                $object -> error = true;
                $object -> message = $wepay_credit_card;
            }




        }

        


        return $object;
    }

    /************************************
     * Note to 3pd:
     *
     * The methods between here
     * and the next comment block are
     * specific to this payment plugin
     *
     ************************************/

    /**
     * Processes the payment
     *
     * This method process only real time (simple and subscription create) payments
     * The scheduled recurring payments are processed by the corresponding method
     *
     * @return string
     * @access protected
     */
    function _process() {
        $data = JRequest::get('post');

        $session = JFactory::getSession();

        // order info
        DSCTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tienda/tables');
        $order = DSCTable::getInstance('Orders', 'TiendaTable');
        $order -> load($data['order_id']);
        $anonymous = $session->get('tienda.order.anonymous');
        

        $orderitems = $order -> getItems();

        $orderpayment = DSCTable::getInstance('OrderPayments', 'TiendaTable');
        $orderpayment -> load($data['orderpayment_id']);

        $orderinfo = $this -> getCustomOrderInfo($data['order_id']);
        $object = WepayHelperWepay::getObjectFromAccountID($session -> get('tienda.wepay.vendor_id'));
        $wepay = new WePayLib($object -> access_token);

        // prepare checkout data
        $wepay_checkout_data = array();
        $wepay_checkout_data['account_id'] = $session -> get('tienda.wepay.vendor_id');
        $wepay_checkout_data['order_id'] = $data['order_id'];
        $wepay_checkout_data['orderpayment_id'] = $data['orderpayment_id'];
        $wepay_checkout_data['orderpayment_amount'] = $orderpayment -> orderpayment_amount;
      
        $long_description = JText::_('PLG_TIENDA_PAYMENT_WEPAY_LONG_DESCRIPTION_HEADER');
        foreach ($orderitems as $item) {
            
            $long_description .= 'Items purchased: SKU: ' . $item -> orderitem_sku . ' - ' . $item -> orderitem_name . '; ';
            Tienda::load('TiendaHelperCampaign', 'helpers.campaign');

                $campaign = TiendaHelperCampaign::getCampaignFromProduct($item -> product_id);
        }
        //$wepay_checkout_data['app_fee'] = $this->makeAppFee( $orderpayment->orderpayment_amount, $item );
        $wepay_checkout_data['long_description'] = $long_description;
        $wepay_checkout_data['credit_card_id'] = $session -> get('tienda.wepay.credit_card_id');
       
        $wepay_checkout = $this->_wepayCheckout( $wepay_checkout_data, $wepay );
        if(empty($wepay_checkout->preapproval_id)) {
        // do an error
            
        }

        if($anonymous){
        $order -> anonymous = 1;
         }

       // $order -> app_fee = $wepay_checkout_data['app_fee'];
        $order -> campaign_id = $campaign->campaign_id;
        $order -> store();    
       

        $this -> processSale($orderpayment -> orderpayment_id, $wepay_checkout);

        //$error = 'processed';
        //return $error;

    }

    function makeAppFee( $amount , $orderItem) {
       // $percent = Wepay::getInstance()->get( 'app_fee', '0.05' );
     
        $campagin = TiendaHelperCampaign::getCampaignFromProduct($orderItem->product_id);
        // setting it at max      
        if($campagin) {
        $percent = $campagin->app_fee ;
        }
        $fee = $amount * $percent;
        return $fee;

    }

    function getCustomOrderInfo($order_id) {
        /*We need the TWO letter short codes of state and country*/
        $db = JFactory::getDBO();
        $query = new DSCQuery();
        $query -> select('o.*, c.*, z.code as state_code');
        $query -> from('#__tienda_orderinfo AS o');
        $query -> leftJoin('#__tienda_countries AS c ON o.billing_country_id = c.country_id');
        $query -> leftJoin('#__tienda_zones AS z ON o.billing_zone_id = z.zone_id');
        $query -> where('o.order_id = ' . (int)$order_id);
        $db -> setQuery($query);
        $orderinfo = $db -> loadObject();
        return $orderinfo;
    }

    function getTwoLetterCountryCode($c_id) {
        /*We need the TWO letter short codes of state and country*/
        $db = JFactory::getDBO();
        $query = new DSCQuery();
        $query -> select('c.*');
        $query -> from('#__tienda_countries AS c');
        $query -> where('c.country_id = ' . (int)$c_id);
        $db -> setQuery($query);
        $country = $db -> loadObject();
        return $country -> country_isocode_2;
    }

    function getTwoLetterStateCode($z_id) {
        /*We need the TWO letter short codes of state and country*/
        $db = JFactory::getDBO();
        $query = new DSCQuery();
        $query -> select('z.*');
        $query -> from('#__tienda_zones AS z');
        $query -> where('z.zone_id = ' . (int)$z_id);
        $db -> setQuery($query);
        $zone = $db -> loadObject();
        return $zone -> code;
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
            case 'approved' :
                $state = '18';
                break;
            case 'failed' :
                $state = '10';
                break;
            default :
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
        DSCTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tienda/tables');
        $orderpayment = DSCTable::getInstance('OrderPayments', 'TiendaTable');
        $orderpayment -> load($orderpayment_id);
        if (empty($orderpayment -> order_id)) {
            // TODO fail
        }
        $orderpayment -> transaction_details = 'preapproval_id=' . $wepay_checkout -> preapproval_id;
        $orderpayment -> transaction_id = $wepay_checkout -> preapproval_id;
        $orderpayment -> transaction_status = $wepay_checkout -> state;

        Tienda::load('TiendaHelperBase', 'helpers._base');
        $stored_amount = TiendaHelperBase::number($orderpayment -> get('orderpayment_amount'), array('thousands' => ''));
        //$respond_amount = TiendaHelperBase::number( $amountResponse, array( 'thousands'=>'' ) );

        // set the order's new status and update quantities if necessary
        Tienda::load('TiendaHelperOrder', 'helpers.order');
        Tienda::load('TiendaHelperCarts', 'helpers.carts');
        $order = DSCTable::getInstance('Orders', 'TiendaTable');
        $order -> load($orderpayment -> order_id);

        // if an error occurred
        $order -> order_state_id = $this -> makeTiendaStatus($wepay_checkout);
        // FAILED

        if ($wepay_checkout -> state == 'approved') {
            // do post payment actions
            $setOrderPaymentReceived = true;

            // send email
            $send_email = true;
        }

        // save the order
        if (!$order -> save()) {
            $errors[] = $order -> getError();
        }

        // save the orderpayment
        if (!$orderpayment -> save()) {
            $errors[] = $orderpayment -> getError();
        }

        if (!empty($setOrderPaymentReceived)) {
            $this -> setOrderPaymentReceived($orderpayment -> order_id);
        }

        if ($send_email) {
            // send notice of new order
            Tienda::load("TiendaHelperBase", 'helpers._base');
            $helper = TiendaHelperBase::getInstance('Email');
            $model = Tienda::getClass("TiendaModelOrders", "models.orders");
            $model -> setId($orderpayment -> order_id);
            $order = $model -> getItem();
            $helper -> sendEmailNotices($order, 'new_order');
        }

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

    /**
     * WePay function to get WePay CC ID
     *
     * @param array   $wepay_cc_info array of CC and holder's info
     * @param object  $wepay_object
     *
     * @return $credit_card_id
     */
    function _getWePayCCID($wepay_cc_info, $wepay_object) {
        $error = '';
        try {
          
            // create the credit card
            $response = $wepay_object -> request('credit_card/create', array(
            // 'client_id'   => $wepay_cc_info['client_id'],
            'user_name' => $wepay_cc_info['user_name'], 'email' => $wepay_cc_info['email'], 'cc_number' => $wepay_cc_info['cc_number'], 'cvv' => $wepay_cc_info['cvv'], 'expiration_month' => $wepay_cc_info['expiration_month'], 'expiration_year' => $wepay_cc_info['expiration_year'], 'address' => $wepay_cc_info['address']));
          
        } catch ( WePayException $e ) {// if the API call returns an error, get the error message for display later
            $error = $e -> getMessage();

        }

        if (empty($error)) {
            return $response;
        } else {

            
            // ONLY FOR TESTING CHANGE IT TO MORE CONVINIENT
            return $error;
        }

        return $response;
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

            $checkout = $wepay -> request('/preapproval/create', array('account_id' => $wepay_checkout_data['account_id'], // ID of the account that you want the money to go to
            'short_description' => JText::_('PLG_TIENDA_PAYMENT_WEPAY_ORDER_ID') . $wepay_checkout_data['order_id'] . '; ' . JText::_('PLG_TIENDA_PAYMENT_WEPAY_ORDERPAYMENT_ID') . $wepay_checkout_data['orderpayment_id'], // a short description of what the payment is for
            'period' => 'once', //How often you'd like to charge the customer (daily, weekly, monthly, yearly, etc.)
            'amount' => $wepay_checkout_data['orderpayment_amount'], // dollar amount you want to charge the user
            'short_description' => $wepay_checkout_data['long_description'], 'mode' => 'regular', 'end_time' => '2013-12-25', //TODO get a date from some time based off of the campaign end date
            'payer_email_message' => $this -> params -> get('payer_email_message'), 'payee_email_message' => $this -> params -> get('payee_email_message'), 'reference_id' => $wepay_checkout_data['orderpayment_id'], 'fee_payer' => 'payee', 'require_shipping' => 0, 'charge_tax' => 0, 'app_fee' => $wepay_checkout_data['app_fee'], 'funding_sources' => $this -> params -> get('funding_sources'), 'payment_method_id' => $wepay_checkout_data['credit_card_id'], // the user's credit_card_id
            'payment_method_type' => 'credit_card'));

        } catch ( WePayException $e ) {// if the API call returns an error, get the error message for display later
            $error = $e -> getMessage();

        }

        if (empty($error)) {

            return $checkout;
        } else {

            return $error;

        }
    }

    /**
     * Displays the article with payment info on the order page & email if the order is yet to pay
     *
     * @param TiendaModelOrders $order
     */
    function onBeforeDisplayOrderView($order) {

    }

    /**
     * Formats the value of the card expiration date
     *
     * @param string  $format
     * @param unknown $value
     * @return string|boolean date string or false
     * @access protected
     */
    function _getFormattedCardExprDate($format, $value) {
        // we assume we received a $value in the format MMYY
        $month = substr($value, 0, 2);
        $year = substr($value, 2);

        if (strlen($value) != 4 || empty($month) || empty($year) || strlen($year) != 2) {
            return false;
        }

        $date = date($format, mktime(0, 0, 0, $month, 1, $year));
        return $date;
    }

    /**
     * Shows the CVV popup
     *
     * @return unknown_type
     */
    public function showCVV($row) {
        if (!$this -> _isMe($row)) {
            return null;
        }

        $vars = new JObject();
        echo $this -> _getLayout('showcvv', $vars);
        return;
    }

}
