$(document).ready(function(){
	
	/*
	The below ajax call will send a POST request to the "getCategories.php" page and it will not send any data because it will 
	be expecting only distinct column names from mongoDB. When the data is received from database, it is rendered as html to
	the <div> tag which have id as "categories".
	*/
	
	 
	 $.ajax(
    {
        type:"POST",
        url: "php/getChannel.php",
        success: function( data ) 
        {
			
            $("#channel").html(data);
        }
     });
});

function searchData()
{
	$("#displayData").html("");
	var p = $('#covid').val();
	p = p.trim();
	p = p.replace(" ","_");
	//alert(p);
	$.ajax(
				{
				type:"POST",
				url: "php/searchData.php",
				data: {
					covid : p,
				    fips : $('#Fips').val(),
					channel : $('#channel').val(),
					limit : $('#limit :selected').val(),
					sortfield : $('#sortfield :selected').val(),
					sorttype : $('#sorttype :selected').val(),
				},
				success: function( data ) 
				{
					//The fetched data will be sent with html formating so we can directly render the value as html to <div>
					//area with id "displayData" as output. 
					$("#displayData").html(data);
				}
			});
};

function resetData()
{
	location.reload();	
};