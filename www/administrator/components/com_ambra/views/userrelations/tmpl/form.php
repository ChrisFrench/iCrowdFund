<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('stylesheet', 'ambra.css', 'media/com_ambra/css/'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; 
JFilterOutput::objectHTMLSafe( $row );
?>

<div id="ambra_new_user_relation" >
	
	<?php $validationUrl = JRoute::_( "index.php?option=com_ambra&view=userrelations&format=raw&task=validate", true ); ?>
	<form name="adminForm" id="adminForm" action="<?php echo JRoute::_( 'index.php', true ); ?>" onsubmit="ambraFormValidation( '<?php echo $validationUrl; ?>', 'message-container', this.task.value, this )" method="post" enctype="multipart/form-data" >
	
		<div id="message-container"></div>
		
		<div class="profile_header">
	        <span><?php echo JText::_( "Add New Relation" ); ?></span>
	    </div>
	    
	    <div class="form_item">
	        <div title="User From" class="form_key"> 
	              <?php echo JText::_( "User ID or Username" ).": "; ?>
	        </div>        
	        <div class="form_input">              
	              <input id="new_relationship_user_from" name="new_relationship_user_from" title="User From" size="15" type="text" />
	        </div>
	    </div>
	        
	    <div class="form_item">
	        <div class="form_key">
	              <?php echo JText::_( "Relationship Type" ); ?>
	        </div>        
	        <div class="form_input">
	              <?php echo AmbraSelect::relationship('', 'new_relationship_type'); ?>
	        </div>
	    </div>  
	    
	    <div class="form_item">
	        <div title="User To" class="form_key"> 
	              <?php echo JText::_( "User ID or Username" ).": "; ?>
	        </div>        
	        <div class="form_input">
	              <input id="new_relationship_user_to" name="new_relationship_user_to" title="User To" size="15" type="text" />
	        </div>
	    </div>  
	    
	    <input type="hidden" name="id" value="<?php echo @$row->userrelation_id; ?>" />
	    <input type="hidden" name="option" value="com_ambra" />
	    <input type="hidden" name="view" value="userrelations" />
	    <input type="hidden" name="task" id="task" value="" /> 
	    <input type="hidden" name="return" value="<?php echo @$this->return; ?>" />
	
		<?php echo JHTML::_( 'form.token' ); ?>
		
    </form>    
</div>