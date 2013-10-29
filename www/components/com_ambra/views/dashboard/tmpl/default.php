<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php JHTML::_('stylesheet', 'menu.css', 'media/com_ambra/css/'); ?>
<?php JHTML::_('stylesheet', 'ambra.css', 'media/com_ambra/css/'); ?>
<?php $user = JFactory::getUser(); ?>

<div class='componentheading'>
	<span><?php echo JText::_( "My Account Dashboard" ); ?></span>
</div>

	<?php if ($menu = AmbraMenu::getInstance()) { $menu->display(); } ?>
		
<table style="width: 100%;">
<tr>
	<td style="width: 70%; vertical-align: top; padding-right: 5px;">
	
        <h3>
        <?php echo sprintf( JText::_("Welcome User"), $user->name ); ?>
        </h3>
        
        <?php echo JText::_( "Dashboard Text" ); ?>
        
            <table class="adminlist" style="margin-bottom: 5px;">
            <thead>
            <tr>
                <th colspan="2">
                    <?php echo JText::_( "Account Information" ); ?>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th style="width: 100px;">
                    <?php echo JText::_( "Order History" ); ?>
                </th>
                <td>
	                <a href="<?php echo JRoute::_("index.php?option=com_ambra&view=orders"); ?>">
	                    <?php echo JText::_( "VIEW ORDERS PRINT RECEIPTS" ); ?>
	                </a>
                </td>
            </tr>
            <tr>
                <th style="width: 100px;">
                    <?php echo JText::_( "Profile" ); ?>
                </th>
                <td>
                    <a href="<?php echo JRoute::_("index.php?option=com_ambra&view=accounts"); ?>">
                        <?php echo JText::_( "MODIFY ACCOUNT INFO" ); ?>
                    </a>
                </td>
            </tr>
            <tr>
                <th style="width: 100px;">
                    <?php echo JText::_( "Addresses" ); ?>
                </th>
                <td>
                    <a href="<?php echo JRoute::_("index.php?option=com_ambra&view=addresses"); ?>">
                        <?php echo JText::_( "Manage Billing and Shipping Addresses" ); ?>
                    </a>
                </td>
            </tr>
            </tbody>
            </table>
        
		<?php
		$modules = JModuleHelper::getModules("ambra_dashboard_main");
		$document	= JFactory::getDocument();
		$renderer	= $document->loadRenderer('module');
		$attribs 	= array();
		$attribs['style'] = 'xhtml';
		foreach ( @$modules as $mod ) 
		{
			echo $renderer->render($mod, $attribs);
		}
		?>
		
		<?php
		$modules = JModuleHelper::getModules("ambra_dashboard_right");
		if ($modules)
		{
			?>
		    </td>
		    <td style="vertical-align: top; width: 30%; min-width: 30%; padding-left: 5px;">
            <?php
			$document	= JFactory::getDocument();
			$renderer	= $document->loadRenderer('module');
			$attribs 	= array();
			$attribs['style'] = 'xhtml';
			foreach ( @$modules as $mod ) 
			{
				echo $renderer->render($mod, $attribs);
			}
		}
		?>
	</td>
</tr>
</table>