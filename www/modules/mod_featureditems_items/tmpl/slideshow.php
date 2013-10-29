<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php
JHtml::_('script', 'carousel.js', 'modules/mod_featureditems_items/media/js/');
?>

<?php if ($count > '0') { ?>

<div class="slideshow">
    <div class="container">
        <ul class="slides">
        <?php foreach ($items as $key=>$item) { ?>
            <li class="slide wrap <?php if ($key == 0) { echo " first"; } if ($key == ($count-1)) { echo " last"; } ?>">
                <div class="slide-frame">
                    <a href="<?php echo JRoute::_( $item->url ); ?>">
                        <div class="feature-meta">
                            <h4 class="label"><?php echo JText::_( $item->label ); ?></h4>
                            <div class="short-title"><?php echo $item->short_title; ?></div>
                        </div>
                        <img src="<?php echo $item->image_src; ?>" alt="<?php echo htmlspecialchars( $item->long_title ); ?>" title="<?php echo htmlspecialchars( $item->long_title ); ?>" > 
                    </a>
                </div>
            </li>
        <?php } ?>
        </ul>
    </div>
    
    <div class="slideshow-details">
        <ul class="details">
            <?php
            foreach ( $items as $key=>$item ) { ?>
                <li class="detail <?php if ($key == 0) { echo " first"; } if ($key == ($count-1)) { echo " last"; } ?>">
                    <div class="feature-info">
                        <div class="long-title"><?php echo $item->long_title; ?></div>
                        <div class="description"><?php echo $item->content; ?></div>
                    </div>            
                </li>
            <?php } ?>
        </ul>
    </div>
    
</div>
        
        
<?php if ($count > '1') { ?>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.slideshow').carousel({
        transition: '<?php echo $params->get( 'slideshow_transition', 'fade' ); ?>',
        start: <?php echo $params->get( 'slideshow_start', '0' ); ?>,
        autoPlayInterval: <?php echo $params->get( 'slideshow_autoplayinterval', '6000' ); ?>,
        autoPlay: <?php echo $params->get( 'slideshow_autoplay', '1' ); ?>,
        autoPlayStopOnClick: <?php echo $params->get( 'slideshow_autoplaystoponclick', '1' ); ?>,
        hideControls: <?php echo $params->get( 'slideshow_hidecontrols', '0' ); ?>,
        insertControls: <?php echo $params->get( 'slideshow_insertcontrols', '1' ); ?>,
        loop: <?php echo $params->get( 'slideshow_loop', '1' ); ?>,
        duration: <?php echo $params->get( 'slideshow_duration', '1000' ); ?>,
        containerWidth: <?php echo $params->get( 'slideshow_container_width', '960' ); ?>,
        containerHeight: <?php echo $params->get( 'slideshow_container_height', '450' ); ?>,
        slideWidth: <?php echo $params->get( 'slideshow_slide_width', '960' ); ?>,
        slideHeight: <?php echo $params->get( 'slideshow_slide_height', '450' ); ?>
    });
});
</script>
<?php } ?>

<?php } ?>
