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

			<table class="table table-striped table-bordered">
				<tr>
					<td style="width: 100px; text-align: right;" class="key">
						<?php echo JText::_( 'Title' ); ?>:
					</td>
					<td>
						<input  name="terms_title" value="<?php echo @$row->terms_title; ?>" size="48" maxlength="250" type="text" />
					</td>
				</tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'terms' ); ?>:
                    </td>
                    <td>
                        <?php $editor = JFactory::getEditor(); ?> <?php echo $editor->display( 'terms',  @$row->terms, '100%', '300', '75', '10' ) ; ?>
                       
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Scope' ); ?>:
                    </td>
                    <td>
                        <?php echo TosSelect::scope(@$row->scope_id, 'scope_id'); ?>
        
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Expire Date' ); ?>:
                    </td>
                    <td>
                       <?php echo JHTML::calendar(@$row->expires_date, "expires_date", "expires_date" ); ?>
        
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="key">
                        <?php echo JText::_( 'Enabled' ); ?>:
                    </td>
                    <td>
                        <?php echo  DSCGrid::btbooleanlist('enabled', '', @$row->enabled, 'Enabled', 'Disabled') ; ?>
                      
                    </td>
                </tr>    
			</table>
			
			<input type="hidden" name="task" value="" />

</form>