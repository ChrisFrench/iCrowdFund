<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>
<?php JLoader::import( 'com_ambra.helpers.category', JPATH_ADMINISTRATOR.DS.'components' )?>

<form action="<?php echo JRoute::_( @$form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

    <?php echo AmbraGrid::pagetooltip( JRequest::getVar('view') ); ?>
    
    <table>
        <tr>
            <td align="left" width="100%">
            </td>
            <td nowrap="nowrap" style="text-align: right;">
                <input name="filter" value="<?php echo @$state->filter; ?>" />
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
                <th style="width: 50px;">
                    <?php echo AmbraGrid::sort( 'ID', "tbl.category_id", @$state->direction, @$state->order ); ?>
                </th>
                <th style="text-align: left;">
                    <?php echo AmbraGrid::sort( 'Name', "tbl.category_name", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo AmbraGrid::sort( 'Order', "tbl.ordering", @$state->direction, @$state->order ); ?>
                    <?php echo JHTML::_('grid.order', @$items ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo AmbraGrid::sort( 'Enabled', "tbl.category_enabled", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 150px;">
                </th>
                <th style="width: 150px;">
                </th>
            </tr>
            <tr class="filterline">
                <th colspan="3">
                    <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
                    <div class="range">
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("From"); ?>:</span> <input id="filter_id_from" name="filter_id_from" value="<?php echo @$state->filter_id_from; ?>" size="5" class="input" />
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("To"); ?>:</span> <input id="filter_id_to" name="filter_id_to" value="<?php echo @$state->filter_id_to; ?>" size="5" class="input" />
                        </div>
                    </div>
                </th>                
                <th style="text-align: left;">
                    <input id="filter_name" name="filter_name" value="<?php echo @$state->filter_name; ?>" size="25"/>
                </th>
                <th>
                </th>
                <th>
                    <?php echo AmbraSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true ); ?>
                </th>
                <th>
                    <?php echo AmbraSelect::field( @$state->filter_field, 'filter_field', $attribs, 'field', true ); ?>
                </th>
                <th>
                    <?php echo AmbraSelect::profile( @$state->filter_profile, 'filter_profile', $attribs, 'profile', true ); ?>
                </th>
            </tr>
            <tr>
                <th colspan="20" style="font-weight: normal;">
                    <div style="float: right; padding: 5px;"><?php echo @$this->pagination->getResultsCounter(); ?></div>
                    <div style="float: left;"><?php echo @$this->pagination->getListFooter(); ?></div>
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
                    <?php echo AmbraGrid::checkedout( $item, $i, 'category_id' ); ?>
                </td>
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->category_id; ?>
                    </a>
                </td>   
                <td style="text-align: left;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo JText::_( $item->category_name ); ?>
                    </a>
                    <br/>
                    <b><?php echo JText::_("Fields"); ?>:</b>
                    <?php echo $item->fields_list; ?>
                    <br/>
                    <b><?php echo JText::_("Profiles"); ?>:</b>
                    <?php echo $item->profiles_list; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo AmbraGrid::order($item->category_id); ?>
                    <?php echo AmbraGrid::ordering($item->category_id, $item->ordering ); ?>
                </td>
                <td style="text-align: center;">
                    <?php echo AmbraGrid::enable($item->category_enabled, $i, 'category_enabled.' ); ?>
                </td>
                <td style="text-align: center;">
                    [<?php echo AmbraUrl::popup( "index.php?option=com_ambra&view=categories&task=selectfields&id=".$item->category_id."&tmpl=component", "Select Fields", array('update' => true) ); ?>]
                </td>
                <td style="text-align: center;">
                    [<?php echo AmbraUrl::popup( "index.php?option=com_ambra&view=categories&task=selectprofiles&id=".$item->category_id."&tmpl=component", "Select Profiles", array('update' => true) ); ?>]
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

    <input type="hidden" name="order_change" value="0" />
    <input type="hidden" name="id" value="" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="" />
    <input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
    <input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
    
    <?php echo $this->form['validate']; ?>
</form>