let objCommon = {};
objCommon.Ajax = function(sURL, aData){
	// alert("common ajax");
	$.ajax({
    url     : sURL,
    method  : 'post',
    data    : aData,
    headers:
    {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success : function(response){
    	return response;
    },
    error : function(){

    } 
});
}
