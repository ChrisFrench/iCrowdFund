<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>
<?php JHTML::_( 'script', 'common.js', 'media/com_featureditems/js/' ); ?>
<?php $form = @$this->form; ?>
<?php $row = @$this->row; ?>

<form action="<?php echo JRoute::_( @$form['action'] ) ?>" method="post" class="well adminform" name="adminForm" id="adminForm" enctype="multipart/form-data" >

<table class="table table-striped table-bordered">
    <tr>
        <td class="dsc-key">
            <?php echo JText::_( 'Item Type' ); ?>:
        </td>
        <td>
            <?php $attribs = array( 'class' => 'inputbox', 'size' => '1', 'onchange' => 'Featureditems.displayItemtype(this.options[this.selectedIndex].value);' ); ?>
            <?php echo FeaturedItemsSelect::item_type( @$row->item_type, 'item_type', $attribs ); ?>
            
            <br/>
            
            <div class="item_type" id="default" <?php if (@$row->item_type != 'default' && !empty($row->item_type)) { echo 'style="display: none;"'; } ?>>
                <br/>
                <div class="note" style="clear: both;">
                    <?php echo JText::_( "Default Featured Items use the values below" ); ?>
                </div>
            </div>
            
            <?php // ******************** CALENDAR ITEMS ******************** ?>
            <?php if (JFile::exists( JPATH_ADMINISTRATOR . "/components/com_calendar/defines.php" )) { ?>
            
            <div class="item_type" id="calendar" <?php if (@$row->item_type != 'calendar') { echo 'style="display: none;"'; } ?>>
                <br/>
                <?php echo JText::_( "Please provide an event instance ID number" ); ?>.
                <a href="index.php?option=com_calendar&view=eventinstances" target="_blank">
                    <?php echo JText::_( "Click here to view them in a new window" ); ?>
                </a> 
                <br/>
                <?php $value = (@$row->item_type == 'calendar') ? @$row->fk_id : ''; ?>
                <input type="text" name="calendar_fk_id" value="<?php echo $value; ?>" />
                
                <div class="note" style="clear: both;">
                    <?php echo JText::_( "If provided, values below will be used for display rather than values from the calendar" ); ?>
                </div>
            </div>
            <?php } ?>
            
            <?php // ******************** MEDIAMANAGER ITEMS ******************** ?>
            <?php if (JFile::exists( JPATH_ADMINISTRATOR."/components/com_mediamanager/defines.php" )) { ?>
                       
            <div class="item_type" id="mediamanager" <?php if (@$row->item_type != 'mediamanager') { echo 'style="display: none;"'; } ?>>
                <br/>
                <?php
                if ( !class_exists('MediaManager') ) {
                    JLoader::register( "MediaManager", JPATH_ADMINISTRATOR."/components/com_mediamanager/defines.php" );
                }
                JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_mediamanager/models' );
                $model = JModel::getInstance( 'ElementMedia', 'MediaManagerModel' );
                $value = (@$row->item_type == 'mediamanager') ? @$row->fk_id : '';
                echo $model->fetchElement( 'mediamanager_fk_id', $value );
                echo $model->clearElement( 'mediamanager_fk_id', '' );
                ?>
                <div class="note" style="clear: both;">
                    <?php echo JText::_( "If provided, values below will be used for display rather than values from the media manager" ); ?>
                </div>
            </div>
            <?php } ?>
            <?php // ******************** Tienda ITEMS ******************** ?>
            <?php if (JFile::exists( JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" )) { ?>
                       
            <div class="item_type" id="tienda-campaign" <?php if (@$row->item_type != 'tienda-campaign') { echo 'style="display: none;"'; } ?>>
                <br/>
                <?php
                if ( !class_exists('Tienda') ) {
                    JLoader::register( "Tienda", JPATH_ADMINISTRATOR."/components/com_tienda/defines.php" );
                }
                JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_tienda/models' );
                $model = JModel::getInstance( 'ElementCampaign', 'TiendaModel' );
                $value = (@$row->item_type == 'tienda-campaign') ? @$row->fk_id : '';
                echo $model->fetchElement( 'tienda-campaign_fk_id', $value );
                echo $model->clearElement( 'tienda-campaign_fk_id', '' );
                ?>
                <div class="note" style="clear: both;">
                    <?php echo JText::_( "If provided, values below will be used for display rather than values from the media manager" ); ?>
                </div>
            </div>
            <?php } ?>
            <div class="item_type" id="content" <?php if (@$row->item_type != 'content') { echo 'style="display: none;"'; } ?>>
                <br/>
                <?php
                $model = FeaturedItems::getClass( 'FeaturedItemsModelElementArticle', 'models.elementarticle' );
                $value = (@$row->item_type == 'content') ? @$row->fk_id : '';
                echo $model->fetchElement( 'content_fk_id', $value );
                echo $model->clearElement( 'content_fk_id', '' );
                ?>
                <div class="note" style="clear: both;">
                    <?php echo JText::_( "If provided, values below will be used for display rather than values from the content manager" ); ?>
                </div>
            </div>
            
            <div class="item_type" id="content-category" <?php if (@$row->item_type != 'content-category') { echo 'style="display: none;"'; } ?>>
                <br/>
                <?php $value = (@$row->item_type == 'content-category') ? @$row->fk_id : ''; ?>
                <select name="content-category_fk_id" class="inputbox">
                    <?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_content'), 'value', 'text', $value ); ?>
                </select>
                
                <div class="note" style="clear: both;">
                    <?php echo JText::_( "If provided, values below will be used for display rather than values from the category manager" ); ?>
                </div>
            </div>
            
        </td>
    </tr>
	<tr>
		<td class="dsc-key">
			<?php echo JText::_( 'Item Enabled' ); ?>:
		</td>
		<td>
			<?php echo JHTML::_( 'select.booleanlist', 'item_enabled', '', @$row->item_enabled ); ?>
		</td>
	</tr>
	<tr>
		<td class="dsc-key">
			<?php echo JText::_( 'Item Label' ); ?>:
		</td>
		<td>
			<input type="text" name="item_label" value="<?php echo @$row->item_label; ?>" size="48" maxlength="250"  />
		</td>
	</tr>
	<tr>
		<td class="dsc-key">
			<?php echo JText::_( 'Item Category' ); ?>:
		</td>
		<td>
			<?php echo FeatureditemsSelect::category( @$row->category_id, 'category_id' ); ?>

		</td>
	</tr>
    <tr>
		<td class="dsc-key">
			<?php echo JText::_( 'Short Title' ); ?>:
		</td>
		<td>
			<input type="text" name="item_short_title" value="<?php echo @$row->item_short_title; ?>" size="50" maxlength="250"  />
		</td>
	</tr>
    <tr>
		<td class="dsc-key">
			<?php echo JText::_( 'Long Title' ); ?>:
		</td>
		<td>
			<input type="text" name="item_long_title" value="<?php echo @$row->item_long_title; ?>" size="150" maxlength="250"  />
		</td>
	</tr>
    <tr>
		<td class="dsc-key"> 		
			<?php echo JText::_( 'Remote Image' ); ?>:
		</td>
		<td>
			<?php
			if ( !empty( $row->item_image_url ) )
			{
			    ?>
                <img src="<?php echo $row->item_image_url; ?>" style="max-height: 125px; max-width: 125px" />
                <?php
			}
			?>
			<br />
			<input type="text" name="item_image_url" size="150" maxlength="250" value="<?php echo @$row->item_image_url; ?>" />
			
            <p class="dsc-tip dsc-clear">
            <?php echo JText::_( "COM_FEATUREDITEMS_REMOTE_IMAGE_URL_TIP" ); ?>
            </p>
		</td>
	</tr>
	<tr>
		<td class="dsc-key">
			<?php echo JText::_( 'Current Local Image' ); ?>:
		</td>
		<td>
			<input type="text" name="item_image_local_filename" value="<?php echo @$row->item_image_local_filename; ?>" size="50" />
		</td>
	</tr>
	<tr>
		<td class="dsc-key">
			<label for="item_image_local_new">
			<?php echo JText::_( 'Upload New Local Image' ); ?>:
			</label>
		</td>
		<td>
			<input name="item_image_local_new" type="file" size="40" />
            <p class="dsc-tip dsc-clear">
            <?php echo JText::sprintf( "COM_FEATUREDITEMS_LOCAL_IMAGE_PATH_TIP", Featureditems::getPath( 'item_images' ) ); ?>
            </p>
		</td>
	</tr>    			
	<tr>
		<td class="dsc-key">
			<?php echo JText::_( 'URL' ); ?>:
		</td>
		<td>
			<input type="text" name="item_url" value="<?php echo @$row->item_url; ?>" size="150" maxlength="250"  />
			
            <p class="dsc-tip dsc-clear">
            <?php echo JText::_( "COM_FEATUREDITEMS_URL_TIP" ); ?>
            </p>
		</td>
	</tr>
	<tr>
		<td class="dsc-key">
			<?php echo JText::_( 'URL Target' ); ?>:
		</td>
		<td>
			<?php echo FeaturedItemsSelect::url_target( @$row->item_url_target, 'item_url_target' ); ?>
		</td>
	</tr>
    <tr>
        <td class="dsc-key">
            <?php echo JText::_( 'Publish Up' ); ?>:
        </td>
        <td>
            <?php echo JHTML::calendar( @$row->publish_up, "publish_up", "publish_up", '%Y-%m-%d', array('size'=>'25') ); ?>
        </td>
    </tr>
    <tr>
        <td class="dsc-key">
            <?php echo JText::_( 'Publish Down' ); ?>:
        </td>
        <td>
            <?php echo JHTML::calendar( @$row->publish_down, "publish_down", "publish_down", '%Y-%m-%d', array('size'=>'25') ); ?>
        </td>
    </tr>
	<?php 
	if (!empty($row->item_id)) 
	{
    	$tagsHelper = new FeaturedItemsHelperTags();
    	if ($tagsHelper->isInstalled()) 
    	{ 
        	?>
            <tr>
        		<td colspan="2">
        			<?php echo $tagsHelper->getForm( $row->item_id ); ?>
        		</td>
        	</tr>
		    <?php 
    	}
    } 
    ?>
    <tr>
        <td class="dsc-key">
            <?php echo JText::_( 'Description' ); ?>:
        </td>
        <td>
            <?php $editor = JFactory::getEditor( ); ?>
            <?php echo $editor->display( 'item_description', @$row->item_description, '100%', '450', '100', '20' ); ?>
        </td>
    </tr>
</table>

<div>
	<input type="hidden" name="id" value="<?php echo @$row->item_id; ?>" />
	<input type="hidden" name="task" value="" />
</div>

</form>