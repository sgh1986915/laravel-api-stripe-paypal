var PriceUtil = function () {
};
PriceUtil.compute_items_total_price = function (items, field_name) {
	if(!field_name)field_name='price';
    var total=0;
    for(var i=0;i<items.length;i++){
    	total+=parseFloat($(items[i]).attr(field_name));
    }
    return total;
};
PriceUtil.compute_custom_wdb_items_total_price = function (items) {
    var total=PriceUtil.compute_items_total_price(items, false);
    var discount = (items.length>1 ? 0.2 : 0);
    return total*(1-discount);
};
PriceUtil.compute_cctld_wdb_items_total_price = function (items, type) {
    var total=PriceUtil.compute_items_total_price(items, type+"_price");
    var discount = (items.length>1 ? Math.min(0.5, 0.1 * items.length) : 0);
    return total*(1-discount);
};