function load_recep(type, req) {
	//load admin_fields_recep
	$.ajax({
		type: 'POST',
		url: "admin/admin_fields_recep.php",
		data: {"type" : type, "data": req},
		success: function(data) {
			$("#load_field").html(data);
			load_up();
		},
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});
}

function load_up() {
	//load admin_fields_up
	$.ajax({
		type: 'POST',
		url: "admin/admin_fields_up.php",
		data: {},
		success: function(data) { $("#exist").html(data); },
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});
}

$(function() {
	//RELOAD ADD FORM
	$(document).on("click", "#reload_add", function(event) {
		event.preventDefault();
		load_recep(0, 0);
	});

	//WHEN SUBMIT A FORM TO REMOVE A FIELD
	$(document).on("click", "#submit_remove", function(event) {
		event.preventDefault();
		load_recep("remove", $("#form_exist").serialize());
	});

	//WHEN SUBMIT A FORM TO MODIFY A FIELD
	$(document).on("click", "#submit_modify", function(event) {
		event.preventDefault();
		load_recep("modify", $("#form_exist").serialize());
	});

	//WHEN SUBMIT A FORM TO ADD A FIELD
	$(document).on("click", "#submit_add", function(event) {
		event.preventDefault();
		load_recep("add", $("#form_add").serialize());
	});

	//WHEN SUBMIT A FORM TO UPDATE A FIELD
	$(document).on("click", "#submit_update", function(event) {
		event.preventDefault();
		load_recep("update", $("#form_update").serialize());
	});
});