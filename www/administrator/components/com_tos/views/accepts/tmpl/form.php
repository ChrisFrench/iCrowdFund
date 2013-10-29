<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" enctype="multipart/form-data" >

			<table class="table table-striped table-bordered">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'User ID' ); ?>:
					</td>
					<td>
						<?php echo @$row->user_id; ?>
					</td>
				</tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Terms ID' ); ?>:
                    </td>
                    <td>
                        <?php echo @$row->terms_id; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'IP ADDRESS' ); ?>:
                    </td>
                    <td>
                        <?php echo @$row->ip_address; ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Accepted Date' ); ?>:
                    </td>
                    <td>
                        <?php echo @$row->created_date; ?>
                    </td>
                </tr>
            
			</table>
	
			<input type="hidden" name="task" value="" />

</form>