<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" id="adminForm" name="adminForm" enctype="multipart/form-data" >

			<table class="table table-striped table-bordered">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'User ID' ); ?>:
					</td>
					<td>
						<input  name="joomla_user_id" value="<?php echo @$row->joomla_user_id; ?>" size="48" maxlength="250" type="text" />
					</td>
				</tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Wepay User ID' ); ?>:
                    </td>
                    <td>
                        <input name="wepay_user_id" value="<?php echo @$row->wepay_user_id; ?>" size="48" maxlength="250" type="text" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Wepay Access Token' ); ?>:
                    </td>
                    <td>
                        <input name="wepay_access_token" value="<?php echo @$row->wepay_access_token; ?>"  maxlength="250" type="text" class="input-xxlarge span-4" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Wepay Token Type' ); ?>:
                    </td>
                    <td>
                        <input name="wepay_token_type" value="<?php echo @$row->wepay_token_type; ?>" size="75" maxlength="250" type="text" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Oauth Code' ); ?>:
                    </td>
                    <td>
                        <input name="oauth_code" value="<?php echo @$row->oauth_code; ?>" size="75" maxlength="250" type="text" />
                    </td>
                </tr>
                
                
			</table>
			<input type="hidden" name="id" value="<?php echo @$row->id; ?>" />
			<input type="hidden" name="task" value="" />

</form>