<?php defined('_JEXEC') or die('Restricted access');

$indirect = isset( $this->is_indirect ) && $this->is_indirect;
$orders = $this -> row -> orders;
$options = array();
$options['num_decimals'] = 2;

 ?>
 
<?php if( !$indirect ) : ?>
<div class="indent-60">
<h1 class="pull-left">Transactions</h1>
<a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&id='.$this -> row -> campaign_id.'&task=stats' );?>" class="btn btn-primary pull-right" style="margin-top : 10px;">Back to Stats</a>

<div class="clearfix"></div>
</div>
<div class="indent-60" style="margin-top:15px;">
<?php endif; ?>
<div class="well well-small">
	<?php if( $indirect ) : ?>
	<h2 class="pull-left">Transactions</h2>
	<a href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&id='.$this -> row -> campaign_id.'&task=stats_orders' );?>" class="btn btn-primary pull-right">More transactions</a>
	<?php endif; ?>
	<div class="clearfix"></div>
	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th>#</th>
				<th>Backer</th>
				<th>Paid</th>
				<th>Level</th>
				<th>Status</th>
				<th>When</th>
				<th>Info</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			if( count( $orders ) == 0 ) : ?>
			<tr>
				<td colspan="7">No transactions have been made so far!</td>
			</tr>
		<?php else :
			$i = 1;
			foreach (@$orders as $o) :
				$d = new DateTime($o->modified_date);
			?>
			<tr>
				<td><?php echo $i++; ?></td>
				<td><?php echo $o->user_name; ?></td>
				<td><?php echo TiendaHelperProduct::currency($o->order_total, '', $options); ?></td>
				<td><?php echo $o->orderitems[0]->orderitem_name; ?></td>
				<td><?php echo $o->order_state_name; ?></td>
				<td><?php echo  $d->format('M d, Y H:i'); ?></td>
				<td><a class="btn btn-inverse" href="<?php echo JRoute::_( 'index.php?option=com_tienda&view=campaigns&task=display_order&id='.$o->order_id); ?>">Show more</a></td>
			</tr>
		<?php endforeach; 
			endif; ?>
			
		</tbody>
		<tr></tr>
	</table>
</div>
<?php if( !$indirect ) : ?>
</div>
<?php endif; ?>
