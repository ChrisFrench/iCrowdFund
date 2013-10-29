<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $image_width = $vars->medium_width; ?>
<?php $image_height = $vars->medium_height; ?>
<?php $items = $vars->items; ?>
<?php $count = count( $items ); ?>
<?php JHTML::_('behavior.modal'); ?>

<ul class="horiz features large">

<?php foreach ($items as $item) { ?>
    <?php 
    $handler = "{handler: 'iframe', size: {x: $item->lightbox_x, y: $item->lightbox_y} }";
    ?>
    <li>
        <div class="feature">
            <a href="<?php echo JRoute::_( $item->url ); ?>" <?php if ($item->url_target == '1') { echo "target='_blank'"; } elseif ($item->url_target == '2') { echo "class='modal' rel=\"$handler\""; } ?>>
                <div class="feature-info">
                    <h4><?php echo JText::_( $item->label ); ?></h4>
                    <?php echo $item->content; ?>
                </div>            
                <img src="<?php echo $item->image_src; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" > 
                <span class="opacity"></span> 
            </a>
        </div>
    </li>   
<?php } ?>

</ul>
