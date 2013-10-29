if (typeof(Featureditems) === 'undefined') {
    var Featureditems = {};
}

Featureditems.displayItemtype = function( item_type )
{
    if (item_type != '')
    {
        $$('div.item_type').setStyle('display', 'none');
        $$('div#' + item_type ).setStyle('display', 'block');
    }
    else
    {
        $$('div.item_type').setStyle('display', 'none');
    }
}

Featureditems.injectForm = function(html)
{
    form = jQuery('#item-form');
    form.prepend(html);
}