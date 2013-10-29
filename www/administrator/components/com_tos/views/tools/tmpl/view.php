<?php 
/**
 * @package Terms of Service
 * @author  Ammonite Networks
 * @link    http://www.ammonitenetworks.com
 * @copyright Copyright (C) 2012 Ammonite Networks. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" >

    <h3>
        <?php echo @$row->name ?>
    </h3>
    
    <?php
        $dispatcher = JDispatcher::getInstance();
        $results = $dispatcher->trigger( 'onGetToolView', array( $row ) );

        for ($i=0; $i<count($results); $i++) 
        {
            $result = $results[$i];
            echo $result;
        }
    ?>
    
    <?php
        echo $form['validate'];
    ?>   
    <input type="hidden" name="id" value="<?php echo @$row->id; ?>" />
    <input type="hidden" name="task" id="task" value="" />
    
</form>