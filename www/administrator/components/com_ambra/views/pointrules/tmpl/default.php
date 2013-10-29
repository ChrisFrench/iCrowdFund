<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('script', 'ambra.js', 'media/com_ambra/js/'); ?>
<?php $state = @$this->state; ?>
<?php $form = @$this->form; ?>
<?php $items = @$this->items; ?>

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
                    <?php echo AmbraGrid::sort( 'ID', "tbl.pointrule_id", @$state->direction, @$state->order ); ?>
                </th>
                <th style="text-align: left;">
                    <?php echo AmbraGrid::sort( 'Name', "tbl.pointrule_name", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo AmbraGrid::sort( 'Scope', "tbl.pointrule_scope", @$state->direction, @$state->order ); ?>
                    +
                    <?php echo AmbraGrid::sort( 'Event', "tbl.pointrule_event", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo AmbraGrid::sort( 'Profile', "tbl.profile_id", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo AmbraGrid::sort( 'Points', "tbl.pointrule_value", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 50px;">
                    <?php echo AmbraGrid::sort( 'Enabled', "tbl.pointrule_enabled", @$state->direction, @$state->order ); ?>
                </th>
                <th style="width: 100px;">
                    <?php echo AmbraGrid::sort( 'Created', "tbl.created_date", @$state->direction, @$state->order ); ?>
                    +
                    <?php echo AmbraGrid::sort( 'Expires', "tbl.expire_date", @$state->direction, @$state->order ); ?>
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
                    <?php echo AmbraSelect::scope( @$state->filter_scope, 'filter_scope', $attribs, 'scope', true ); ?>
                    <?php echo AmbraSelect::event( @$state->filter_event, 'filter_event', $attribs, 'event', true ); ?>
                </th>
                <th>
                    <?php echo AmbraSelect::profile( @$state->filter_profile, 'filter_profile', $attribs, 'profile', true, true, 'Select Profile', 'All Profiles' ); ?>
                </th>
                <th>
                    <div class="range">
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("From"); ?>:</span> <input id="filter_points_from" name="filter_points_from" value="<?php echo @$state->filter_points_from; ?>" size="5" class="input" />
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("To"); ?>:</span> <input id="filter_points_to" name="filter_points_to" value="<?php echo @$state->filter_points_to; ?>" size="5" class="input" />
                        </div>
                    </div>                
                </th>
                <th>
                    <?php echo AmbraSelect::booleans( @$state->filter_enabled, 'filter_enabled', $attribs, 'enabled', true ); ?>
                </th>
                <th>
                    <div class="range">
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("From"); ?>:</span>
                            <?php echo JHTML::calendar( @$state->filter_date_from, "filter_date_from", "filter_date_from", '%Y-%m-%d %H:%M:%S' ); ?>
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("To"); ?>:</span>
                            <?php echo JHTML::calendar( @$state->filter_date_to, "filter_date_to", "filter_date_to", '%Y-%m-%d %H:%M:%S' ); ?>
                        </div>
                        <div class="rangeline">
                            <span class="label"><?php echo JText::_("Type"); ?>:</span>
                            <?php echo AmbraSelect::pointhistory_datetype( @$state->filter_datetype, 'filter_datetype', '', 'datetype' ); ?>
                        </div>
                    </div>
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
                    <?php echo AmbraGrid::checkedout( $item, $i, 'pointrule_id' ); ?>
                </td>
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->pointrule_id; ?>
                    </a>
                </td>
                <td style="text-align: left;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo JText::_( $item->pointrule_name ); ?>
                    </a>
                    <br/>
                    <?php echo substr( strip_tags( JText::_( $item->pointrule_description ) ), 0, 250 ); ?>
                </td>
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->pointrule_scope; ?>
                    </a>
                    <a href="<?php echo $item->link; ?>">
                        <?php echo $item->pointrule_event; ?>
                    </a>
                </td>
                <td style="text-align: center;">
                    <a href="<?php echo $item->link; ?>">
                        <?php echo (!empty($item->profile_name)) ? $item->profile_name : JText::_( "All Profiles" ); ?>
                    </a>
                </td>
                <td style="text-align: center;">
                    <h1><?php echo $item->pointrule_value; ?></h1>
                </td>
                <td style="text-align: center;">
                    <?php echo AmbraGrid::enable($item->pointrule_enabled, $i, 'pointrule_enabled.' ); ?>
                </td>
                <td style="text-align: center;">
                    <?php
                    if ($item->created_date != '0000-00-00 00:00:00') {
                        echo JHTML::_('date', $item->created_date, "%a, %d %b %Y");
                        echo '<br />';    
                    }
                                  
                    if ($item->expire_date == '0000-00-00 00:00:00') {
                        echo '&nbsp;&nbsp;&bull;&nbsp;&nbsp;';
                        echo JText::_( 'No Expiration' );
                    } else { 
                        echo '&nbsp;&nbsp;&bull;&nbsp;&nbsp;';
                        echo JHTML::_('date', $item->expire_date, "%a, %d %b %Y");
                    }                    
                    ?>
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