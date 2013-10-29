<?php 
	defined('_JEXEC') or die('Restricted access'); 
    DSC::loadBootstrap('2.2.2', FALSE); 
	JHTML::_('stylesheet', 'tienda.css', 'media/com_tienda/css/'); 
 	JHTML::_('script', 'tienda.js', 'media/com_tienda/js/'); 
 	JHTML::_('script', 'tienda_checkout.js', 'media/com_tienda/js/'); 
    JHTML::_('stylesheet', 'default.css', 'templates/default/css/');
	$order_link = @$this->order_link;
	$plugin_html = @$this->plugin_html;
?>

<div class='componentheading'>
    <span><?php echo JText::_('COM_TIENDA_CHECKOUT_RESULTS'); ?></span>
</div>

<?php if( !Tienda::getInstance()->get('one_page_checkout', '0') ) : ?>
<!-- Progress Bar -->
<?php echo $this->progress; ?>
<?php endif; ?>

<?php if (!empty($this->onBeforeDisplayPostPayment)) : ?>
    <div id='onBeforeDisplayPostPayment_wrapper'>
    <?php echo $this->onBeforeDisplayPostPayment; ?>
    </div>
<?php endif; ?>

<div class=" center">
    <div class=" center" style="width:450px; text-align:center;">
<?php echo $plugin_html; ?>
    <a class="btn btn-primary btn-large" href="#" onclick="window.parent.SqueezeBox.close();">Return to Campaign</a>
    </div>
</div>

<?php foreach ($this->articles as $article) : ?>
    <div class="postpayment_article">
        <?php echo $article; ?>
    </div>    
<?php endforeach; ?>

<?php if (!empty($this->onAfterDisplayPostPayment)) : ?>
    <div id='onAfterDisplayPostPayment_wrapper'>
    <?php echo $this->onAfterDisplayPostPayment; ?>
    </div>
<?php endif; ?>
