<?php
/**
 * @version	1.5
 * @package	Tienda
 * @author 	Daniele Rosario
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

Tienda::load( 'TiendaPluginBase', 'library.plugins._base' );

class plgTiendaCampaign_emailonedit extends TiendaPluginBase
{
	/**
	 * @var $_element  string  Should always correspond with the plugin's filename, 
	 *                         forcing it to be unique 
	 */
	var $_element = 'campaign_emailonedit';
	var $_campaigns = null;
	var $_campaign = null;
	var $_payeeEmail = null;


	function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->loadLanguage( '', JPATH_ADMINISTRATOR );
	}
	
	
    function onAfterSaveCampaigns($row) {
       
        if($row->campaign_ready && $row->campaign_enabled) {

            //TODO move to plugin
            if ( !class_exists('Messagebottle') ) 
            JLoader::register( "Messagebottle", JPATH_ADMINISTRATOR."/components/com_messagebottle/defines.php" );
            $lang = JFactory::getLanguage();
            $lang->load('com_tienda', JPATH_ADMINISTRATOR);
            $mail = Messagebottle::getClass( 'Bottle', 'helpers.bottle' );
             $mail->addRecipient( 'chris@ammonitenetworks.com' );
            $mail->addRecipient( 'lgroveman@icrowdfund.com' );
            $mail->setSubject( sprintf ( JText::_('COM_TIENDA_EMAIL_MESSAGE_CAMPAIGN_EDITED_TITLE'), $row->campaign_name ) );
            $link = JURI::base() .'administrator/index.php?option=com_tienda&view=campaigns&task=edit&id='.$row->campaign_id;
            $link = '<a href="'.$link.'">Here</a>';
            $mail->setBody( sprintf ( JText::_('COM_TIENDA_EMAIL_MESSAGE_CAMPAIGN_EDITED_BODY'), $row->campaign_name , $link ) );
            $mail->setScope('2');
            $mail->setView('campaign');
            $mail->setOption('com_tienda');
            $mail->Send();
        }

    }

	
}
