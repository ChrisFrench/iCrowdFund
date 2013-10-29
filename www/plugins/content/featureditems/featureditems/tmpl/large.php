<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $image_width = $vars->large_width; ?>
<?php $image_height = $vars->large_height; ?>
<?php $items = $vars->items; ?>
<?php $count = count( $items ); ?>

<?php if ($count > '1') { ?>
<ul class="horiz features">
<?php } ?>

<?php foreach ($items as $item) { ?>

    <?php if ($count > '1') { ?>
    <li>
    <?php } ?>
    
        <div class="feature large">
            <a href="<?php echo JRoute::_( $item->url ); ?>">
                <div class="feature-info">
                    <h4><?php echo JText::_( $item->label ); ?></h4>
                    <?php echo $item->content; ?>
                </div>            
                <img src="<?php echo $item->image_src; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" > 
                <span class="opacity"></span> 
            </a>
        </div>

    <?php if ($count > '1') { ?>
    </li>
    <?php } ?>
    
<?php } ?>

<?php if ($count > '1') { ?>
</ul>
<?php } ?>