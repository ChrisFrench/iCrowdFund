<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

	<?php echo DSCGrid::pagetooltip( JRequest::getVar('view') ); ?>
	
    <?php echo DSCGrid::searchform(@$state->filter,JText::_('Search'), JText::_('Reset') ) ?>
    
	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 5px;">
                	<?php echo JText::_("Num"); ?>
                </th>
                <th style="width: 20px;">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                </th>
                <th style="width: 50px;">
                	<?php echo DSCGrid::sort( 'ID', "tbl.id", @$state->direction, @$state->order ); ?>
                </th>
                <th>
                
                </th>
                <th style="text-align: left;">
                	<?php echo DSCGrid::sort( 'Name', "tbl.name", @$state->direction, @$state->order ); ?>
                	+
                	<?php echo DSCGrid::sort( 'Username', "tbl.username", @$state->direction, @$state->order ); ?>
                	+
                	<?php echo DSCGrid::sort( 'Email', 'tbl.email', @$state->direction, @$state->order); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo DSCGrid::sort( 'Current', "data.points_current", @$state->direction, @$state->order ); ?>
                    +
                    <?php echo DSCGrid::sort( 'Total Points', "data.points_total", @$state->direction, @$state->order ); ?>
                </th>
				<th>
                	<?php echo DSCGrid::sort( 'Last Visit', "tbl.lastvisitDate", @$state->direction, @$state->order ); ?>
                	+
                	<?php echo DSCGrid::sort( 'Registered', "tbl.registerDate", @$state->direction, @$state->order ); ?>
				</th>
				<th>
					<?php echo DSCGrid::sort( 'Enabled', 'tbl.block', @$state->direction, @$state->order); ?>
				</th>
                <th>
                	<?php echo JText::_( "Logged In" ); ?>
                </th>
				<?php foreach (@$this->fields as $field) : ?>
	                <th>
	                	<?php echo DSCGrid::sort( $field->field_name, "data.{$field->db_fieldname}", @$state->direction, @$state->order ); ?>	
	                </th>
                <?php endforeach; ?>
            </tr>
            <tr class="filterline">
                <th colspan="3">
                    <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                        <div class="range">
                        <div class="rangeline">
                            <input type="text" placeholder="<?php echo JText::_('From'); ?>" id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from; ?>" size="5" class="input input-tiny" />
                        </div>
                        <div class="rangeline">
                            <input type="text" placeholder="<?php echo JText::_('To'); ?>" id="filter_id_to" name="filter_id_to" value="<?php echo @$state->filter_id_to; ?>" size="5" class="input input-tiny" />
                        </div>
                    </div>
                    
                    
                </th>
                <th>
                
                </th>
                <th style="text-align: left;">
                    <input id="filter_flex" name="filter_flex" value="<?php echo @$state->filter_flex; ?>" size="25"/>
                    <?php echo AmbraSelect::profile( @$state->filter_profile, 'filter_profile', $attribs, 'profile', true, true ); ?>
                </th>
                <th>
                    <div class="range">
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("From"); ?>:</span> <input id="filter_points_from" name="filter_points_from" value="<?php echo @$state->filter_points_from; ?>" size="5" class="input" />
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("To"); ?>:</span> <input id="filter_points_to" name="filter_points_to" value="<?php echo @$state->filter_points_to; ?>" size="5" class="input" />
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("Type"); ?>:</span>
                            <?php echo AmbraSelect::pointtype( @$state->filter_pointtype, 'filter_pointtype', '', 'pointtype' ); ?>
                        </div>
                    </div>                
                </th>
                <th>
                    <div class="range">
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("From"); ?>:</span>
                            <?php echo JHTML::calendar( @$state->filter_date_from, "filter_date_from", "filter_date_from", JText::_('DATE_FORMAT_LC1') ); ?>
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("To"); ?>:</span>
                            <?php echo JHTML::calendar( @$state->filter_date_to, "filter_date_to", "filter_date_to", JText::_('DATE_FORMAT_LC1') ); ?>
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("Type"); ?>:</span>
                            <?php echo AmbraSelect::datetype( @$state->filter_datetype, 'filter_datetype', '', 'datetype' ); ?>
                        </div>
                    </div>
                </th>
                <th>
                    <?php echo AmbraSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true, 'Enabled State', 'Blocked', 'Enabled' ); ?>
                </th>
                <th>
                    <?php echo AmbraSelect::booleans( @$state->filter_online, 'filter_online', $attribs, 'online', true, 'Login State', 'Online', 'Offline' ); ?>
                </th>
                <?php foreach (@$this->fields as $field) : ?>
                    <th> 
                    </th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <th colspan="20" style="font-weight: normal;">
                    <div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
                    <div style="float: left;"><?php echo @$this->pagination->getListFooter(); ?></div>
                    <select name="format">
                        <option value="html">List</option>
                        <option value="csv">Export CSV</option>
                    <select>  
                </th>
            </tr>
		</thead>
        <tfoot>
            <tr>
                <td colspan="20">
                    <div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
                    <?php echo @$this->pagination->getPagesLinks(); ?>
                      
                </td>
            </tr>
        </tfoot>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td align="center">
					<?php echo $i + 1; ?>
				</td>
                <td style="text-align: center;">
                    <?php echo JHTML::_( 'grid.id', $i, $item->id ); ?>
                </td>
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->id; ?>
					</a>
				</td>
                <td style="text-align: center;">

                
                    <img src="<?php  echo Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->getAvatar( $item->id ); ?>" style="max-width: 36px; max-height: 36px;" />
                </td>
				<td style="text-align: left;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->name; ?>
					</a>
					<?php //if (Ambra::getClass( "AmbraHelperUser", 'helpers.user' )->canEdit( JFactory::getUser(), JFactory::getUser( $item->id ) )) : ?>
					    <span style="float: right;" class="edit_link">
    					    [<a href="<?php echo JRoute::_( "index.php?option=com_ambra&view=users&task=edit&id=".$item->id ); ?>"><?php echo JText::_( "Edit" ); ?></a>]
					    </span>
					<?php // endif; ?>
                    <br />&nbsp;&nbsp;&bull;&nbsp;&nbsp;<?php echo $item->username; ?>
                    <br />&nbsp;&nbsp;&bull;&nbsp;&nbsp;<?php echo $item->email; ?>
					<?php
					if (!empty($item->profile_id)) 
					{
						echo '<br />';
						echo '&nbsp;&nbsp;&bull;&nbsp;&nbsp;';
						echo '<strong>'.JText::_( 'Profile' ).'</strong>: ';
						echo JText::_( $item->profile_name );	
					}
					?>
					<div style="margin-top: 3px;">
    					<?php if (!empty($item->profile_linkedin)) : ?><a target="_blank" href="<?php echo $item->profile_linkedin; ?>"><img src="<?php echo Ambra::getUrl( "images" )."linkedin.png"; ?>" alt="<?php echo JText::_( "LinkedIn" ); ?>" /></a><?php endif; ?>
                        <?php if (!empty($item->profile_facebook)) : ?><a target="_blank" href="<?php echo $item->profile_facebook; ?>"><img src="<?php echo Ambra::getUrl( "images" )."facebook.png"; ?>" alt="<?php echo JText::_( "Facebook" ); ?>" /></a><?php endif; ?>
                        <?php if (!empty($item->profile_twitter)) : ?><a target="_blank" href="<?php echo $item->profile_twitter; ?>"><img src="<?php echo Ambra::getUrl( "images" )."twitter.png"; ?>" alt="<?php echo JText::_( "Twitter" ); ?>" /></a><?php endif; ?>
                        <?php if (!empty($item->profile_youtube)) : ?><a target="_blank" href="<?php echo $item->profile_youtube; ?>"><img src="<?php echo Ambra::getUrl( "images" )."youtube.png"; ?>" alt="<?php echo JText::_( "YouTube" ); ?>" /></a><?php endif; ?>
                    </div>
				</td>
                <td style="text-align: center;">
                    <h1>
                        <a href="<?php echo JRoute::_( "index.php?option=com_ambra&view=pointhistory&filter_order=tbl.created_date&filter_direction=DESC&filter_user=".$item->id ); ?>">
                        <?php echo (int) $item->points_current; ?>
                        </a>
                    </h1>
                    [<?php echo (int) $item->points_total; ?>]
                </td>
				<td style="text-align: center;">
					<?php
					if ($item->lastvisitDate == '0000-00-00 00:00:00') {
						echo JText::_( 'Never Logged In' );
					} else { 
						echo JHTML::_('date', $item->lastvisitDate, JText::_('DATE_FORMAT_LC1'));
					}
					echo '<br />';
					echo '&nbsp;&nbsp;&bull;&nbsp;&nbsp;';				
					if ($item->registerDate != '0') { 
						echo JHTML::_('date', $item->registerDate, JText::_('DATE_FORMAT_LC1')); 
					}
					?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::boolean( empty($item->block) ); ?>
				</td>
				<td style="text-align: center;">
					<?php echo DSCGrid::boolean( !empty($item->session_id) ); ?>
				</td>
				<?php foreach (@$this->fields as $field) : ?>
	                <td style="text-align: center;">
	                	<?php $db_fieldname = $field->db_fieldname; ?>
	                	<?php echo AmbraField::displayValue( $db_fieldname, $item->$db_fieldname ); ?>
	                </td>
                <?php endforeach; ?>
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>
			
			<?php if (!count(@$items)) : ?>
			<tr>
				<td colspan="10" align="center">
					<?php echo JText::_('No items found'); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" name="boxchecked" value="" />
	<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
	
	<?php echo $this->form['validate']; ?>
</form>
