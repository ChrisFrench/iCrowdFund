<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'billets.js', 'media/com_billets/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

	<?php echo DSCGrid::pagetooltip( "tickets_convert" ); ?>

	<table class="adminlist" style="clear: both;">
		<thead>
            <tr>
                <th style="width: 20px;">
                	<?php echo JText::_('COM_BILLETS_ID'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_SUBJECT'); ?>
                	+
                	<?php echo JText::_('COM_BILLETS_CATEGORY'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_TARGET_CONTENT_CATEGORY'); ?>
                </th>
                <th style="width: 50px;">
					<?php echo JText::_('COM_BILLETS_CREATE_ARTICLE'); ?>
                </th>
                <th style="width: 50px;">
					<?php echo JText::_('COM_BILLETS_PUBLISH_ARTICLE'); ?>
                </th>
                <th>
                	<?php echo JText::_('COM_BILLETS_EXISTING_ARTICLES_FROM_TICKET'); ?>
                </th>
                <th style="width: 50px;">
                	<?php echo JText::_('COM_BILLETS_INCLUDE_USER_NAMES'); ?>
                </th>
                <th style="width: 50px;">
                	<?php echo JText::_('COM_BILLETS_USE_READMORE'); ?>
                </th>
            </tr>
		</thead>
        <tbody>
		<?php $i=0; $k=0; ?>
        <?php foreach (@$items as $item) : ?>
            <tr class='row<?php echo $k; ?>'>
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<?php echo $item->id; ?>
					</a>
					<input type="hidden" name="cid[]" value="<?php echo $item->id; ?>"/>
				</td>
				<td style="text-align: center;">
					<a href="<?php echo $item->link; ?>">
						<b><?php echo Billets::wraplongword(htmlspecialchars($item->title),30,' '); ?></b>
						<br/>
						<?php echo @$item->category_title; ?>
					</a>
				</td>
				<td style="text-align: center;">
					<?php echo BilletsSelect::contentcategory( Billets::getInstance()->get( 'kb_category' ), 'categoryid['.$item->id.']', null, null, true ); ?>					
				</td>
				<td style="text-align: left;">
					<?php echo JHTML::_('select.booleanlist', 'create['.$item->id.']', '', '1' ) ?>
				</td>
				<td style="text-align: left;">
					<?php echo JHTML::_('select.booleanlist', 'publish['.$item->id.']', '', Billets::getInstance()->get( 'kb_publish' ) ) ?>
				</td>
				<td style="text-align: center;">
					<ul class="content_link_list">
						<?php foreach (@$item->articles as $article) : ?>							
							<li>
								<?php echo BilletsUrl::popup( "index.php?option=com_content&task=edit&cid[]=".$article->articleid, $article->title); ?>
								<br/>&nbsp;
								<?php echo JHTML::_('date', $article->created_datetime, "%a, %d %b %Y, %H:%M"); // strftime formatting ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</td>
				<td style="text-align: left;">
					<?php echo JHTML::_('select.booleanlist', 'username['.$item->id.']', '', Billets::getInstance()->get( 'kb_username' ) ) ?>
				</td>
				<td style="text-align: left;">
					<?php echo JHTML::_('select.booleanlist', 'readmore['.$item->id.']', '', Billets::getInstance()->get( 'kb_readmore' ) ) ?>
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
		<tfoot>
			<tr>
				<td colspan="20">
					&nbsp;
				</td>
			</tr>
		</tfoot>
	</table>

	<input type="hidden" name="task" id="task" value="" />
	
	<?php echo $this->form['validate']; ?>
</form>