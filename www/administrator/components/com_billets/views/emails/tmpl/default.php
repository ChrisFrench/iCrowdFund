<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php Billets::load( 'BilletsHelperBase', 'helpers._base' ); ?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

	<?php //echo BilletsGrid::pagetooltip( JRequest::getVar('view') ); ?>

	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 5px;">
                	<?php echo JText::_('COM_BILLETS_NUM'); ?>
                </th>           
                <th>
                	<?php echo JText::_('COM_BILLETS_NAME'); ?>
                </th>
                <th>
    	            <?php echo JText::_('COM_BILLETS_CODE'); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="20">
                    <div style="float: right; padding: 5px;"><?php //echo @$this->pagination->getResultsCounter(); ?></div>
                    <?php //echo @$this->pagination->getPagesLinks(); ?>
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
				<td style="text-align: left">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->text; ?>
					</a>
				</td>	
				<td style="text-align: left;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->value; ?>
					</a>
				</td>	
			</tr>
			<?php $i=$i+1; $k = (1 - $k); ?>
			<?php endforeach; ?>
			
			<?php if (!count(@$items)) : ?>
			<tr>
				<td colspan="10" align="center">
					<?php echo JText::_('COM_BILLETS_NO_ITEMS_FOUND'); ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" value="" />
	
	<?php echo $this->form['validate']; ?>
</form>