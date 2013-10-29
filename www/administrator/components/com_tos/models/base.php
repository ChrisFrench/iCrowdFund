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


class TosModelBase extends DSCModel
{
	 /**
     * Method to get a table object, load it if necessary.
     *
     * @access  public
     * @param   string The table name. Optional.
     * @param   string The class prefix. Optional.
     * @param   array   Configuration array for model. Optional.
     * @return  object  The table
     * @since   1.5
     */
    public function getTable($name='', $prefix='TosTable', $options = array())
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_tos/tables' );
        return parent::getTable($name, $prefix, $options);
    }

}