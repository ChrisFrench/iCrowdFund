<?php defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

    <fieldset>
        <legend>Featured Items</legend>
        <div class="control-group">
            <label for="optionsCheckbox" class="control-label"></label>
            <div class="controls">
                <label class="checkbox"> <input type="checkbox" value="1" name="featureditem_create_new" id="featureditem_create_new">Create a new Featured Item from this article</label>
            </div>
        </div>
        <?php if (!empty($vars->items)) { ?>
        <h3 class="dsc-clear">
            Existing Featured Items based on this article
        </h3>
        
        <table class="table table-striped adminlist">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Label</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; foreach ($vars->items as $key=>$item) { $i++; ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $item->item_id; ?></td>
                    <td>
                        <?php echo $item->short_title; ?>
                        <?php echo $item->long_title ? '<p class="help-block">' . $item->long_title . '</p>' : ''; ?>
                    </td>
                    <td><?php echo $item->label; ?></td>
                    <td><a href="<?php echo JRoute::_( "index.php?option=com_featureditems&view=items&task=edit&id=" . $item->item_id ); ?>" target="_blank">Edit</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>
    </fieldset>
