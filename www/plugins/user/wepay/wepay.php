<?php 
defined('JPATH_BASE') or die;
 
class plgUserWepay extends JPlugin
{
	function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile','com_users.registration','com_users.user','com_admin.profile'))){
			return true;
		}
 
		$userId = isset($data->id) ? $data->id : 0;
 
		// Load the profile data from the database.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT profile_key, profile_value FROM #__user_profiles' .
			' WHERE user_id = '.(int) $userId .
			' AND profile_key LIKE \'wepay.%\'' .
			' ORDER BY ordering'
		);
		$results = $db->loadRowList();
 
		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->_subject->setError($db->getErrorMsg());
			return false;
		}
 
		// Merge the profile data.
		$data->wepay = array();
		foreach ($results as $v) {
			$k = str_replace('wepay.', '', $v[0]);
			$data->wepay[$k] = $v[1];
		}
 
		return true;
	}
 
	function onContentPrepareForm($form, $data)
	{
		// Load user_profile plugin language
		$lang = JFactory::getLanguage();
		$lang->load('plg_user_wepay', JPATH_ADMINISTRATOR);
 
		if (!($form instanceof JForm)) {
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
		// Check we are manipulating a valid form.
		if (!in_array($form->getName(), array('com_users.profile', 'com_users.registration','com_users.user','com_admin.profile'))) {
			return true;
		}

		// Add the profile fields to the form.
		JForm::addFormPath(dirname(__FILE__).'/profiles');
		$form->loadFile('profile', false);
		
		$fields = array(
			'organization',
		);
	}
 
	function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId	= JArrayHelper::getValue($data, 'id', 0, 'int');
 
		if ($userId && $result && isset($data['wepay']) && (count($data['wepay'])))
		{
			try
			{
				$db = &JFactory::getDbo();
				$db->setQuery('DELETE FROM #__user_profiles WHERE user_id = '.$userId.' AND profile_key LIKE \'wepay.%\'');
				if (!$db->query()) {
					throw new Exception($db->getErrorMsg());
				}
 
				$tuples = array();
				$order	= 1;
				foreach ($data['wepay'] as $k => $v) {
					$tuples[] = '('.$userId.', '.$db->quote('wepay.'.$k).', '.$db->quote($v).', '.$order++.')';
				}
 
				$db->setQuery('INSERT INTO #__user_profiles VALUES '.implode(', ', $tuples));
				if (!$db->query()) {
					throw new Exception($db->getErrorMsg());
				}
			}
			catch (JException $e) {
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}
 
		return true;
	}
 
	function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success) {
			return false;
		}
 
		$userId	= JArrayHelper::getValue($user, 'id', 0, 'int');
 
		if ($userId)
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__user_profiles WHERE user_id = '.$userId .
					" AND profile_key LIKE 'wepay.%'"
				);
 
				if (!$db->query()) {
					throw new Exception($db->getErrorMsg());
				}
			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}
 
		return true;
	}
  
}
?>