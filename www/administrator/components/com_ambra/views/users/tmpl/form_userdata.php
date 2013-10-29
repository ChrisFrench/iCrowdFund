<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('stylesheet', 'ambra.css', 'media/com_ambra/css/'); ?>
<?php $row = @$this->row; ?>
<?php $categories = @$this->categories; ?>
<?php $userdata = @$this->userdata; ?>

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