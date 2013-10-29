<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $image_width = "460"; ?>
<?php $image_height = "307"; ?>

<?php if ($count > '1') { ?>
<ul class="features large">
<?php } ?>

<?php foreach ($items as $item) { ?>

    <?php if ($count > '1') { ?>
    <li>
    <?php } ?>
    
        <div class="feature large">
            <a href="<?php echo JRoute::_( $item->url ); ?>">
                <h2>
                    <span>
                        <?php echo $item->short_title; ?>
                    </span>
                </h2>
                <img src="<?php echo $item->image_src; ?>" width="<?php echo $image_width; ?>" height="<?php echo $image_height; ?>" alt="<?php echo strip_tags( $item->short_title ); ?>" title="<?php echo strip_tags( $item->short_title ); ?>" />
                <div class="feature-info">
                    <?php if (!empty($item->label)) { ?><h5><?php echo $item->label; ?></h5><?php } ?>
                    <?php echo $item->content; ?>
                </div>
            </a>
        </div>

    <?php if ($count > '1') { ?>
    </li>
    <?php } ?>
    
<?php } ?>

<?php if ($count > '1') { ?>
</ul>
<?php } ?>