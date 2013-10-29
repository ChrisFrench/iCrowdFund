<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $items = @$this->items; ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Select A Profile" ); ?></span>
</div>

<div id="ambra_selectprofile" class="registration selectprofile">

    <?php if (!empty($this->onBeforeDisplaySelectProfileForm)) : ?>
        <div id='onBeforeDisplay_wrapper'>
        <?php echo $this->onBeforeDisplaySelectProfileForm; ?>
        </div>
    <?php endif; ?>
    
    <div id="selectprofile_maincolumn">
        <?php foreach ($items as $item) : ?>
            <div class="selectprofile_item">
                <div class="selectprofile_icon">
                    <a href="<?php echo JRoute::_( "index.php?option=com_ambra&view=registration&profile_id=".$item->profile_id ); ?>">
                    <?php echo Ambra::getClass( "AmbraHelperProfile", 'helpers.profile' )->getImage( $item->profile_id ); ?>
                    </a>
                </div>
            
                <div class="selectprofile_title">
                    <a href="<?php echo JRoute::_( "index.php?option=com_ambra&view=registration&profile_id=".$item->profile_id ); ?>">
                    <?php echo JText::_( $item->profile_name ); ?>
                    </a>
                </div>
                <div class="selectprofile_description">
                    <?php echo JText::_( $item->profile_description ); ?>
                </div>
            </div>
            
            <div class="reset"></div>            
        <?php endforeach; ?>
    </div>
        
    <?php if (!empty($this->onAfterDisplaySelectProfileForm)) : ?>
        <div id='onAfterDisplay_wrapper'>
        <?php echo $this->onAfterDisplaySelectProfileForm; ?>
        </div>
    <?php endif; ?>
    
</div>