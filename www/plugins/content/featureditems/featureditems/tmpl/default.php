<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $image_width = $vars->medium_width; ?>
<?php $image_height = $vars->medium_height; ?>
<?php $items = $vars->items; ?>
<?php $count = count( $items ); ?>

<ul class="horiz features large">

<?php foreach ($items as $item) { ?>
    <li>
        <div class="feature">
            <a href="<?php echo JRoute::_( $item->url ); ?>">
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
