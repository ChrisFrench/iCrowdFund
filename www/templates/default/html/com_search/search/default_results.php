<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<h2>Results</h2>
<div class="search-results<?php echo $this->pageclass_sfx; ?>">
	<?php foreach($this->results as $result) : ?>
 <?php 
if ( !class_exists('Tienda') ) 
    JLoader::register( "Tienda", JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" );

Tienda::load('TiendaHelperCampaign','helpers.campaign');


  ?>
<div class="well well-small">
<div class="media">

  <a class="pull-left" href="<?php echo JRoute::_($result->href); ?>">
    <img class="media-object img-rounded" src="<?php echo TiendaHelperCampaign::getImage($result, 'thumb', '',true);  ?>">
  </a>

  <div class="media-body">
    <h3 class="media-heading"><a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) :?> target="_blank"<?php endif;?>> <?php echo $this->escape($result->title);?> </a></h3>

    <div class="media"><p>
    <?php echo $result->text; ?>
</p>
    <p>
    <a class="btn btn-primary" href="<?php echo JRoute::_($result->href); ?>">See Page</a>
	</p>
    </div>
  </div>
</div>
</div>
	<?php endforeach; ?>
</div>
<div class="pagination"> <?php echo $this->pagination->getPagesLinks(); ?> </div>
