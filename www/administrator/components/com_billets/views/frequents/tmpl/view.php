<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" >

	<?php echo DSCGrid::checkoutnotice( @$row ); ?>
	
	<fieldset>
		<legend><?php echo JText::_('COM_BILLETS_FORM'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<label for="title">
						<?php echo JText::_('COM_BILLETS_TITLE'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="title" id="title" size="48" maxlength="250" value="<?php echo @$row->title; ?>" />
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="enabled">
						<?php echo JText::_('COM_BILLETS_ENABLED'); ?>:
						</label>
					</td>
					<td>
						<?php echo JHTML::_('select.booleanlist', 'enabled', '', @$row->enabled ) ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="description">
						<?php echo JText::_('COM_BILLETS_DESCRIPTION'); ?>:
						</label>
					</td>
					<td>
						<?php
						$fulltext = stripslashes( $row->description );
						$dispatcher = JDispatcher::getInstance();
						$dispatcher->trigger( 'onBBCode_RenderText', array(&$fulltext) );
						echo $fulltext;
						?>
					</td>
				</tr>
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->id?>" />
			<input type="hidden" name="task" id="task" value="" />
	</fieldset>
</form>