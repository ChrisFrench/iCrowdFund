<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

<form name="adminForm" id="adminForm" action="<?php echo JRoute::_( @$form['action'] )?>" method="post" enctype="multipart/form-data">

    <?php echo AmbraGrid::pagetooltip( JRequest::getVar('view') ); ?>
    
	<div id="exsisting_relationships" name="exsisting_relationships" >
		<table>
	        <tr>
	            <td align="left" width="100%">
	            	<?php //echo Ambra::dump($this); ?>
	            </td>
	            <td nowrap="nowrap" style="text-align: right;">
	            	<?php echo JText::_("Enter User ID or Username:"); ?>
	                <input name="filter_user_from" value="<?php echo @$state->filter_user_from; ?>" />
	                <button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
	                <button onclick="ambraFormReset(this.form);"><?php echo JText::_('Reset'); ?></button>
	            </td>
	        </tr>
	    </table>
	
		<table class="adminlist" style="clear: both;">
			<thead>
	            <tr>
	                <th style="width: 5px;">
	                	<?php echo JText::_("Num"); ?>
	                </th>
	                <th style="width: 20px;">
                    	<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( @$items ); ?>);" />
                	</th>
                	<th style="text-align: center; width:125px;">
	                	<?php echo JText::_( 'Relationship ID' ); ?>
	                </th>
	                <th style="text-align: left;">
	    	            <?php echo JText::_( 'User From' ); ?>
	                </th>	                                
	                <th style="text-align: center; width:135px;">
	                	<?php echo JText::_( 'Relationship Type' ); ?>
	                </th>
	                <th style="text-align: left;">
	    	            <?php echo JText::_( 'User ID' ); ?>
	                </th>
	                <th style="text-align: left;">
	    	            <?php echo JText::_( 'User Name' ); ?>
	                </th>
	                <th style="text-align: left;">
	    	            <?php echo JText::_( 'Username' ); ?>
	                </th>
	                <th style="text-align: left;">
	                    <?php echo JText::_( 'Email' ); ?>
	                </th>
	            </tr>
			</thead>
	        <tbody>
			<?php $i=0; $k=0; ?>
	        <?php foreach (@$items as $item) : ?>
	            
	        
	            <tr class='row<?php echo $k; ?>'>
					<td align="center">
						<?php echo $i + 1; ?>
					</td>
					<td style="text-align: center;">
                    	<?php echo AmbraGrid::checkedout( $item, $i, 'userrelation_id' ); ?>
                	</td>
                	<td style="text-align: center;">
						<?php echo $item->userrelation_id; ?>
					</td>
					<td style="text-align: left;">
						<?php echo '[ID'.$item->user_id_from.'] - '.$item->user_username_from; ?>
					</td>						
					<td style="text-align: center;">
					    <span class="relationship_<?php echo $item->relation_type; ?>">
						<?php echo JText::_( "Relationship ". $item->relation_type ); ?>
						</span>
					</td>
					<td style="text-align: left;">
					    <?php 
					        // display the _user_to
	                        $user_id = $item->user_id_to;
	                        $user_name = $item->user_name_to;
	                        $user_username = $item->user_username_to;
	                        $user_email = $item->user_email_to;
	                    ?>
	                    
	                    <?php echo $user_id; ?>
	                 </td>
	                 <td style="text-align: left;">
	                 	<?php echo $user_name; ?>
	                 </td>
	                 <td style="text-align: left;">                    
	                    <?php echo $user_username; ?>
	                 </td>
	                <td style="text-align: left;">
	                    <?php echo $user_email; ?>
	                </td>
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
	</div>

    <input type="hidden" name="order_change" value="0" />
    <input type="hidden" name="id" value="" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="" />
    <input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
    <input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
    
    <?php echo $this->form['validate']; ?>
</form>