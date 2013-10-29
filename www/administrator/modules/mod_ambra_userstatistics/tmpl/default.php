<?php
/**
 * @version    1.5
 * @package    Ambra
 * @author     Dioscouri Design
 * @link     http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
?>

<?php $options = array('num_decimals'=>'0'); ?>
    
<table class="adminlist" style="margin-bottom: 5px;">
<thead>
<tr>
    <th colspan="3"><?php echo JText::_( "Summary Statistics" ); ?></th>
</tr>
</thead>
<tbody>
<tr>
    <th colspan="2">
        <strong><?php echo JText::_("Registrations"); ?></strong>
    </th>
</tr>
<tr>
    <th><a href="<?php echo $stats->link; ?>"><?php echo JText::_( "Today" ); ?></a></th>
    <td style="text-align: right;"><?php echo AmbraHelperBase::number( $stats->today->num, $options ); ?></td>
</tr>
<tr>
    <th><a href="<?php echo $stats->link; ?>"><?php echo JText::_( "Yesterday" ); ?></a></th>
    <td style="text-align: right;"><?php echo AmbraHelperBase::number( $stats->yesterday->num, $options ); ?></td>
</tr>
<tr>
    <th><a href="<?php echo $stats->link; ?>"><?php echo JText::_( "Last Seven Days" ); ?></a></th>
    <td style="text-align: right;"><?php echo AmbraHelperBase::number( $stats->lastseven->num, $options ); ?></td>
</tr>
<tr>
    <th><a href="<?php echo $stats->link; ?>"><?php echo JText::_( "Last Month" ); ?></a></th>
    <td style="text-align: right;"><?php echo AmbraHelperBase::number( $stats->lastmonth->num, $options ); ?></td>
</tr>
<tr>
    <th><a href="<?php echo $stats->link; ?>"><?php echo JText::_( "This Month" ); ?></a></th>
    <td style="text-align: right;"><?php echo AmbraHelperBase::number( $stats->thismonth->num, $options ); ?></td>
</tr>
<tr>
    <th><a href="<?php echo $stats->link; ?>"><?php echo JText::_( "Last Year" ); ?></a></th>
    <td style="text-align: right;"><?php echo AmbraHelperBase::number( $stats->lastyear->num, $options ); ?></td>
</tr>
<tr>
    <th><a href="<?php echo $stats->link; ?>"><?php echo JText::_( "This Year" ); ?></a></th>
    <td style="text-align: right;"><?php echo AmbraHelperBase::number( $stats->thisyear->num, $options ); ?></td>
</tr>
<tr>
    <th><a href="<?php echo $stats->link; ?>"><?php echo JText::_( "Lifetime" ); ?></a></th>
    <td style="text-align: right;">
        <?php echo AmbraHelperBase::number( $stats->lifetime->num, $options )." ".JText::_("Total"); ?>
    </td>
</tr>
<tr>
    <th><a href="<?php echo $stats->link; ?>"><?php echo JText::_( "Average" ); ?></a></th>
    <td style="text-align: right;"><?php echo AmbraHelperBase::number( $stats->lifetime->average_daily, $options )." ".JText::_("per day"); ?></td>
</tr>
</tbody>
</table>