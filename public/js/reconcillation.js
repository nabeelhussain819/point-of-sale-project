let obj = {};
 

obj.confirm = function(data){
    var sUrl = APP_URL+"/reconciliation/matchConciliation/" ; 
    aData = {
        "type": data.parent().parent().attr("data-name"),
        "inventory_id": data.attr('data-id'),
        "product_id": data.attr('data-product-id'),
        "concile_id": data.attr('data-concile-id'),
    }
    // console.log(aData);
	console.log(objCommon.Ajax(sUrl, aData));
    window.location = APP_URL+"/reconciliation/";
} 

$(document).ready(function(){


    $("#contact div#action").remove();

    $("#action").on('click' , function(){
        obj.confirm($(this));
    });
})