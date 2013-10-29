<?php
defined('_JEXEC') or die('Restricted access');
$item = @$this->item;
$form = @$this->form;
$values = @$this->values;
$formName = 'adminForm_fund_'.$item->product_id; 

$return = base64_encode( JRoute::_( 'index.php?option=com_tienda&view=carts&funding=1&tmpl=component') );
	
$working_image = Tienda::getInstance()->get( 'dispay_working_image_product', 1);

JHtml::_("behavior.modal");
Tienda::load( 'TiendaHelperBase', 'helpers._base' );
$js_strings = array( 'COM_TIENDA_UPDATING_ATTRIBUTES' );
TiendaHelperBase::addJsTranslationStrings( $js_strings );
?>
<script type="text/javascript">
	if( typeof Dsc.displayLightboxLevels == 'undefined' )
	{
		Dsc.displayLightboxLevels = function(form)
		{
			var f = tiendaJQ(form);
			var p_id = tiendaJQ( "input[name='product_id']", f ).val();
			var is_lb = f.parent().parent().data('lb');
			if (is_lb )
			{
				// currently, we're in the light box so feel free to refresh it
				return true;
			}
			else
			{
				var w = tiendaJQ(window);
				var data = new Object;
				for(var k in form.elements)
				{
					if( typeof form.elements[k] == 'object' )
					{
						obj = tiendaJQ(form.elements[k]);
						if( obj.is("input, select"))
						{
							if(obj.attr("type") != 'button' &&  obj.attr("name") != "task" &&  obj.attr("name") != "return")
							{
								data[obj.attr("name")] = obj.val();
							}
						}
					}
				}

			    // execute request to server
			    url = com_tienda.jbase + "index.php?option=com_tienda&view=products&tmpl=component&task=addtocart";
			    var a = new Request({
			        url: url,
			        method:"post",
			        data:data,
			        onSuccess: function(response){
						SqueezeBox.open(com_tienda.jbase + "index.php?option=com_tienda&view=carts&tmpl=component&funding=1", { handler : 'iframe', size : {x: (w.width() - 100) , y: (w.height() - 100) } } );
			        }
			    }).send();
				
				return false;
			}
			
		}
	}
</script>


<div>
    <div id="validationmessage_fund_<?php echo $item->product_id; ?>" style="display:none;"></div>
    
    <form action="<?php echo JRoute::_( 'index.php?option=com_tienda&view=products&task=addtocart'); ?>" method="post" class="adminform" name="<?php echo $formName; ?>" enctype="multipart/form-data" >
    <input type="hidden" name="product_qty" value="<?php echo $item->product_parameters->get('default_quantity', '1'); ?>" />
 
    <!-- Add to cart button --> 
    <div id='add_to_cart_<?php echo $item->product_id; ?>' class="add_to_cart" style="display: block;"> 
        <input type="hidden" name="product_id" value="<?php echo $item->product_id; ?>" />
        <input type="hidden" name="filter_category" value="<?php echo $this->filter_category; ?>" />
        <input type="hidden" id="task" name="task" value="" />
        <input type="hidden" id="price" name="price" value="<?php echo $item->price;?>" />
        <?php if( !empty( $values['Itemid'] ) ): ?>
        <input type="hidden" name="Itemid" value="<?php echo ( int )$values['Itemid']; ?>" />        	
        <?php endif; ?>
        <?php echo JHTML::_( 'form.token' ); ?>
        <input type="hidden" name="return" value="<?php echo $return; ?>" />
   
        <?php $onclick = "Dsc.formValidation( '".JRoute::_( @$this->validation )."', 'validationmessage_fund_".$item->product_id."', 'addtocart', document.".$formName.", true, '".JText::_('COM_TIENDA_VALIDATING')."', Dsc.displayLightboxLevels );"; ?>
        
        <?php 
        if (empty($item->product_check_inventory) || (!empty($item->product_check_inventory) && empty($this->invalidQuantity)) ) : ?>
		<div class="fundButton">	
		   	<input type="button" onclick="<?php echo $onclick; ?>" class="full btn btn-large btn-funding" value="FUND THIS PROJECT" />
		</div>

        <?php endif;  ?>
    </div>    
    </form>
</div>
