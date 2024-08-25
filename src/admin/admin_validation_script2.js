$(function() {
	wait("#val_form");

	//LOAD AN INTERNSHIP TO VALIDATE
	$.ajax({
		type: 'POST',
		url: "admin/admin_validation_get.php",
		data: {},
		dataType: "text",
		success: function(data) { load_form(data); },
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});

	
});