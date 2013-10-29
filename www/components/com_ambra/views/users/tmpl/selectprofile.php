<?php
/**
 * @version	1.5
 * @package	Ambra
 * @author 	Dioscouri Design
 * @link 	http://www.dioscouri.com
 * @copyright Copyright (C) 2007 Dioscouri Design. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');
?>

		<form enctype="multipart/form-data" action="<?php echo JRoute::_( 'index.php&Itemid='.$this->Itemid ); ?>" method="post" name="adminForm" id="adminForm">

		<div class='componentheading'>
            <?php if ($this->pagetitle) { echo $this->pagetitle; } ?>
		</div>
        
		<div id='onBeforeDisplay_wrapper'>
			<?php 
				$dispatcher =& JDispatcher::getInstance();
				$dispatcher->trigger( 'onBeforeDisplaySelectProfileForm', array( $this->row, $this->user ) );
			?>
		</div>
        
		<table class="userlist">
		<tbody>
            <tr>
            	<td class='title'>
	            <div id="cpanel">
	            	
	            <table width="99%" align="center">
				<?php 
				// foreach profile type, display push-button icon next to description
				// onclick, go to registration page for profile
                for ($i=0; $i<count( $this->items ); $i++)
                {
				    $profile = new stdClass();
					
				    $profile->link 			= JRoute::_( 'index.php?option=com_ambra&controller=user&task=edit&profileid='.$this->items[$i]->id.'&Itemid='.$this->Itemid, false );
					$profile->title 		= JText::_( $this->items[$i]->title );
					$profile->description 	= JText::_( $this->items[$i]->description );
					$profile->img 			= JURI::root().'/components/com_ambra/images/profiles.png';
					
					// fire plugin event here or in view.html.php ?
					$dispatcher->trigger( 'onBeforeDisplayProfileItem', array( &$profile ) );
					
					echo "<tr>";
	            	echo "<td valign='top' class='noborder'>";

		              echo "<div style='float:left;'>";
		              echo "<div class='icon'>";

		                echo "<a href='{$profile->link}'>";
		                    echo "<img src='{$profile->img}' alt='{$profile->title}' name='{$profile->title}' align='middle' border='0' />";
							echo '<br>';
		                    echo $profile->title;
						echo "</a>";
						
		              echo "</div>";
		              echo "</div>";
					
					echo '<p>';  
					echo $profile->description;
					echo '</p>';
										
			        echo "</td>";
					echo "</tr>";
                		
				}
				?>
	            </table>
	            
				</div>
					
				</td>
            </tr>
        </tbody>
		</table>

		<div id='onAfterDisplay_wrapper'>
			<?php 
				$dispatcher =& JDispatcher::getInstance();
				$dispatcher->trigger( 'onAfterDisplaySelectProfileForm', array( $this->row, $this->user ) );
			?>
		</div>
		
		<input type="hidden" name="act" value="<?php echo $this->act;?>" />
		<input type="hidden" name="id" value="<?php echo $this->row->get('id');?>" />

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="controller" value="<?php echo $this->_name;?>" />		
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="Itemid" value="<?php echo $this->Itemid;?>" />
        <?php echo JHTML::_( 'form.token' ); ?>
		</form>