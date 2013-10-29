<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php
JHtml::_('script', 'jquery.jcarousel.min.js', 'modules/mod_featureditems_items/media/pikachoose/');
JHtml::_('script', 'jquery.pikachoose.full.js', 'modules/mod_featureditems_items/media/pikachoose/');
?>

<div class="images-list-wrapper">

<ul class="images-list">

<?php foreach ($items as $item) { ?>

    <li class="image wrap">
    
        <div class="image-frame">
            <a href="<?php echo JRoute::_( $item->url ); ?>">
                <div class="feature-meta">
                    <h4 class="label"><?php echo JText::_( $item->label ); ?></h4>
                    <div class="short-title"><?php echo $item->short_title; ?></div>
                </div>
                <div class="feature-info">
                    <div class="long-title"><?php echo $item->long_title; ?></div>
                    <div class="description"><?php echo $item->content; ?></div>
                </div>            
                <img src="<?php echo $item->image_src; ?>" alt="<?php echo htmlspecialchars( $item->long_title ); ?>" title="<?php echo htmlspecialchars( $item->long_title ); ?>" > 
            </a>
        </div>

    </li>
    
<?php } ?>

</ul>

<?php if ($count > '1') { ?>

<script type="text/javascript">
jQuery(document).ready(function () {
    jQuery("#module-<?php echo $module->id; ?> .images-list").PikaChoose({
        text: { play: "", stop: "", previous: "", next: "", loading: "Loading" },
        showCaption:false,
        hoverPause:true
    });
});
</script>
<?php } ?>

</div>