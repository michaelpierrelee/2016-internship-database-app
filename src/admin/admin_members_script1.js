function load_mb_get (first) {
	$.ajax({
		type: 'POST',
		url: "admin/admin_members_get.php",
		success: function(data) {
			$("#exist_mb").html(data);
			if (first) $("#msg").html("");
		},
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});
}

$(function() {
	//WHEN SUBMIT A FORM TO ADD A MEMBER
	$(document).on("click", "#add_mb", function(event) {
		event.preventDefault();
		wait("#msg");
		$.ajax({
			type: 'POST',
			url: "admin/admin_members_recep.php",
			data: {"type" : "add", "data": $("#form_new_mb").serialize()},
			success: function(data) {
				$("#msg").html(data);
				load_mb_get(false);
				$("#form_new_mb").trigger("reset");
			},
			error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
		});
	});

	//WHEN SUBMIT A FORM TO REMOVE MEMBERS
	$(document).on("click", "#remove_mb", function(event) {
		event.preventDefault();
		wait("#msg");
		$.ajax({
			type: 'POST',
			url: "admin/admin_members_recep.php",
			data: {"type" : "remove", "data": $("#form_mbs").serialize()},
			success: function(data) {
				$("#msg").html(data);
				load_mb_get(false);
				$("#form_new_mb").trigger("reset");
			},
			error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
		});
	});
});