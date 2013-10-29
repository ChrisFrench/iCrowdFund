<?php
// No direct access
defined('_JEXEC') or die ;

jimport('joomla.plugin.plugin');
jimport( 'joomla.form.form' );

class plgSystemExtendForm extends JPlugin {

	function onContentPrepareForm($form, $data) {

		
		if (!($form instanceof JForm)) {
			$this -> _subject -> setError('JERROR_NOT_A_FORM');
			return false;
		}
		
	    $db = JFactory::getDBO(); 	
	 	$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__extendform_forms'));
		$query->where($db->quoteName('enabled') . ' = 1 AND ' .$db->quoteName('form') .  " = '" . $form -> getName() . "'" );
		
		
		$db->setQuery($query);	
		$results = $db->loadObjectList();		
		if(!$results){
			   return true;
		}
		
	
		foreach($results as $result) {
	     // Check we are manipulating a valid form. This probably isn't really needed if the query worked but it maybe keep it from failing and breaking joomla
        if (!in_array($form->getName(), array($result->form))) {
		    return true;
        }
       
        $app = JFactory::getApplication();
        switch ($result->section) {
        	case '1':
        		//FRONTEND
        		if($app->isAdmin())
        			return true;
        		break;
        	case '2':
        		//ADMIN
        	if(!$app->isAdmin())
        			return true;
        		break;	
        	default:
        		//BOTH FRONTEND and ADMIN
        		break;
        }
		
		/*There has to be a better way to get the root path*/
			$path = str_replace('plugins/system/extendform', '', dirname(__FILE__));
		
		// if there is no path, than we can assume it is a extendform plugin
		if(empty($result->path)) {
			$path = $path . 'media/com_extendform/forms';
			
		}
		
			// Add the fields to the form.
			JForm::addFormPath($path);
			$form -> loadFile($result->xmlfile, true);
		}
	}




}
?>