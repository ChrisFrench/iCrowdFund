<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('stylesheet', 'ambra.css', 'media/com_ambra/css/'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php JHTML::_('script', 'ambra_edit.js', 'media/com_ambra/js/'); ?>
<?php $row = @$this->row; ?>
<?php $categories = @$this->categories; ?>
<?php $userdata = @$this->userdata; ?>

<div id="ambra_edituser" class="edituser default">

    <?php $validationUrl = JRoute::_( "index.php?option=com_ambra&view=users&format=raw&task=validate", true ); ?>
    <form name="adminForm" id="adminForm" action="<?php echo JRoute::_( 'index.php', true ); ?>" onsubmit="ambraFormValidation( '<?php echo $validationUrl; ?>', 'message-container', this.task.value, this )" method="post" enctype="multipart/form-data" >
        
    <div id="message-container"></div>

    <?php if (!empty($this->onBeforeEditUser)) : ?>
        <div id='onBeforeDisplay_wrapper'>
        <?php echo $this->onBeforeEditUser; ?>
        </div>
    <?php endif; ?>
    
    <div id="edituser_maincolumn<?php if (empty($this->onEditUserRightColumn)) { echo "_full"; } ?>">
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Name').' '.AmbraGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->name; ?>" name="name" id="name" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-name"></div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Username').' '.AmbraGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->username; ?>" onkeyup="ambraCheckUsername( 'message-username', document.adminForm );" name="username" id="username" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-username"></div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Email').' '.AmbraGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->email; ?>" onkeyup="ambraCheckEmail( 'message-email', document.adminForm );" name="email" id="email" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-email"></div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('New Password').' '.AmbraGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input onkeyup="ambraCheckPassword( 'message-password', document.adminForm ); ambraCheckPassword2( 'message-password2', document.adminForm );" name="password" id="password" type="password" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-password"></div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Verify New Password').' '.AmbraGrid::required(); ?>
            </div>        
            <div class="form_input">
                <input onkeyup="ambraCheckPassword2( 'message-password2', document.adminForm );" name="password2" id="password2" type="password" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-password2"></div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_( 'Block User' ); ?>
            </div>       
            <div class="form_input">
               <?php echo JHTML::_('select.booleanlist', 'block', 'class="inputbox"', (@$row->block) ); ?>
            </div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_( "Profile Type" ); ?>
            </div>        
            <div class="form_input">
                <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => "ambraChangeProfileType( 'profile-userdata', document.adminForm );"); ?>
                <?php echo AmbraSelect::profile( @$userdata->profile_id, 'profile_id', $attribs, 'profile_id', true ); ?>
            </div>
        </div>
                
        <div class="reset"></div>
        
        <div class="profile_header">
            <span><?php echo JText::_( "User Points" ); ?></span>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Liftetime Total'); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->points_total; ?>" name="points_total" id="points_total" type="text" class="inputbox" size="10" />
            </div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Current Total'); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->points_current; ?>" name="points_current" id="points_current" type="text" class="inputbox" size="10" />
            </div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Maximum Allowed Total'); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->points_maximum; ?>" name="points_maximum" id="points_maximum" type="text" class="inputbox" size="10" />
            </div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Maximum Allowed Per Day'); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->points_maximum_per_day; ?>" name="points_maximum_per_day" id="points_maximum_per_day" type="text" class="inputbox" size="10" />
            </div>
        </div>
                
        <div class="reset"></div>
        
        <div class="profile_header">
            <span><?php echo JText::_( "Avatar" ); ?></span>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_( "Upload New Avatar" ); ?>
            </div>        
            <div class="form_input">
                <input type="file" name="avatar" />
            </div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_( "Current Avatar" ); ?>
            </div>        
            <div class="form_input">
                <div id="user_profile_avatar">
                    <img src="<?php echo Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatar( $row->id ); ?>" />
                </div>
            </div>
        </div>
        
 		<div class="form_item">
            <div class="form_key">
                <?php echo JText::_( 'Is manual aproval for the points' ); ?>
            </div>       
            <div class="profile_value">
               <?php echo JHTML::_('select.booleanlist', 'is_manual_approval', 'class="inputbox"', (@$row->is_manual_approval) ); ?>
            </div>
        </div>
        

        <div class="reset"></div>
        
        <div class="profile_header">
            <span><?php echo JText::_( "Online Profiles" ); ?></span>
        </div>

        <div class="form_item">
            <div class="form_key">
                <img src="<?php echo Ambra::getUrl( "images" )."linkedin30.png"; ?>" alt="<?php echo JText::_( "LinkedIn" ); ?>" />
            </div>        
            <div class="form_input">
                <input name="userdata[profile_linkedin]" id="profile_linkedin" value="<?php echo $userdata->profile_linkedin; ?>" type="text" class="inputbox" size="50" />
            </div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <img src="<?php echo Ambra::getUrl( "images" )."facebook30.png"; ?>" alt="<?php echo JText::_( "Facebook" ); ?>" />
            </div>        
            <div class="form_input">
                <input name="userdata[profile_facebook]" id="profile_facebook" value="<?php echo $userdata->profile_facebook; ?>" type="text" class="inputbox" size="50" />
            </div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <img src="<?php echo Ambra::getUrl( "images" )."twitter30.png"; ?>" alt="<?php echo JText::_( "Twitter" ); ?>" />
            </div>        
            <div class="form_input">
                <input name="userdata[profile_twitter]" id="profile_twitter" value="<?php echo $userdata->profile_twitter; ?>" type="text" class="inputbox" size="50" />
            </div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <img src="<?php echo Ambra::getUrl( "images" )."youtube30.png"; ?>" alt="<?php echo JText::_( "YouTube" ); ?>" />
            </div>        
            <div class="form_input">
                <input name="userdata[profile_youtube]" id="profile_youtube" value="<?php echo $userdata->profile_youtube; ?>" type="text" class="inputbox" size="50" />
            </div>
        </div>
        
        <div id="profile-userdata">
            <?php foreach ($categories as $category) : ?>
                <div class="profile_header">
                    <span><?php echo JText::_( $category->category_name ); ?></span>
                </div>
                
                <?php foreach ($category->fields as $field) : ?>
                    <div class="form_item">
                        <div class="form_key">
                            <?php echo JText::_( $field->field_name ); if (!empty($field->required)) { echo ' '.AmbraGrid::required(); } ?>
                        </div>        
                        <div class="form_input">
                            <?php $fieldname = $field->db_fieldname; ?>
                            <?php echo Ambra::getClass( "AmbraField", 'library.field' )->display( $field, 'userdata', $userdata->$fieldname ); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                    
                <div class="reset"></div>            
            <?php endforeach; ?>
        </div>
        
        <div class="reset"></div>
        
        <?php if (!empty($this->onAfterEditUser)) : ?>
            <div id='onAfterDisplay_wrapper'>
            <?php echo $this->onAfterEditUser; ?>
            </div>
        <?php endif; ?>
        
    </div>
    
    <?php if (!empty($this->onEditUserRightColumn)) : ?>
    <div id="edituser_rightcolumn">
        <div id='onDisplayRightColumn_wrapper'>
        <?php echo $this->onEditUserRightColumn; ?>
        </div>
    </div>
    <?php endif; ?>

    <input type="hidden" name="id" value="<?php echo $row->id; ?>" />
    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="users" />
    <input type="hidden" name="task" id="task" value="" />
    <input type="hidden" name="return" value="<?php echo @$this->return; ?>" />
    
    <?php echo JHTML::_( 'form.token' ); ?>
    </form>
    
</div>