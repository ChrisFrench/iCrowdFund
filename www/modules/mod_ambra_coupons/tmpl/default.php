<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $document->addStyleSheet( JURI::root(true).'/modules/mod_ambra_coupons/tmpl/style.css'); ?>
<?php $article_url = Ambra::getClass( "AmbraHelperPoint", 'helpers.point' )->getArticle(); ?>

<?php if ( $article_url && $params->get( 'display_article', '0' ) ) : ?>
    <div class="points_article">
        <a href="<?php echo JRoute::_( $article_url ); ?>">
            <?php echo JText::_( "Learn About Our Points Program" ); ?>
        </a>
    </div>
<?php endif;?>

<form action="<?php echo JRoute::_( $url."&task=submitcoupon", false )?>" method="post" name="adminForm" enctype="multipart/form-data">        
    <div class="mod_ambra_points_form">
        <span><?php echo JText::_( "Module Submit Coupon Code Here" ); ?></span>
        <span id="mod_ambra_points_form_inputs">
            <input type="text" size="20" name="pointcoupon_code_module" id="pointcoupon_code_module" ></input>
            <button onclick="this.form.submit();" class="button" ><?php echo JText::_('Submit Coupon'); ?></button>
        </span>
    </div>
    <?php echo JHTML::_( 'form.token' ); ?>
</form>