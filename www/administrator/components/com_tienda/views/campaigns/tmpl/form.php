<?php
	defined('_JEXEC') or die('Restricted access');
	$form = @$this->form;
	$row = @$this->row; 

?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" >
    
    <?php
        // fire plugin event here to enable extending the form
        JDispatcher::getInstance()->trigger('onBeforeDisplayCampaignForm', array( $row ) );                    
    ?>
    
    <table style="width: 100%">
    <tr>
        <td style="vertical-align: top; width: 65%;">
    			<table class="table table-striped table-bordered">
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="campaign_name">
    						<?php echo JText::_('COM_TIENDA_NAME'); ?>:
    						</label>
    					</td>
    					<td>
    						<input type="text" name="campaign_name" id="campaign_name" size="48" maxlength="250" value="<?php echo @$row->campaign_name; ?>" />
    					</td>
    				</tr>
                    <tr>
                        <td style="width: 100px; text-align: right;" class="key">
                            <?php echo JText::_('COM_TIENDA_ALIAS'); ?>:
                        </td>
                        <td>
                            <input name="campaign_alias" id="campaign_alias" value="<?php echo @$row->campaign_alias; ?>" type="text" size="48" maxlength="250" />
                        </td>
                    </tr>
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="category">
    						Category:
    						</label>
    					</td>
    					<td>
    						<?php 
                                
                                echo TiendaHelperCampaign::getSectorsSelectList(@$row->category_id, 'category_id');
                                ?>
    						
    					</td>
    				</tr>
                    <tr>
                        <td style="width: 100px; text-align: right;" class="key">
                            <label for="app_fee">
                            App Fee:
                            </label>
                        </td>
                        <td>
                            <?php echo TiendaHelperCampaign::appFee(@$row->app_fee, 'app_fee'); ?>
                            
                        </td>
                    </tr>
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="campaign_owner">
    						Campaign Owner:
    						</label>
    					</td>
    					<td>
    					
           
                        <?php
                        Tienda::load('TiendaModelElementUser', 'models.elementuser');
						$elementUserModel = DSCModel::getInstance('ElementUser', 'TiendaModel');
                        echo $elementUserModel->fetchElement( 'user_id', @$row->user_id ); 
                      //  echo $elementUserModel->clearElement( 'user_id', '' );
                         
                        ?>
                        
    					</td>
    				</tr>
    				
    				
    				
    				
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="enabled">
    						<?php echo JText::_('COM_TIENDA_ENABLED'); ?>:
    						</label>
    					</td>
    					<td>
    						<?php echo TiendaSelect::btbooleanlist( 'campaign_enabled', '', @$row->campaign_enabled ); ?>
    					</td>
    				</tr>
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="campaign_goal">
    						Funding Goal:
    						</label>
    					</td>
    					<td>
    						$ <input name="campaign_goal" id="campaign_goal" value="<?php echo @$row->campaign_goal; ?>" type="text" size="28" maxlength="250" /> / $ <input name="campaign_raised" id="campaign_raised" disabled="disabled" value="<?php echo @$row->campaign_raised; ?>" type="text" size="28" maxlength="250" />

    					</td>
    				</tr>


                    
                    <tr>
                        <td style="width: 100px; text-align: right;" class="key">
                            <label for="campaign_stats">
                           Funding Type:
                            </label>
                        </td>
                        <td class="flat">
                            <?php  echo TiendaHelperCampaign::campaignFundingType(@$row->fundingtype, 'fundingtype'); ?>
                            
                        </td>
                    </tr>
                    
                     <tr>
                        <td style="width: 100px; text-align: right;" class="key">
                            <label for="campaign_stats">
                           Campaign Type:
                            </label>
                        </td>
                        <td class="flat">
                            <?php  echo TiendaHelperCampaign::campaignType(@$row->type, 'type'); ?>
                            
                        </td>
                    </tr>

    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="campaign_stats">
    						Quick Stats :
    						</label>
    					</td>
    					<td class="flat">
    						<?php  echo TiendaHelperCampaign::displayCampaignStats(@$row); ?>
    						
    					</td>
    				</tr>
    				
    				<tr>
    					<td colspan="2" style="width: 100px; text-align: right;" >
    						<div class="well options">
            <legend>Campaign Dates</legend>
            <table class="table table-striped table-bordered" style="width: 100%;">
                <tr>
                    <td style="width: 100px; text-align: right;" class="dsc-key">
                        Start Funding Date:
                    </td>
                    <td>
                        <?php echo JHTML::calendar( @$row->fundingstart_date, "fundingstart_date", "fundingstart_date", '%Y-%m-%d %H:%M:%S', array('size'=>25) ); ?>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100px; text-align: right;" class="dsc-key">
                        Stop Funding Date:
                    </td>
                    <td>
                        <?php echo JHTML::calendar( @$row->fundingend_date, "fundingend_date", "fundingend_date", '%Y-%m-%d %H:%M:%S', array('size'=>25) ); ?>
                    </td>
                </tr>
            </table>
            </div>
    					</td>
    				</tr>
    				<tr>
                        <td style="width: 100px; text-align: right;" class="key">
                            <label for="campaign_full_image">
                            <?php echo JText::_('Campaign Video'); ?>:
                            </label>
                        </td>
                        <td>
                            <?php
                            if(strlen(@$row->video)) {
                                 echo TiendaHelperCampaign::Video(@$row->video);  
                            }
                           
                            ?>
                            <br />
                            <input type="text" class="input-xxlarge"  name="video" id="video" size="158" maxlength="250" value="<?php echo @$row->video; ?>" />
                        </td>
                    </tr>
                   
    				
    				
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="campaign_full_image">
    						<?php echo JText::_('Campaign Image'); ?>:
    						</label>
    					</td>
    					<td>
    						<?php
    						echo TiendaHelperCampaign::getImage($row, 'full');
    						?>
    						<br />
    						<input type="text" disabled="disabled" name="campaign_full_image" id="campaign_full_image" size="48" maxlength="250" value="<?php echo @$row->campaign_full_image; ?>" />
    					</td>
    				</tr>
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="campaign_full_image_new">
    						<?php echo JText::_('COM_TIENDA_UPLOAD_NEW_IMAGE'); ?>:
    						</label>
    					</td>
    					<td>
    						<input name="campaign_full_image_new" type="file" size="40" />
    					</td>
    				</tr>
    				<?php /*
                    <tr>
                        <td style="vertical-align: top; width: 100px; text-align: right;" class="key">
                            <?php echo JText::_('COM_TIENDA_CATEGORY_LAYOUT_FILE'); ?>:
                        </td>
                        <td>
                            <?php echo TiendaSelect::campaignlayout( @$row->campaign_layout, 'campaign_layout' ); ?>
                            <div class="well note">
                                <?php echo JText::_('COM_TIENDA_CATEGORY_LAYOUT_FILE_DESC'); ?>
                            </div>                        
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; width: 100px; text-align: right;" class="key">
                            <?php echo JText::_('COM_TIENDA_CATEGORY_PRODUCTS_LAYOUT_FILE'); ?>:
                        </td>
                        <td>
                            <?php echo TiendaSelect::productlayout( @$row->campaignproducts_layout, 'campaignproducts_layout' ); ?>
                            <div class="well note">
                                <?php echo JText::_('COM_TIENDA_CATEGORY_PRODUCTS_LAYOUT_FILE_DESC'); ?>
                            </div>                        
                        </td>
                    </tr> */
                    ?>
                    <tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="campaign_description">
    						<?php echo JText::_('Short Description'); ?>:
    						</label>
    					</td>
    					<td>
    						<?php $editor = JFactory::getEditor(); ?>
    						<?php echo $editor->display( 'campaign_shortdescription',  @$row->campaign_shortdescription, '100%', '250', '100', '20' ) ; ?>
    					</td>
    				</tr>
    				<tr>
    					<td style="width: 100px; text-align: right;" class="key">
    						<label for="campaign_description">
    						<?php echo JText::_('COM_TIENDA_DESCRIPTION'); ?>:
    						</label>
    					</td>
    					<td>
    						<?php $editor = JFactory::getEditor(); ?>
    						<?php echo $editor->display( 'campaign_description',  @$row->campaign_description, '100%', '450', '100', '20' ) ; ?>
    					</td>
    				</tr>
                   
    			</table>
    
    			<input type="hidden" name="id" value="<?php echo @$row->campaign_id?>" />
    			<input type="hidden" name="task" value="" />
    	
            <?php
                // fire plugin event here to enable extending the form
                JDispatcher::getInstance()->trigger('onAfterDisplayCampaignFormMainColumn', array( $row ) );                    
            ?>

        </td>
        <td style="max-width: 35%; min-width: 35%; width: 35%; vertical-align: top;">

        <?php
            // fire plugin event here to enable extending the form
            JDispatcher::getInstance()->trigger('onAfterDisplayCampaignFormRightColumn', array( $row ) );                    
        ?>
        </td>
    </tr>
    </table>

    <?php
        // fire plugin event here to enable extending the form
        JDispatcher::getInstance()->trigger('onAfterDisplayCampaignForm', array( $row ) );                    
    ?>

</form>