<?php
/**
 * @package	FeaturedItems
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.model' );
jimport('joomla.filesystem.file');

if (!class_exists('DSC')) {
    if (!JFile::exists(JPATH_SITE.'/libraries/dioscouri/dioscouri.php'))
    {
        return false;
    }
    
    require_once JPATH_SITE.'/libraries/dioscouri/dioscouri.php';
    if (!DSC::loadLibrary()) 
    {
        return false;
    }
}
    
if (class_exists('DSC') && JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_featureditems'.DS.'defines.php')) 
{
    // Check the registry to see if our FeaturedItems class has been overridden
    if ( !class_exists('FeaturedItems') ) { 
        JLoader::register( "FeaturedItems", JPATH_ADMINISTRATOR.DS."components".DS."com_featureditems".DS."defines.php" );
    }
    
    class plgSystemFeaturedItems extends DSCPlugin
    {
    	/**
    	 * @var $_element  string  Should always correspond with the plugin's filename, 
    	 *                         forcing it to be unique 
    	 */
        var $_element    = 'featureditems';
        
    	function plgSystemFeaturedItems(& $subject, $config) 
    	{    		 
    		parent::__construct($subject, $config);
    		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
    		$this->loadLanguage('com_featureditems');
    	}
        
        /**
         * (non-PHPdoc)
         * @see featureditems/featureditems/admin/library/plugins/FeaturedItemsPluginBase::_getLayout()
         */
        protected function _getLayout($layout, $vars = false, $plugin = '', $group = 'system')
        {
            return parent::_getLayout($layout, $vars, $plugin, $group);
        }
        
        /**
         * Checks if this system plugin should even run.
         * Only runs if the URL being accessed is in the format:
         * index.php?option=com_whatever&task=something&format=soap
         *
         * @return boolean
         */
        protected function canRun()
        {
            $success = false;
        
            // don't run if we're on the front-end
            $app = JFactory::getApplication();
            if (!$app->isAdmin())
            {
                $this->setError( 'Invalid Site' );
                return $success;
            }
        
            $option = JRequest::getCmd( 'option' );
            $view = JRequest::getCmd( 'view' );
            $layout = JRequest::getCmd( 'layout' );
            
            if ($option != 'com_content' || $view != 'article' || $layout != 'edit') {
                return $success;
            }
            
            // if we're here, then all is OK
            $success = true;
            return $success;
        }
        

        public function onAfterInitialise()
        {
            $success = null;
            
            if (!$this->canRun())
            {
                return $success;
            }
            
            $this->renderForm();
        }
        
        protected function renderForm()
        {
            JHTML::_('stylesheet', 'common.css', 'media/dioscouri/css/');
            
            // get the FIs that exist for this article
            // add a form for creating a new FI from this article
            // list all existing ones with a link to their editing form
            
            $vars = new JObject();
            $vars->id = JRequest::getInt('id');
            
            JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_featureditems/models' );
            $model = JModel::getInstance('Items', 'FeatureditemsModel');
            $model->setState( 'filter_type', 'content' );
            $model->setState( 'filter_fk', $vars->id );
            $model->setState( 'order', 'tbl.item_short_title' );
            $items = $model->getList();
            
            $vars->items = $items;
            
            $html = $this->_getLayout( 'form', $vars );
            $output = preg_replace('/^\s+|\n|\r|\s+$/m', '', $html);
            
            DSC::loadJQuery();
            JHTML::_('script', 'common.js', 'media/com_featureditems/js/');
            
            $document = JFactory::getDocument();
            $handleDropdowns = "jQuery(document).ready(function(){Featureditems.injectForm('".$output."');});";
            $document->addScriptDeclaration( $handleDropdowns );
        }
        
    }
}