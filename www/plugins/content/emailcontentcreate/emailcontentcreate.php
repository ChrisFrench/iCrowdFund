<?php
/**
 * @version	1.5
 * @package	Tienda
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

class plgContentEmailContentCreate extends JPlugin 
{   
   
    public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}





	protected function _sendEmails (&$Users_to_send, $forUsers) {

		if (empty($Users_to_send) ) { return; }

		$app = JFactory::getApplication();
			
			$mailer = JFactory::getMailer();
			$mailer->setSubject('Project Updated');
			$mailer->setSender(array('chris@ammonitenetworks.com', 'iCrowdFund'));
			$mailer->setBody('<p>There was a content updates </p>');
			$mailer->addRecipient($mail['email']);
			$send =& $mailer->Send();
	}//function

	//joomla 1.6 compatibility code
	public function onContentAfterSave($context, &$article, $isNew) {
		$result = $this->onAfterContentSave($article, $isNew, $context);
		return $result;
	}

	/**
	 * Run plugin on change article state
	 */
   	public function onContentChangeState($context, $pks, $value) {
		if ($context != 'com_content.article' || ($value != '1' && $value != '0')) {
			return;
		}

		FB::log($context);
		FB::log($pks);
		FB::log($value);

		$this->previous_state = 1 - $value;
		foreach ($pks as $id) {
			$article =& JTable::getInstance( 'content' );
			$article->load( $id );
			$this->onContentAfterSave($context, $article, false);
		}

		return true;
	}




}