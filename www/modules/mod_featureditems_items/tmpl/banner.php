<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php
JHtml::_('script', 'carousel.js', 'modules/mod_featureditems_items/media/js/');
?>

<?php if ($count > '0') { ?>

<div class="slideshow banner">
    <div class="container">
        <ul class="slides">
        <?php foreach ($items as $key=>$item) { ?>
            <li class="slide wrap <?php if ($key == 0) { echo " first"; } if ($key == ($count-1)) { echo " last"; } ?>">
                <div class="slide-frame">
                    <a href="<?php echo JRoute::_( $item->url ); ?>">
                    
                        <div class="table full feature-overlay wrap">
                            <div class="cell">
                                <div class="page">
        	                    	<?php if (!empty($item->content)) { ?>
        	                    	<h3 class="description"><?php echo $item->content; ?></h3>
        	                    	<?php } ?>
    	                    	</div>
                            </div>
                        </div>
                        
                    	<div class="feature-labels feature-meta page">
                    	    <div class="inner">
                        	    <div class="table full">
                                    <div class="row">
                                        <div class="label cell"><?php echo JText::_( $item->label ); ?></div>
                                        <div class="margin cell"></div>
                                        <div class="long-title cell"><?php echo $item->long_title; ?></div>
                                        <div class="margin cell"></div>
                                        <div class="short-title cell"><?php echo $item->short_title; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <img src="<?php echo $item->image_src; ?>" alt="<?php echo htmlspecialchars( $item->long_title ); ?>" title="<?php echo htmlspecialchars( $item->long_title ); ?>" > 
                    </a>
                </div>
            </li>
        <?php } ?>
        </ul>
    </div>
</div>
        
        
<?php if ($count > '1') { ?>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.slideshow.banner').carousel({
        transition: '<?php echo $params->get( 'slideshow_transition', 'fade' ); ?>',
        start: <?php echo $params->get( 'slideshow_start', '0' ); ?>,
        autoPlayInterval: <?php echo $params->get( 'slideshow_autoplayinterval', '6000' ); ?>,
        autoPlay: <?php echo $params->get( 'slideshow_autoplay', '1' ); ?>,
        autoPlayStopOnClick: <?php echo $params->get( 'slideshow_autoplaystoponclick', '1' ); ?>,
        hideControls: <?php echo $params->get( 'slideshow_hidecontrols', '0' ); ?>,
        insertControls: <?php echo $params->get( 'slideshow_insertcontrols', '1' ); ?>,
        loop: <?php echo $params->get( 'slideshow_loop', '1' ); ?>,
        duration: <?php echo $params->get( 'slideshow_duration', '1000' ); ?>,
        sizeToBrowser: <?php echo $params->get( 'slideshow_sizetobrowser', '0' ); ?>,
        containerWidth: <?php echo $params->get( 'slideshow_container_width', '960' ); ?>,
        containerHeight: <?php echo $params->get( 'slideshow_container_height', '450' ); ?>,
        slideWidth: <?php echo $params->get( 'slideshow_slide_width', '960' ); ?>,
        slideHeight: <?php echo $params->get( 'slideshow_slide_height', '450' ); ?>
    });

    jQuery('.slideshow.banner .description p').each(function(){
        jQuery(this).contents().wrap('<span>');
    });
});
</script>
<?php } ?>

<?php } ?>
