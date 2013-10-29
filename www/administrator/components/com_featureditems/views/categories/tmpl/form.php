<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row;
JFilterOutput::objectHTMLSafe( $row );
?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >
    
    <?php
	// fire plugin event here to enable extending the form
	JDispatcher::getInstance( )->trigger( 'onBeforeDisplayCategoryForm', array( $row ) );
	?>
    
    <table style="width: 100%">
    <tr>
        <td style="vertical-align: top; width: 65%;">

    	   <fieldset>
    		<legend><?php echo JText::_( 'Form' ); ?></legend>
    			<table class="admintable">
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="category_name">
    						<?php echo JText::_( 'Name' ); ?>:
    						</label>
    					</td>
    					<td>
    						<input type="text" name="category_name" id="category_name" size="48" maxlength="250" value="<?php echo @$row->category_name; ?>" />
    					</td>
    				</tr>
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="enabled">
    						<?php echo JText::_( 'Enabled' ); ?>:
    						</label>
    					</td>
    					<td>
    						<?php echo JHTML::_( 'select.booleanlist', 'category_enabled', '', @$row->category_enabled ); ?>
    					</td>
    				</tr>
    			</table>
    
    			<input type="hidden" name="id" value="<?php echo @$row->category_id ?>" />
    			<input type="hidden" name="task" value="" />
        	</fieldset>
    	
            <?php
			// fire plugin event here to enable extending the form
			JDispatcher::getInstance( )->trigger( 'onAfterDisplayCategoryFormMainColumn', array( $row ) );
			?>

        </td>
        <td style="max-width: 35%; min-width: 35%; width: 35%; vertical-align: top;">

        <?php
		// fire plugin event here to enable extending the form
		JDispatcher::getInstance( )->trigger( 'onAfterDisplayCategoryFormRightColumn', array( $row ) );
		?>
        </td>
    </tr>
    </table>

    <?php
	// fire plugin event here to enable extending the form
	JDispatcher::getInstance( )->trigger( 'onAfterDisplayCategoryForm', array( $row ) );
	?>

</form>