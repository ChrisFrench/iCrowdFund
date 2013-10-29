<?php
/**
 * @version 1.5
 * @package Messagebottle
 * @author  Dioscouri Design
 * @link    http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

class MessagebottleModelBase extends DSCModel
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
    public function getTable($name='', $prefix='MessagebottleTable', $options = array())
    {
        JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_messagebottle/tables' );
        return parent::getTable($name, $prefix, $options);
    }

    //TODO DELETE THIS
    //THIS IS JUST HERE FOR DEVELOPMENT TO STOP CACHING LISTS
    public function getList($refresh = true)
    {
        if (empty( $this->_list ) || $refresh)
        {
            $this->_list = parent::getList($refresh);

            $overridden_methods = $this->getOverriddenMethods( get_class($this) );
            if (!in_array('getList', $overridden_methods))
            {
                $dispatcher = JDispatcher::getInstance();
                $dispatcher->trigger( 'onPrepare'.$this->getTable()->get('_suffix').'List', array( &$this->_list ) );
            }
        }
        return $this->_list;
    }
}