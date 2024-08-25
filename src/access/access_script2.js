$(function() {
	wait("#msg");

	//LOAD DATA
	if ((typeof ints_data === 'undefined' || ints_data === null) && (typeof fields_data === 'undefined' || fields_data === null)) {
		$.ajax({
			type: 'POST',
			url: "access/access_getall.php",
			data: {"start": 0},
			success: function(data) {
				add_ints(data, false);
			},
			error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
		});
	}
	else //if ints_data and fields_data already exist, load the table with them and without do another ajax request to the server
		display_table("", true);
});