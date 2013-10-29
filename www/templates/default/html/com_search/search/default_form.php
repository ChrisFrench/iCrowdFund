<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
?>

<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search');?>" method="post" class="form-horizontal">
	<fieldset class="word">
		<br /><br />
		<div class="control-group">
			<label for="search-searchword"> <?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?> </label>
			<div class="controls">
				<input type="text" name="searchword" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="inputbox" />
				<button name="Search" onclick="this.form.submit()" class="btn btn-primary button"><?php echo JText::_('COM_SEARCH_SEARCH');?></button>
			</div>
		</div>
		
		<input type="hidden" name="task" value="search" />
	</fieldset>
	<div class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
			
	<?php if ($this->total > 0) : ?>
	<div class="form-limit control-group">
		
		</div>
	<p class="counter"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
	<?php endif; ?>
</form>
