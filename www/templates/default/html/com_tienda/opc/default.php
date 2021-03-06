<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $active = false;
   DSC::loadBootstrap('2.2.2', FALSE);
JHTML::_('stylesheet', 'default.css', 'templates/default/css/');
   ?>

<?php JHTML::_('script', 'tienda.js', 'media/com_tienda/js/'); ?>
<?php JHTML::_('script', 'class.js', 'media/com_tienda/js/'); ?>
<?php JHTML::_('script', 'validation.js', 'media/com_tienda/js/'); ?>
<?php JHTML::_('script', 'opcaccordion.js', 'media/com_tienda/js/'); ?>
<?php JHTML::_('script', 'opc.js', 'media/com_tienda/js/'); ?>
<?php JHTML::_('stylesheet', 'opc.css', 'media/com_tienda/css/'); ?>

<?php 
$cart_itemid = $this->router->findItemid( array('view'=>'carts') );
if (empty($cart_itemid)) {
    $cart_itemid = JRequest::getInt('Itemid');
}
 
$guest_checkout_enabled = $this->defines->get('guest_checkout_enabled');
$failureUrl = $this->defines->get('opc_failure_url', JRoute::_( "index.php?option=com_tienda&view=carts&Itemid=".$cart_itemid ) );

Tienda::load( 'TiendaHelperAddresses', 'helpers.addresses' );
$js_strings = array( 'COM_TIENDA_PLEASE_CHOOSE_REGISTER', 'COM_TIENDA_PLEASE_CHOOSE_REGISTER_OR_CHECKOUT_AS_GUEST' );
TiendaHelperAddresses::addJsTranslationStrings( $js_strings );

$doc = JFactory::getDocument();
$js = 'tiendaJQ(document).ready(function(){
    Opc = new TiendaOpc("#opc-checkout-steps", { guestCheckoutEnabled: '.$guest_checkout_enabled.', urls: { failure: "'.$failureUrl.'" } });';
    if (empty($this->user->id)) {
        $js .= 'Opc.gotoSection("checkout-method");';
    } else {
        $js .= 'Opc.gotoSection("billing");';
    }
    if (!empty($this->showShipping)) {
        $js .= 'Opc.shipping = new TiendaShipping("#opc-shipping-form");';
    }
    $js .= 'Opc.payment = new TiendaPayment("#opc-payment-form");';
$js .= '});';
$doc->addScriptDeclaration($js);
?>

<ol id="opc-checkout-steps">
    
    <?php if (empty($this->user->id)) { ?>
    <li id="opc-checkout-method" class="opc-section allow <?php if (empty($active)) { $active = 'opc-checkout-method'; echo 'active'; } ?>">
        <div class="opc-section-title dsc-wrap">
            <h1>
                <?php echo JText::_( "COM_TIENDA_CHECKOUT_METHOD" ); ?>
                <a class="opc-change" href="#"><?php echo JText::_( "COM_TIENDA_CHANGE" ); ?></a>
            </h1>
        </div>
        <div id="opc-checkout-method-body" class="opc-section-body <?php echo ($active != 'opc-checkout-method') ? 'opc-hidden' : 'opc-open'; ?>">
            <?php $this->setLayout('loginregister'); echo $this->loadTemplate(); ?>
        </div>
        <div id="opc-checkout-method-summary" class="opc-summary muted opc-hidden"></div>
    </li>
    <?php } ?>
    
    <li id="opc-billing" class="opc-section <?php if (empty($active)) { $active = 'opc-billing'; echo 'allow active'; } ?>">
        <div class="opc-section-title dsc-wrap">
            <h4>
                <?php echo JText::_( "COM_TIENDA_BILLING_INFORMATION" ); ?>
                <a class="opc-change" href="#"><?php echo JText::_( "COM_TIENDA_CHANGE" ); ?></a>
            </h4>
        </div>
        <div id="opc-billing-body" class="opc-section-body <?php echo ($active != 'opc-billing') ? 'opc-hidden' : 'opc-open'; ?>">
            <?php $this->setLayout('billing'); echo $this->loadTemplate(); ?>
        </div>
        <div id="opc-billing-summary" class="opc-summary muted opc-hidden"></div>
    </li>    
    
    <?php if (!empty($this->showShipping)) { ?>
    <li id="opc-shipping" class="opc-section <?php if (empty($active)) { $active = 'opc-shipping'; echo 'active'; } ?>">
        <div class="opc-section-title dsc-wrap">
            <h4>
                <?php echo JText::_( "COM_TIENDA_SHIP_TO" ); ?>
                <a class="opc-change" href="#"><?php echo JText::_( "COM_TIENDA_CHANGE" ); ?></a>
            </h4>
        </div>
        <div id="opc-shipping-body" class="opc-section-body <?php echo ($active != 'opc-shipping') ? 'opc-hidden' : 'opc-open'; ?>">
            <?php $this->setLayout('shipping'); echo $this->loadTemplate(); ?>
        </div>
        <div id="opc-shipping-summary" class="opc-summary muted opc-hidden"></div>
    </li>
    
    <li id="opc-shipping-method" class="opc-section <?php if (empty($active)) { $active = 'opc-shipping-method'; echo 'active'; } ?>">
        <div class="opc-section-title dsc-wrap">
            <h4>
                <?php echo JText::_( "COM_TIENDA_SHIPPING_METHOD" ); ?>
                <a class="opc-change" href="#"><?php echo JText::_( "COM_TIENDA_CHANGE" ); ?></a>
            </h4>            
        </div>
        <div id="opc-shipping-method-body" class="opc-section-body <?php echo ($active != 'opc-shipping-method') ? 'opc-hidden' : 'opc-open'; ?>">
           
        </div>
        <div id="opc-shipping-method-summary-nothing" class="opc-summary muted opc-hidden" style="display:none;"></div>
    </li>
    <?php } ?>

    <li id="opc-payment" class="opc-section <?php if (empty($active)) { $active = 'opc-payment'; echo 'active'; } ?>">
        <div class="opc-section-title dsc-wrap">
            <h4>
                <?php echo JText::_( "COM_TIENDA_PAYMENT_INFORMATION" ); ?>
                <a class="opc-change" href="#"><?php echo JText::_( "COM_TIENDA_CHANGE" ); ?></a>
            </h4>
        </div>
        <div id="opc-payment-body" class="opc-section-body <?php echo ($active != 'opc-payment') ? 'opc-hidden' : 'opc-open'; ?>">
            <?php //$this->setLayout('payment'); echo $this->loadTemplate(); ?>
        </div>
        <div id="opc-payment-summary" class="opc-summary muted opc-hidden"></div>
        <div id="opc-payment-prepayment" class="opc-hidden"></div>
    </li>

    <li id="opc-review" class="opc-section <?php if (empty($active)) { $active = 'opc-review'; echo 'active'; } ?>">
        <div class="opc-section-title dsc-wrap">
            <h4>
                <?php echo JText::_( "COM_TIENDA_ORDER_REVIEW" ); ?>
                <a class="opc-change" href="#"><?php echo JText::_( "COM_TIENDA_CHANGE" ); ?></a>
            </h4>
        </div>
        <div id="opc-review-body" class="opc-section-body <?php echo ($active != 'opc-review') ? 'opc-hidden' : 'opc-open'; ?>">
            <?php //$this->setLayout('review'); echo $this->loadTemplate(); ?>
        </div>
        <div id="opc-review-summary" class="opc-summary muted opc-hidden"></div>
    </li>
</ol>
