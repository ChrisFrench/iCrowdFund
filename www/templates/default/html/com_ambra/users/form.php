<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('behavior.mootools');
        JHTML::_('behavior.framework', true); ?>
<?php JHTML::_('script', 'ambra_edit.js', 'media/com_ambra/js/'); ?>
<?php //$row = @$this->row; 
 $row = JFactory::getUser();
?>
<?php $categories = @$this->categories; ?>
<?php $userdata = @$this->userdata; ?>

<div class='componentheading'>
    <span><?php echo JText::_( "Edit Profile" ); ?></span>
</div>

<div id="ambra_edituser" class="edituser default">

    <?php $validationUrl = JRoute::_( "index.php?option=com_ambra&view=users&format=raw&task=validate", true, $this->params->get('usesecure') ); ?>
    <form name="ambra_edituser_form" id="ambra_edituser_form" action="<?php echo JRoute::_( 'index.php', true, $this->params->get('usesecure') ); ?>" onsubmit="ambraFormValidation( '<?php echo $validationUrl; ?>', 'message-container', this.task.value, this )" method="post" enctype="multipart/form-data" >
        
    <div id="message-container"></div>

    <?php if (!empty($this->onBeforeEditUser)) : ?>
        <div id='onBeforeDisplay_wrapper'>
        <?php echo $this->onBeforeEditUser; ?>
        </div>
    <?php endif; ?>
    
    <div id="edituser_maincolumn<?php if (empty($this->onEditUserRightColumn)) { echo "_full"; } ?>">
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Name').' '.DSCGrid::required('*'); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->name; ?>" name="name" id="name" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-name"></div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Username').' '.DSCGrid::required('*'); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->username; ?>" onkeyup="ambraCheckUsername( 'message-username', document.ambra_edituser_form );" name="username" id="username" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-username"></div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Email').' '.DSCGrid::required('*'); ?>
            </div>        
            <div class="form_input">
                <input value="<?php echo @$row->email; ?>" onkeyup="ambraCheckEmail( 'message-email', document.ambra_edituser_form );" name="email" id="email" type="text" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-email"></div>
        </div>

        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('New Password').' '.DSCGrid::required('*'); ?>
            </div>        
            <div class="form_input">
                <input onkeyup="ambraCheckPassword( 'message-password', document.ambra_edituser_form ); ambraCheckPassword2( 'message-password2', document.ambra_edituser_form );" name="password" id="password" type="password" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-password"></div>
        </div>
        
        <div class="form_item">
            <div class="form_key">
                <?php echo JText::_('Verify New Password').' '.DSCGrid::required('*'); ?>
            </div>        
            <div class="form_input">
                <input onkeyup="ambraCheckPassword2( 'message-password2', document.ambra_edituser_form );" name="password2" id="password2" type="password" class="inputbox" size="50" />
            </div>
            <div class="form_message" id="message-password2"></div>
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
                    <?php $avatar = Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatar( $row->id , true); ?>
                    <div><img src="<?php echo $avatar->pic; ?>" />
                        <div class="caption"><?php echo $avatar->type; ?> </div></div>

                </div>
            </div>
        </div>
        
        <div class="reset"></div>
        
        <?php foreach ($categories as $category) : ?>
            <div class="profile_header">
                <span><?php echo JText::_( $category->category_name ); ?></span>
            </div>
            
            <?php foreach ($category->fields as $field) : ?>
                <div class="form_item">
                    <div class="form_key">
                        <?php echo JText::_( $field->field_name ); if (!empty($field->required)) { echo ' '.DSCGrid::required(); } ?>
                    </div>        
                    <div class="form_input">
                        <?php $fieldname = $field->db_fieldname; ?>
                        <?php echo Ambra::getClass( "AmbraField", 'library.field' )->display( $field, 'userdata', $userdata->$fieldname ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
                
            <div class="reset"></div>            
        <?php endforeach; ?>

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
    
    <input onclick="ambraSubmitForm('save', document.ambra_edituser_form);" value="<?php echo JText::_('Save'); ?>" type="button" class="btn btn-primary" />
    <a class="btn btn-danger" href="<?php echo JRoute::_( "index.php?option=com_ambra&view=users" ); ?>"><?php echo JText::_( "Cancel" ); ?></a>

    <input type="hidden" name="profile_id" value="<?php echo $userdata->profile_id; ?>" />
    <input type="hidden" name="option" value="com_ambra" />
    <input type="hidden" name="view" value="users" />
    <input type="hidden" name="task" id="task" value="" />
    <input type="hidden" name="return" value="<?php echo @$this->return; ?>" />
    
    <?php echo JHTML::_( 'form.token' ); ?>
    </form>
    
</div>