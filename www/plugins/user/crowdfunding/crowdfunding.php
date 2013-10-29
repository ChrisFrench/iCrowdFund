<?php
/**
 * @package Tienda
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2009 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

/** Import library dependencies */
jimport('joomla.plugin.plugin');

if ( !class_exists('Tienda') )
    JLoader::register( "Tienda", JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" );

class plgUserCrowdfunding extends JPlugin
{
	
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    /**
     * When the user logs in, their session cart should override their db-stored cart.
     * Current actions take precedence
     * For Joomla 1.5     
     *
     * @param $user
     * @param $options
     * @return unknown_type
     */
    public function onLoginUser($user, $options = array())
    {
      return $this->doLoginUser($user, $options);
    }

    /**
     * When the user logs in, their session cart should override their db-stored cart.
     * Current actions take precedence
     *
     * @param $user
     * @param $options
     * @return unknown_type
     */
    public function onUserLogin($user, $options = array())
    {
      return $this->doLoginUser($user, $options);
    }
    
    /**
     * When the user has a product in both his current cart, and his saved cart (in his profile), the saved cart is emptied
     *
     * @param $user
     * @param $options
     * @return unknown_type
     */
    private function doLoginUser( $user, $options = array() )
    {

    $app = JFactory::getApplication();

        // is user in admin area?
        if($app->isAdmin()) {
            return;
        }

    	$session = JFactory::getSession();
		$db = JFactory::getDbo();
    	$old_sessionid = $session->getId();
    	$uid = intval(JUserHelper::getUserId($user['username']));
    	
    	// Should check that Tienda is installed first before executing
        if (!$this->_isInstalled())
        {
            return;
        }
		
		// get session cart
        Tienda::load('TiendaTableCarts','tables.carts');
		Tienda::load('TiendaModelCarts','models.carts');
		$model = DSCModel::getInstance( 'Carts', 'TiendaModel' );
		$model->setState( 'filter_user_leq', '0' );
		$model->setState( 'filter_session', $old_sessionid );
		$session_cartitems = $model->getList();
		// there is a product in my cart before logging in so I want to delete my saved cart
		if (count($session_cartitems))
		{
			$query = new DSCQuery();
			$query->delete();
			$query->from( "#__tienda_carts" );
			$query->where( "`user_id` = ".(int)$uid." " );
			$db->setQuery($query);
			$db->query();
		}
    }

    /**
     * Checks the extension is installed 
     *
     * @return boolean
     */
    function _isInstalled()
    {
        $success = false;

        jimport('joomla.filesystem.file');
        if (JFile::exists(JPATH_ADMINISTRATOR.'/components/com_tienda/defines.php'))
        {
            $success = true;
        }
        return $success;
    } 
}
?>