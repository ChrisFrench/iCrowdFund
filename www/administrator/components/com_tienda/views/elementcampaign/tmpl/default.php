<?php
/**
 * @version	1.5
 * @package	Tienda
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die('Restricted access');
$state = @$this->state;
$rows = @$this->get('List');
$form = @$this->form;

Tienda::load('TiendaHelperCampaign', 'helpers.campaign');
JHTML::_('behavior.modal');
JHTML::_('behavior.tooltip');
$model = $this->getModel();
$page = $this->get('Pagination'); 
?>
<form action="<?php echo JRoute::_( @$form['action'] .'&tmpl=component&object='.$this->object )?>" method="post" name="adminForm">

<table>
	<tr>
		<td width="100%" ><?php echo JText::_('COM_TIENDA_FILTER'); ?>:
			<input name="filter" value="<?php echo @$state->filter; ?>" />
			<button onclick="this.form.submit();"><?php echo JText::_('Search'); ?></button>
			<button onclick="Dsc.formReset(this.form);"><?php echo JText::_('Reset'); ?></button>
		</td>
	</tr>
</table>

<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="2%" class="title">
				<?php echo DSCGrid::sort( 'ID', 'c.campaign_id', @$state->direction , @$state->order ); ?>
			</th>
			<th style="width:50px;">
				<?php echo JText::_('COM_TIENDA_IMAGE'); ?>
			</th>
			<th class="title">
				<?php echo DSCGrid::sort( 'Name', 'c.campaign_name', @$state->direction, @$state->order ); ?>
			</th>
			<th class="title">
				<?php echo DSCGrid::sort( 'Description', 'c.campaign_shortdescription', @$state->direction, @$state->order ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="15"><?php echo $page->getListFooter(); ?></td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $rows ); $i < $n; $i++)
	{
		$row = &$rows[$i];

		$onclick = "
					window.parent.Dsc.select{$model->getName()}(
					'{$row->campaign_id}', '".str_replace(array("'", "\""), array("\\'", ""), $row->campaign_name)."', '".$this->object."'
					);";
		?>
		<tr class="<?php echo "row$k"; ?>">
			
			
			<td style="text-align: center;"><a style="cursor: pointer;"
				onclick="<?php echo $onclick; ?>"> <?php echo $row->campaign_id;?> </a>
			</td>
			<td>
			<?php
				if (!empty($row->campaign_full_image) )
				{ ?>
					<img src="<?php echo TiendaHelperCampaign::getImage($row, 'thumb', '', TRUE); ?>" width="120px">
			<?	
				}
			?>	
			</td>				
			<td><a style="cursor: pointer;"
				onclick="<?php echo $onclick; ?>"> <?php echo htmlspecialchars($row->campaign_name, ENT_QUOTES, 'UTF-8'); ?>
			</a></td>
			<td style="text-align: center;"><a style="cursor: pointer;"
				onclick="<?php echo $onclick; ?>"> <?php echo $row->campaign_shortdescription;?>
			</a></td>
			
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</tbody>
</table>

<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo @$state->order; ?>" />
<input type="hidden" name="filter_direction" value="<?php echo @$state->direction; ?>" />
</form>