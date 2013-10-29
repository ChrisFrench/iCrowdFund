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
	
    class plgContentFeaturedItems extends DSCPlugin
    {
    	/**
    	 * @var $_element  string  Should always correspond with the plugin's filename, 
    	 *                         forcing it to be unique 
    	 */
        var $_element    = 'featureditems';
        
    	function plgContentFeaturedItems(& $subject, $config) 
    	{    		 
    		parent::__construct($subject, $config);
    		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
    		$this->loadLanguage('com_featureditems');
    	}
    	
     	/**
         * Checks the extension is installed
         * 
         * @return boolean
         */
        private function isInstalled()
        {
            return true;
        }
        
        /**
        * Joomla < 1.6 prepare content function
        *
        * Enter description here ...
        * @param unknown_type $row
        * @param unknown_type $params
        * @param unknown_type $page
        */
        public function onPrepareContent( &$row, &$params, $page=0 )
        {
            $context = 'com_content.article';
            return $this->doContentPrepare($context, $row, $params, $page);
        }
        
        /**
         * Joomla 1.6+ prepare content function
         *
         * @param unknown_type $context
         * @param unknown_type $article
         * @param unknown_type $params
         * @param unknown_type $page
         */
        public function onContentPrepare($context, &$article, &$params, $page = 0)
        {
            return $this->doContentPrepare($context, $article, $params, $page);
        }
        
    	
    	/**
         * Search for the tag and replace it with the media view {featureditems}
         * 
         * @param $article
         * @param $params
         * @param $limitstart
         */
       	private function doContentPrepare( $context, &$row, &$params, $page=0 )
       	{
       		if( !$this->isInstalled() )
       			return true;
      		
    	   	// simple performance check to determine whether bot should process further
    		if ( JString::strpos( $row->text, 'featureditems' ) === false ) {
    			return true;
    		}
    	
    		// Get plugin info
    		$plugin =& JPluginHelper::getPlugin('content', 'featureditems');
    	
    	 	// expression to search for
    	 	$regex = '/{featureditems\s*.*?}/i';
    	
    	 	$pluginParams = new JParameter( $plugin->params );
            $this->pluginParams = $pluginParams;
            
    		// check whether plugin has been unpublished
    		if ( !$pluginParams->get( 'enabled', 1 ) ) {
    			$row->text = preg_replace( $regex, '', $row->text );
    			return true;
    		}
    	
    	 	// find all instances of plugin and put in $matches
    		preg_match_all( $regex, $row->text, $matches );
   
    		// Number of plugin instances
    	 	$count = count( $matches[0] );
    	
    	 	// plugin only processes if there are any instances of the plugin in the text
    	 	if ( $count ) {
    	 		foreach($matches as $match)
    	 		{
    	 			$this->processTags( $row, $matches, $count, $regex );
    	 		}
    		}
       	}
        
        /**
         * Processes the various featureditems plugin instances
         * 
         * @param $row
         * @param $matches
         * @param $count
         * @param $regex
         * @return unknown_type
         */
        private function processTags( $row, $matches, $count, $regex )
        {
            // TODO This would load the appropriate plugin for the media item,
            // or, if possible, just load the front-end Media view

            for ( $i=0; $i < $count; $i++ )
            {
                $load = str_replace( 'featureditems', '', $matches[0][$i] );
                $load = str_replace( '{', '', $load );
                $load = str_replace( '}', '', $load );
                $load = trim( $load );
        
                $item    = $this->processTag( $load );
                $row->text  = str_replace($matches[0][$i], $item, $row->text );
            }
        
            // removes tags without matching
            $row->text = preg_replace( $regex, '', $row->text );
        }
        
        /**
         * Shows a single media item based on params (if present)
         * @param $load
         * @return unknown_type
         */
        private function processTag( $load )
        {
            $inline_params = explode(" ", $load);
            
            $params = $this->get('params');
            $params = $params->toArray();
            
            $params['attributes'] = array();
            // Merge plugin parameters with tag parameters, overwriting wherever necessary
            foreach( $inline_params as $p )
            {
                $data = explode("=", $p);
                if (!empty($data[0]))
                {
                    $k = $data[0];
                    $v = $data[1];
                    
                    $params[$k] = $v;                    
                }
            }
            
            if ( !array_key_exists('id', $params) && !array_key_exists('ids', $params) && !array_key_exists('use_queue', $params))
            {
                return;
            }
            
            if (!empty($params['id']))
            {
                $params['ids'] = $params['id'];
                unset($params['id']);
            }
            
            $parameters = $this->get('params');
            foreach ($params as $key=>$value)
            {
                $parameters->set( $key, $value );
            }
            
            require_once ( dirname( __FILE__ ) . '/featureditems/helper.php' );
            $helper = new plgContentFeaturedItemsHelper( $parameters );            
            $items = $helper->getItems();
            $count = count($items);
            $params = $helper->params->toArray();

            return $this->view( $items, $params );
        }
        
        /**
         * (non-PHPdoc)
         * @see featureditems/featureditems/admin/library/plugins/FeaturedItemsPluginBase::_getLayout()
         */
        protected function _getLayout($layout, $vars = false, $plugin = '', $group = 'content')
        {
            return parent::_getLayout($layout, $vars, $plugin, $group);
        }
        
        /**
         * 
         * Enter description here ...
         * @param $row
         * @param $params
         * @return unknown_type
         */
        private function view( $items, $params=array() )
        {
            $vars = new JObject();
            $vars->large_width = '720';
            $vars->large_height = '405';
            $vars->medium_width = '359';
            $vars->medium_height = '203';
            $vars->small_width = '239';
            $vars->small_height = '134';
            $vars->items = $items;
            $vars->item_id = $this->pluginParams->get('item_id');
            return $this->_getLayout( $params['layout'], $vars );
        }
        
        /**
         * 
         * @param unknown_type $article
         * @param unknown_type $isNew
         * @return boolean
         */
        public function onAfterContentSave( &$article, $isNew )
        {
            $context = 'com_content.form';
            return $this->doContentSave($context, $article, $isNew);            
        }
        
        /**
         * 
         * @param unknown_type $context
         * @param unknown_type $article
         * @param unknown_type $isNew
         * @return boolean
         */
        public function onContentAfterSave($context, &$article, $isNew)
        {
            return $this->doContentSave($context, $article, $isNew);
        }
        
        /**
         * 
         * @param unknown_type $context
         * @param unknown_type $article
         * @param unknown_type $isNew
         * @return boolean
         */
        private function doContentSave($context, &$article, $isNew)
        {
        	$app = JFactory::getApplication();
        	if (!$app->isAdmin()) {
        		return true;
        	}
        	
            if ($context != 'com_content.article') {
                return true;
            }
            
            $create_new = JRequest::getVar('featureditem_create_new', '', 'post');
            if (!empty($create_new)) 
            {
                JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_featureditems/tables' );
                $table = JTable::getInstance('Items', 'FeatureditemsTable');
                $table->fk_id = $article->id;
                $table->item_type = 'content';
                $table->item_short_title = $article->title;
                $table->save();
            }
        }
        
        /**
         * If your admin-side template has an override for the content editing form, 
         * then you don't need the system plugin.  Just add the following code to the override wherever you want the FI box to appear
         * 
         * $dispatcher = JDispatcher::getInstance();
         * $dispatcher->trigger( 'onContentDisplayFormBeforeParams', array( 'com_content.article', $this->item, $params ) );
         * 
         * @param unknown_type $context
         * @param unknown_type $article
         * @param unknown_type $articleParams
         * @return boolean
         */
        public function onContentDisplayFormBeforeParams( $context, $article, $articleParams )
        {
            $app = JFactory::getApplication();
            if (!$app->isAdmin()) {
                return true;
            }
             
            if ($context != 'com_content.article') {
                return true;
            }
        
            JHTML::_('stylesheet', 'common.css', 'media/dioscouri/css/');
        
            // get the FIs that exist for this article
            // add a form for creating a new FI from this article
            // list all existing ones with a link to their editing form
        
            $vars = new JObject();
            $vars->id = $article->id;
        
            $items = array();
            if (!empty($article->id)) 
            {
                JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_featureditems/models' );
                $model = JModel::getInstance('Items', 'FeatureditemsModel');
                $model->setState( 'filter_type', 'content' );
                $model->setState( 'filter_fk', $vars->id );
                $model->setState( 'order', 'tbl.item_short_title' );
                $items = $model->getList();
            }
        
            $vars->items = $items;
        
            echo $this->_getLayout( 'form', $vars );
        
        }
    }
}