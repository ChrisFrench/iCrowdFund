<?php
/**
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 *
 * eg.  To render a module mod_test in the sliders style, you would use the following include:
 * <jdoc:include type="module" name="test" style="slider" />
 *
 * This gives template designers ultimate control over how modules are rendered.
 *
 * NOTICE: All chrome wrapping methods should be named: modChrome_{STYLE} and take the same
 * two arguments.
 */

/**
 * Module chrome for rendering the module in a slider
 */
function modChrome_slider($module, &$params, &$attribs)
{
	jimport('joomla.html.pane');
	$sliders = & JPane::getInstance('sliders');
	$sliders->startPanel( JText::_( $module->title ), 'module' . $module->id );
	echo $module->content;
	$sliders->endPanel();
}

/**
 * Default rendering for modules
 *
 *
 */
function modChrome_default($module, &$params, &$attribs)
{
	if (!empty ($module->content)) : ?>
		<div class="wrap module-wrap <?php echo $params->get('moduleclass_sfx'); ?>" id="module-<?php echo $module->id; ?>">
		<?php if ($module->showtitle != 0) : ?>
			<h4><?php echo $module->title; ?></h4>
		<?php endif; ?>
            <div class="module-content wrap">
                <?php
    			    echo $module->content;
                ?>
            </div>
		</div>
    <?php endif;
}
