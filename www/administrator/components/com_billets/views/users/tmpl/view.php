<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>
<?php  $datelayout = Billets::getInstance()->get( 'datelayout', 'DATE_FORMAT_LC2' );   ?>


<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" >

    <?php echo DSCGrid::pagetooltip( "users_view" ); ?>

    <fieldset>
        <legend><?php echo JText::_('COM_BILLETS_USER'); ?></legend>
        <div id="billets_header">
            <table class="admintable" style="width: 99%;">
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="name">
                        <?php echo JText::_('COM_BILLETS_NAME'); ?>:
                        </label>
                    </td>
                    <td>
                        <div class="name"><?php echo @$row->name; ?></div>          
                    </td>
                    <td width="100" align="right" class="key">
                        <label for="registerDate">
                        <?php echo JText::_('COM_BILLETS_REGISTERED'); ?>:
                        </label>
                    </td>
                    <td>
                        <div class="registerDate"><?php echo JHTML::_('date', @$row->registerDate, JText::_($datelayout)); ?></div>         
                    </td>
                    <td>
                        <?php
                        $config = Billets::getInstance();
                        $url = $config->get( "user_edit_url", "index.php?option=com_users&view=user&task=edit&cid[]=");
                        $url .= @$row->id; 
                        $text = "<button>".JText::_('COM_BILLETS_CLICK_HERE_TO_EDIT_USER')."</button>";
                        ?>
                        <div style="float: right;"><?php echo BilletsUrl::popup( $url, $text, '', '', '', '', '', true ); ?></div>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="username">
                        <?php echo JText::_('COM_BILLETS_USERNAME'); ?>:
                        </label>
                    </td>
                    <td>
                        <div class="username"><?php echo @$row->username; ?></div>          
                    </td>
                    <td width="100" align="right" class="key">
                        <label for="lastvisitDate">
                        <?php echo JText::_('COM_BILLETS_LAST_VISITED'); ?>:
                        </label>
                    </td>
                    <td>
                        <div class="lastvisitDate"><?php echo JHTML::_('date', @$row->lastvisitDate, JText::_($datelayout)); ?></div>           
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="email">
                        <?php echo JText::_('COM_BILLETS_EMAIL'); ?>:
                        </label>
                    </td>
                    <td>
                        <div class="email"><?php echo @$row->email; ?></div>            
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="id">
                        <?php echo JText::_('COM_BILLETS_ID'); ?>:
                        </label>
                    </td>
                    <td>
                        <div class="id"><?php echo @$row->id; ?></div>          
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
            </table>
        </div>
    </fieldset>
    
    <table style="width: 100%;">
    <tr>
        <td style="width: 70%; max-width: 70%; vertical-align: top; padding: 0px 5px 0px 5px;">
            <?php
            $modules = JModuleHelper::getModules("billets_user_main");
            $document   = JFactory::getDocument();
            $renderer   = $document->loadRenderer('module');
            $attribs    = array();
            $attribs['style'] = 'xhtml';
            foreach ( @$modules as $mod ) 
            {
                echo $renderer->render($mod, $attribs);
            }
            ?>
        </td>
        <td style="vertical-align: top; width: 30%; min-width: 30%; padding: 0px 5px 0px 5px;">     
            <?php
            $modules = JModuleHelper::getModules("billets_user_right");
            $document   = JFactory::getDocument();
            $renderer   = $document->loadRenderer('module');
            $attribs    = array();
            $attribs['style'] = 'xhtml';
            foreach ( @$modules as $mod ) 
            {
                echo $renderer->render($mod, $attribs);
            }
            ?>
        </td>
    </tr>
    </table>
    
    <input type="hidden" name="id" value="<?php echo @$row->id; ?>" />
    <input type="hidden" name="task" value="" />
    
</form>