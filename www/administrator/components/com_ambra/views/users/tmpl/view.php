<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('stylesheet', 'ambra.css', 'media/com_ambra/css/'); ?>
<?php $form = @$this->form; ?>
<?php $user = JFactory::getUser(); ?>
<?php $row = @$this->row; ?>
<?php $categories = @$this->categories; ?>
<?php $userdata = @$this->userdata; ?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
    
    <div id="message-container"></div>

    <?php if (!empty($this->onBeforeDisplayUser)) : ?>
        <div id='onBeforeDisplay_wrapper'>
        <?php echo $this->onBeforeDisplayUser; ?>
        </div>
    <?php endif; ?>
    
    <div id="ambra_user" class="users default">
        <div id="user_maincolumn">
        
        <div class="profile_header">
            <span><?php echo JText::_( AmbraConfig::getInstance()->get( 'defaults_title', 'Basic Information' ) ); ?></span>
        </div>

        <div class="profile_item">
            <div class="profile_key">
                <?php echo JText::_( 'Name' ); ?>
            </div>        
            <div class="profile_value">
                <?php echo $row->name; ?>
            </div>
        </div>

        <div class="profile_item">
            <div class="profile_key">
                <?php echo JText::_( 'Username' ); ?>
            </div>        
            <div class="profile_value">
                <?php echo $row->username; ?>
            </div>
        </div>

        <?php $canViewEmail = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->canView( JFactory::getUser(), JFactory::getUser( $row->id ), 'email' ); ?>
        <?php if ($canViewEmail) : ?>
            <div class="profile_item">
                <div class="profile_key">
                    <?php echo JText::_( 'Primary Email' ); ?>
                </div>        
                <div class="profile_value">
                    <?php echo $row->email; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="profile_item">
            <div class="profile_key">
                <?php echo JText::_( 'User since' ); ?>
            </div>        
            <div class="profile_value">
                <?php echo JHTML::_('date', $row->registerDate, "%a, %d %b %Y"); ?>
            </div>
        </div>
        
        <?php if (!empty($userdata->profile_name)) : ?>
        <div class="profile_item">
            <div class="profile_key">
                <?php echo JText::_( 'Profile Type' ); ?>
            </div>        
            <div class="profile_value">
                <?php echo JText::_( $userdata->profile_name ) ; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($userdata->profile_linkedin) || !empty($userdata->profile_facebook) || !empty($userdata->profile_twitter) || !empty($userdata->profile_youtube)) : ?>
        
            <div class="reset"></div>
            
            <div class="profile_header">
                <span><?php echo JText::_( "Online Profiles" ); ?></span>
            </div>
    
            <?php if (!empty($userdata->profile_linkedin)) : ?>
            <div class="profile_item">
                <div class="profile_key">
                    <img src="<?php echo Ambra::getUrl( "images" )."linkedin30.png"; ?>" alt="<?php echo JText::_( "LinkedIn" ); ?>" />
                </div>        
                <div class="profile_value">
                    <a target="_blank" href="<?php echo $userdata->profile_linkedin; ?>"><?php echo $userdata->profile_linkedin; ?></a>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($userdata->profile_facebook)) : ?>
            <div class="profile_item">
                <div class="profile_key">
                    <img src="<?php echo Ambra::getUrl( "images" )."facebook30.png"; ?>" alt="<?php echo JText::_( "Facebook" ); ?>" />
                </div>        
                <div class="profile_value">
                    <a target="_blank" href="<?php echo $userdata->profile_facebook; ?>"><?php echo $userdata->profile_facebook; ?></a>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($userdata->profile_twitter)) : ?>
            <div class="profile_item">
                <div class="profile_key">
                    <img src="<?php echo Ambra::getUrl( "images" )."twitter30.png"; ?>" alt="<?php echo JText::_( "Twitter" ); ?>" />
                </div>        
                <div class="profile_value">
                    <a target="_blank" href="<?php echo $userdata->profile_twitter; ?>"><?php echo $userdata->profile_twitter; ?></a>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($userdata->profile_youtube)) : ?>
            <div class="profile_item">
                <div class="profile_key">
                    <img src="<?php echo Ambra::getUrl( "images" )."youtube30.png"; ?>" alt="<?php echo JText::_( "YouTube" ); ?>" />
                </div>        
                <div class="profile_value">
                    <a target="_blank" href="<?php echo $userdata->profile_youtube; ?>"><?php echo $userdata->profile_youtube; ?></a>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="reset"></div>
        
        <div class="profile_header">
            <span><?php echo JText::_( "Points Earned" ); ?></span>
        </div>

        <div class="profile_item">
            <div class="profile_key">
                <?php echo JText::_( 'Lifetime Total Points Earned' ); ?>
            </div>        
            <div class="profile_value">
                <?php echo (int) $userdata->points_total; ?>
            </div>
        </div>
        
        <div class="profile_item">
            <div class="profile_key">
                <?php echo JText::_( 'Current Available Points' ); ?>
            </div>        
            <div class="profile_value">
                <?php echo (int) $userdata->points_current; ?>
            </div>
        </div>

        <div class="profile_item">
            <div class="profile_key">
                <?php echo JText::_( 'Points Earned Today' ); ?>
            </div>        
            <div class="profile_value">
                <?php echo (int) $this->pointhistory_today; ?>
            </div>
        </div>
                
        <?php $canViewEmail = Ambra::getClass( "AmbraHelperUser", "helpers.user" )->canView( JFactory::getUser(), JFactory::getUser( $row->id ), 'email' ); ?>
        <?php if ($canViewEmail) : ?>
            <div class="profile_item">
                <div class="profile_key">
                    <?php echo JText::_( 'Maximum Points That Can Be Earned Today' ); ?>
                </div>        
                <div class="profile_value">
                    <?php echo $this->max_points_per_day; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="reset"></div>
        
        <?php foreach ($categories as $category) : ?>
            <div class="profile_header">
                <span><?php echo JText::_( $category->category_name ); ?></span>
            </div>
            
            <?php foreach ($category->fields as $field) : ?>
                <?php $fieldname = $field->db_fieldname; ?>
                <?php $value = Ambra::getClass( "AmbraField", 'library.field' )->displayValue( $field, $userdata->$fieldname ); ?>
                <?php if (!empty($value)) : ?>
                    <div class="profile_item">
                        <div class="profile_key">
                            <?php echo JText::_( $field->field_name ); ?>
                        </div>        
                        <div class="profile_value">
                            <?php echo $value; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
                
            <div class="reset"></div>            
        <?php endforeach; ?>

        <div class="reset"></div>

        <?php
        // if there are plugins with config options, display them accordingly
        if ($this->items || $this->items_tabs || $this->items_sliders) 
        {                   
            // Fire two events, one to display plugins that choose tabs
            $tab=1;
            $pane=1;
            $maxtabs=5; // number of tabs per pane
            for ($i=0, $count=count($this->items_tabs); $i < $count; $i++) {
                if ($pane == 1) {
                    echo $this->tabs->startPane( "pane_$pane" );
                }
                $item = $this->items_tabs[$i];
                if ($tab > $maxtabs) {
                    $tab = 1;
                    $pane = $pane + 1;
                    echo $this->tabs->endPane();
                    echo $this->tabs->startPane( "pane_$pane" );                
                }
                echo $this->tabs->startPanel( JText::_( $item->element ), $item->element );
                
                // load the plugin
                    $import = JPluginHelper::importPlugin( strtolower( 'Ambra' ), $item->element );
                // fire plugin
                    $dispatcher =& JDispatcher::getInstance();
                    $dispatcher->trigger( 'onDisplayProfileTabs', array( $item, $row ) );
                    
                echo $this->tabs->endPanel();
                if ($i == $count-1) {
                    echo $this->tabs->endPane();
                }
            }
            
            // one to display plugins that choose sliders
            $tab=1;
            $pane=1;
            $maxtabs=-1; // number of tabs per pane
            for ($i=0, $count=count($this->items_sliders); $i < $count; $i++) {
                if ($pane == 1) {
                    echo $this->sliders->startPane( "pane_$pane" );
                }
                $item = $this->items_sliders[$i];
                if ($tab > $maxtabs && $maxtabs > 0) {
                    $tab = 1;
                    $pane = $pane + 1;
                    echo $this->sliders->endPane();
                    echo $this->sliders->startPane( "pane_$pane" );             
                }
                echo $this->sliders->startPanel( JText::_( $item->element ), $item->element );
                
                // load the plugin
                    $import = JPluginHelper::importPlugin( strtolower( 'Ambra' ), $item->element );
                // fire plugin
                    $dispatcher =& JDispatcher::getInstance();
                    $dispatcher->trigger( 'onDisplayProfileSliders', array( $item, $row ) );
                    
                echo $this->sliders->endPanel();
                
                if ($i == $count-1) 
                {
                    echo $this->sliders->endPane();
                }
            }

            // and one for plugins that choose legends
            for ($i=0; $i < count($this->items); $i++) 
            {
                $item = $this->items[$i];
                $dispatcher =& JDispatcher::getInstance();
                $dispatcher->trigger( 'onDisplayProfile', array( $item, $row ) );
            }
        }
        ?>        
        </div>
        
        <?php //if (!empty($this->onDisplayUserRightColumn)) : ?>
        <div id="user_rightcolumn">
            <div id="user_profile_avatar">
                <img src="<?php echo Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatar( $row->id ); ?>" />
            </div>
            
            <?php if (Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->canEdit( JFactory::getUser(), JFactory::getUser( $row->id ) )) : ?>
                <div id="user_profile_edit_link">
                    <a href="<?php echo JRoute::_( "index.php?option=com_ambra&view=users&task=edit&id=".$row->id ); ?>"><?php echo JText::_( "Edit Profile" ); ?></a>
                </div>
            <?php endif; ?>
                    
            <div id='onDisplayRightColumn_wrapper'>
                <?php echo $this->onDisplayUserRightColumn; ?>
            </div>
        </div>
        <?php //endif; ?>
        
    </div>
    
    <div class="reset"></div>
    
    <?php if (!empty($this->onAfterDisplayUser)) : ?>
        <div id='onAfterDisplay_wrapper'>
        <?php echo $this->onAfterDisplayUser; ?>
        </div>
    <?php endif; ?>
    
    <div class="reset"></div>

    <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="users" />
    <input type="hidden" name="task" id="task" value="" />
    
    <?php echo JHTML::_( 'form.token' ); ?>
</form>