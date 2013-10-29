<?php defined('_JEXEC') or die('Restricted access'); ?>

<style type="text/css">
    #wepay_form { width: 100%; }
    #wepay_form td { padding: 5px; }
    #wepay_form .field_name { font-weight: bold; }
</style>

<?php  if (Tienda::getInstance()->get('one_page_checkout')) :?>
<table id="wepay_form">      
    <tr>
        <td class="field_name">
            <?php echo JText::_('COM_TIENDA_CARD_NUMBER') ?><br/>
            <input type="text" name="cardnum" class="input-medium" size="35" value="<?php echo !empty($vars->prepop['x_card_num']) ? ($vars->prepop['x_card_num']) : '' ?>" />
        </td>
    </tr>
    <tr>
        <td id="creditcards" class="field_name">
            <?php echo JText::_('COM_TIENDA_EXPIRATION_DATE') ?><br/>
            <?php Tienda::load( 'TiendaSelect', 'library.select' ); ?>
            <?php $year = date('Y'); $year_end = $year + 25; ?> 
            <?php $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'document.adminForm.submit();'); ?>
            <?php echo TiendaSelect::integerlist( '1', '12', '1', 'cardexp_month', $attribs , '12' ); ?>
            <?php echo TiendaSelect::integerlist( $year, $year_end, '1', 'cardexp_year', $attribs, '2014'  ); ?>            
        </td>
    </tr>
    <tr>
        <td class="field_name">
            <?php echo JText::_('COM_TIENDA_CREDIT_CARD_ID') ?><br/>
            <input type="text" name="cardcvv" size="1" class="input-tiny" value="" />
            <br/>
            <a href="javascript:void(0);" onclick="window.open('index.php?option=com_tienda&view=opc&task=doTask&element=payment_wepay&elementTask=showCVV&tmpl=component', '<?php echo JText::_('COM_TIENDA_WHERE_IS_MY_CARD_ID') . "?"; ?>', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=600, height=550');"><?php echo JText::_('COM_TIENDA_WHERE_IS_MY_CARD_ID') . "?"; ?></a>
        </td>
    </tr>
        <tr>
        
        <td colspan='2'>
            <label class="checkbox"><input name="anonymous"  type="checkbox" value="anonymously">Fund this Project or Charity Anonymously.</label>
        </td>
    </tr>
</table>
<?php else: ?>
<table id="wepay_form"> 
    <tr>
        <td class="field_name"><?php echo JText::_('COM_TIENDA_CARD_NUMBER') ?></td>
        <td><input type="text" name="cardnum" size="35" value="<?php echo !empty($vars->prepop['x_card_num']) ? ($vars->prepop['x_card_num']) : '5496198584584769' ?>" /></td>
    </tr>
    <tr>
        <td class="field_name"><?php echo JText::_('COM_TIENDA_EXPIRATION_DATE') ?></td>
        <td id="creditcards">
            <?php Tienda::load( 'TiendaSelect', 'library.select' ); ?>
            <?php $year = date('Y'); $year_end = $year + 25; ?>
            <?php echo TiendaSelect::integerlist( '1', '12', '1', 'cardexp_month', '', '12' ); ?>
            <?php echo TiendaSelect::integerlist( $year, $year_end, '1', 'cardexp_year' , '', '2014' ); ?>
        </td>
    </tr>
    <tr>
        <td class="field_name"><?php echo JText::_('COM_TIENDA_CREDIT_CARD_ID') ?></td>
        <td>
            <input type="text" name="cardcvv" size="10" value="363" />
            <br/>
            <a href="javascript:void(0);" onclick="window.open('index.php?option=com_tienda&view=checkout&task=doTask&element=payment_wepay&elementTask=showCVV&tmpl=component', '<?php echo JText::_('COM_TIENDA_WHERE_IS_MY_CARD_ID') . "?"; ?>', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=600, height=550');"><?php echo JText::_('COM_TIENDA_WHERE_IS_MY_CARD_ID') . "?"; ?></a>
        </td>
    </tr>
    <tr>
        <td colspan='2'>
            <label class="checkbox"><input name="anonymous" type="checkbox" value="anonymously">Fund this Project or Charity Anonymously.</label>
        </td>
    </tr>
</table>
<?php endif;?>