<?php
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/**
 * @version	1.5
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

if(!class_exists('plgCommunityBillets'))
{
	class plgCommunityBillets extends CApplications 
	{
		
		/**
		 * Constructor 
		 *
		 * For php4 compatability we must not use the __constructor as a constructor for plugins
		 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
		 * This causes problems with cross-referencing necessary for the observer design pattern.
		 *
		 * @param object $subject The object to observe
		 * @param 	array  $config  An array that holds the plugin configuration
		 * @since 1.5
		 */
		function plgCommunityBillets(& $subject, $config) {
			parent::__construct($subject, $config);		
			$this->loadLanguage( '', JPATH_ADMINISTRATOR );	
		}	
		
	    function onProfileDisplay()
	    {
	    	global $mainframe;
  		                
            $caching = $this->params->get('cache', 1);
            if($caching)
            {
                $caching = $mainframe->getCfg('caching');
            }
            
            $cache =& JFactory::getCache('plgCommunityAmbra');
            $cache->setCaching($caching);
            $callback = array('plgCommunityBillets', '_getBilletsHTML');
            
            return $cache->call($callback, $this->params);
	    }
	    
	    function _getBilletsHTML($params)
	    {
	    	if (!plgCommunityBillets::_isInstalled()) { return null; }
	    	jimport( 'joomla.application.component.model' );
	    	JTable::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'tables' );
	    	JModel::addIncludePath( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'models' );
	    	$model = JModel::getInstance( 'Tickets', 'BilletsModel' );	 
    		
	    	//set the state
			$model->setState( 'filter_userid', JFactory::getUser()->id );				  
			$model->setState( 'limit', $params->get('limit', '10') );
			
	    	$vars = new JObject();	      
	        $vars->items = $model->getList();	      
	        $html = plgCommunityBillets::_getLayout('default', $vars, 'billets');
	       
	        return $html;
	    }
	   	    
	   /**
		* Method to check if billets is installed
	    * @return return boolean
	    */
	    function _isInstalled()
	    {
	        $success = false;
	        
	        jimport('joomla.filesystem.file');
	        if (JFile::exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'defines.php')) 
	        {
	            require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_billets'.DS.'defines.php' );
	            $success = true;
	        }
	        return $success;
	    }   
			    	    
		/**
	     * Gets the parsed layout file
	     * 
	     * @param string $layout The name of  the layout file
	     * @param object $vars Variables to assign to
	     * @param string $plugin The name of the plugin
	     * @param string $group The plugin's group
	     * @return string
	     * @access protected
	     */
	    function _getLayout($layout, $vars = false, $plugin = '', $group = 'community' )
	    {
	        if (empty($plugin)) 
	        {
	            $plugin = $this->_element;
	        }
	        
	        ob_start();
	        $layout = plgCommunityBillets::_getLayoutPath( $plugin, $group, $layout ); 
	        include($layout);
	        $html = ob_get_contents(); 
	        ob_end_clean();
	        
	        return $html;
	    }
	    
		/**
	     * Get the path to a layout file
	     *
	     * @param   string  $plugin The name of the plugin file
	     * @param   string  $group The plugin's group
	     * @param   string  $layout The name of the plugin layout file
	     * @return  string  The path to the plugin layout file
	     * @access protected
	     */
	    function _getLayoutPath($plugin, $group, $layout = 'default')
	    {
	        $app = JFactory::getApplication();
	
	        // get the template and default paths for the layout
	        $templatePath = JPATH_SITE.DS.'templates'.DS.$app->getTemplate().DS.'html'.DS.'plugins'.DS.$group.DS.$plugin.DS.$layout.'.php';
	        $defaultPath = JPATH_SITE.DS.'plugins'.DS.$group.DS.$plugin.DS.'tmpl'.DS.$layout.'.php';
	
	        // if the site template has a layout override, use it
	        jimport('joomla.filesystem.file');
	        if (JFile::exists( $templatePath )) 
	        {
	            return $templatePath;
	        } 
	        else 
	        {
	            return $defaultPath;
	        }
	    }
	}
}