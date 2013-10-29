<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


Ambra::load( 'AmbraViewBase', 'views._base' );

class AmbraViewConfig extends AmbraViewBase 
{
	/**
	 * 
	 * @return void
	 **/
	function _default($tpl = null) 
	{
		// check config
			$row = Ambra::getInstance();
			$this->assign( 'row', $row );
		
		// add toolbar buttons
			JToolBarHelper::save('save');
			JToolBarHelper::cancel( 'close', JText::_( 'Close' ) );
			
			// add the core ACL options button only if access allows them to
			if (JFactory::getUser()->authorise('core.admin', 'com_ambra')) {
			    JToolBarHelper::preferences('com_ambra');
			}
			
		// plugins
        	$filtered = array();
	        $items = DSCTools::getPlugins( 'Ambra' );
			for ($i=0; $i<count($items); $i++) 
			{
				$item = &$items[$i];
				// Check if they have an event
				if ($hasEvent = DSCTools::hasEvent( $item, 'onListConfigAmbra', 'Ambra' )) {
					// add item to filtered array
					$filtered[] = $item;
				}
			}
			$items = $filtered;
			$this->assign( 'items_sliders', $items );
			
		// Add pane
			jimport('joomla.html.pane');
			$sliders = JPane::getInstance( 'sliders' );
			$this->assign('sliders', $sliders);
			
		// form
			$validate = JUtility::getToken();
			$form = array();
			$view = strtolower( JRequest::getVar('view') );
			$form['action'] = "index.php?option=com_ambra&controller={$view}&view={$view}";
			$form['validate'] = "<input type='hidden' name='{$validate}' value='1' />";
			$this->assign( 'form', $form );
			
		// set the required image
		// TODO Fix this to use defines
			$required = new stdClass();
			$required->text = JText::_( 'Required' );
			$required->image = "<img src='".JURI::root()."/media/com_ambra/images/required_16.png' alt='{$required->text}'>";
			$this->assign('required', $required );
			
        	// Elements
        	$elementArticleModel    = JModel::getInstance( 'ElementArticle', 'AmbraModel' );
            // login
            $elementArticle_login         = $elementArticleModel->fetchElement( 'article_login', @$row->get('article_login') );

            $resetArticle_login           = $elementArticleModel->clearElement( 'article_login', '0' );
            $this->assign('elementArticle_login', $elementArticle_login);
            $this->assign('resetArticle_login', $resetArticle_login);
            // logout
            $elementArticle_logout       = $elementArticleModel->fetchElement( 'article_logout', @$row->get('article_logout') );
            $resetArticle_logout         = $elementArticleModel->clearElement( 'article_logout', '0' );
            $this->assign('elementArticle_logout', $elementArticle_logout);
            $this->assign('resetArticle_logout', $resetArticle_logout);
            // points program
            $elementArticle_points       = $elementArticleModel->fetchElement( 'article_points', @$row->get('article_points') );
            $resetArticle_points         = $elementArticleModel->clearElement( 'article_points', '0' );
            $this->assign('elementArticle_points', $elementArticle_points);
            $this->assign('resetArticle_points', $resetArticle_points);
            			
		
    }
    
}
