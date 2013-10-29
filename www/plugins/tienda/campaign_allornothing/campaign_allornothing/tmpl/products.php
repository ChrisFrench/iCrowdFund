<?php
	defined('_JEXEC') or die('Restricted access');

?>





	<h2>Campaign Levels</h2>
	<div class="accordion" id="accordion2">
		<?php foreach ($vars as $level) {  ?>
			
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle " data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $level->product_id; ?>" style="width:90%;display: inline-block;">
        <?php echo $level->product_name; ?> | $ <?php echo $level->price; ?>
      </a>
       <a target="_blank" href="<?php echo $level->link_edit; ?>" class="">Edit</a>
    </div>
    <div id="collapse<?php echo $level->product_id; ?>" class="accordion-body collapse">
      <div class="accordion-inner">
        <?php echo $level->product_description; ?>
       
      </div>
    </div>
  </div>
		
			
	<?php	} ?>
 
