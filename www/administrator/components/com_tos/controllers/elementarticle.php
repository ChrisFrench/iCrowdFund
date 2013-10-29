<?php
/**
 * @package Terms of Service
 * @author  Ammonite Networks
 * @link    http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

class TosControllerElementArticle extends TosController 
{
	function __construct() 
	{
		parent::__construct();
		
		$this->set('suffix', 'elementarticle');
	}
	
    function display($cachable=false, $urlparams = false)
    {
        $this->hidefooter = true;
        
        $object = JRequest::getVar('object');
        $view = $this->getView( $this->get('suffix'), 'html' );
        $view->assign( 'object', $object );
        $view->setTask(true);
        parent::display();
    }
}

?>