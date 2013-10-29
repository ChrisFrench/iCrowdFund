<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $items = @$vars->results; ?>
<p><?php echo JText::_('COM_TIENDA_THIS_TOOL_INSTALL_SAMPLE_DATA_TO_TIENDA'); ?></p>

    <div class="note">
        <span style="float: right; font-size: large; font-weight: bold;"><?php echo JText::_('COM_TIENDA_FINAL'); ?></span>
        <p><?php echo JText::_('COM_TIENDA_INSTALLATION_RESULTS'); ?></p>
    </div>

    <table class="adminlist" style="clear: both;">
        <thead>
            <tr>
                <th style="width: 5px;">
                    <?php echo JText::_('COM_TIENDA_NUM'); ?>
                </th>
                <th style="text-align: center;">
                    <?php echo JText::_('COM_TIENDA_DATA'); ?>
                </th>
                <th style="width: 50px;">
                    <?php echo JText::_('COM_TIENDA_AFFECTED_ROWS'); ?>
                </th>
                <th>
                    <?php echo JText::_('COM_TIENDA_ERRORS'); ?>
                </th>
            </tr>
        </thead>      
        <tbody>
        <?php $i=0; $k=0; ?>
        <?php $data = array('Manufacturer', 'Category', 'Product');?>
        <?php foreach (@$items as $item=>$results) : ?>             
			<?php foreach (@$results as $result) : ?>
            <tr class='row<?php echo $k; ?>'>
                <td align="center">
                    <?php echo $i + 1; ?>
                </td>
                <td style="text-align: center; width:50px;">
                        <?php echo JText::_('COM_TIENDA_COM_TIENDA_TABLE'); ?> <?php echo $i + 1; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $result->affectedRows; ?>
                </td>
                <td style="text-align: center;">
                    <?php echo $result->error ? $result->error : "-"; ?>
                </td>
            </tr>
			<?php ++$i; $k = (1 - $k); ?>
            <?php endforeach; ?>           
        <?php endforeach; ?>
            <?php if (!count(@$items)) : ?>
            <tr>
                <td colspan="10" align="center">
                    <?php echo JText::_('COM_TIENDA_NO_ITEMS_FOUND'); ?>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>