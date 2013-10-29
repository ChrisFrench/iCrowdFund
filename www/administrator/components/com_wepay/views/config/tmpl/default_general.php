<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
	echo $this -> sliders -> startPanel(JText::_("COM_WEPAY_GENERAL_SETTINGS"), 'general');
?>

<table class="adminlist">
	<tbody>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_USE_PRODUCTION'); ?> </th>
			<td> <?php echo JHTML::_('select.booleanlist', 'use_production', 'class="inputbox"', $this -> row -> get('use_production', '1')); ?> </td>
			<td></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_CLIENT_ID'); ?> </th>
			<td>
			<input name="client_id" value="<?php echo $this -> row -> get('client_id', ''); ?>" type="text" size="40"/>
			</td>
			<td><?php echo JText::_('COM_WEPAY_CLIENT_ID_DESC'); ?></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_CLIENT_SECRET'); ?> </th>
			<td>
			<input name="client_secret" value="<?php echo $this -> row -> get('client_secret', ''); ?>" type="text" size="40"/>
			</td>
			<td><?php echo JText::_('COM_WEPAY_CLIENT_SECRET_DESC'); ?></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_ACCESS_TOKEN'); ?> </th>
			<td>
			<input name="access_token" value="<?php echo $this -> row -> get('access_token', ''); ?>" type="text" size="40"/>
			</td>
			<td><?php echo JText::_('COM_WEPAY_ACCESS_TOKEN_DESC'); ?></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_ACCOUNT_ID'); ?> </th>
			<td>
			<input name="account_id" value="<?php echo $this -> row -> get('account_id', ''); ?>" type="text" size="40"/>
			</td>
			<td><?php echo JText::_('COM_WEPAY_ACCOUNT_ID_DESC'); ?></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_OAUTH_ITEMID'); ?> </th>
			<td>
			<input name="oauth_itemid" value="<?php echo $this -> row -> get('oauth_itemid', ''); ?>" type="text" size="40"/>
			</td>
			<td><?php echo JText::_('COM_WEPAY_OAUTH_ITEMID_DESC'); ?></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_OAUTH_REDIRECT_ITEMID'); ?> </th>
			<td>
			<input name="oauth_itemid" value="<?php echo $this -> row -> get('oauth_redirect', ''); ?>" type="text" size="40"/>
			</td>
			<td><?php echo JText::_('COM_WEPAY_OAUTH_REDIRECT_ITEMID_DESC'); ?></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_DASHBOARD_ITEMID'); ?> </th>
			<td>
			<input name="dashboard_itemid" value="<?php echo $this -> row -> get('dashboard_itemid', ''); ?>" type="text" size="40"/>
			</td>
			<td><?php echo JText::_('COM_WEPAY_DASHBOARD_ITEMID_DESC'); ?></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_SET_DATE_FORMAT'); ?> </th>
			<td>
			<input name="date_format" value="<?php echo $this -> row -> get('date_format', '%a, %d %b %Y, %I:%M%p'); ?>" type="text" size="40"/>
			</td>
			<td> <?php echo JText::_("COM_WEPAY_SET_DATE_FORMAT_TIP"); ?> </td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_SHOW_LINKBACK'); ?> </th>
			<td> <?php echo JHTML::_('select.booleanlist', 'show_linkback', 'class="inputbox"', $this -> row -> get('show_linkback', '1')); ?> </td>
			<td></td>
		</tr>
		<tr>
			<th style="width: 25%;"> <?php echo JText::_('COM_WEPAY_INCLUDE_SITE_CSS'); ?> </th>
			<td> <?php echo JHTML::_('select.booleanlist', 'include_site_css', 'class="inputbox"', $this -> row -> get('include_site_css', '1')); ?> </td>
			<td></td>
		</tr>
	</tbody>
</table>
<?php
echo $this -> sliders -> endPanel();
?>