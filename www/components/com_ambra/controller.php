<?php
/**
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class AmbraController extends DSCControllerSite
{
    public $default_view = 'users';
    public $router = null;
    public $defines = null;
    public $_models = array();
	public $message = "";
	public $messagetype = "";

    function __construct( $config=array() )
    {
        parent::__construct( $config );
        $this->set('suffix', 'users');

    }
}

?>