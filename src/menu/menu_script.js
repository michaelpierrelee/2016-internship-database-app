function click_event(selector, php, js1, js2) {
	$(document).on("click", selector, function(event) {
		event.preventDefault();
		load_new_page(selector, php, js1, js2);
	});
}

function load_new_page(selector, php, js1, js2) {
	$.ajax({
		type: 'POST',
		url: php,
		success: function(data) {
			$("#load").html(data);
			if (typeof loaded_var[selector] === "undefined") { //first time that the js script is loaded
				$.getScript(js1, function() { //load the functions and events
					$.getScript(js2);
				});
				loaded_var[selector] = true; //load what is needed to be load at each tile
			} else {
				$.getScript(js2);
			}
			
		},
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});
	ints_data = null;
	current_data = null;
	fields_data = null;
	filters_data = null;
}


$( function() {
	//init a variable to know if a script was already loaded
	if (typeof loaded_var === "undefined") {
		loaded_var = {};
	}
	//load form.php
	click_event("#form", "form/form.php", "form/form_script1.js", "form/form_script2.js");
	//load access.php
	click_event("#access", "access/access.php", "access/access_script1.js", "access/access_script2.js");
	//load admin_validation.php
	click_event("#validation", "admin/admin_validation.php", "admin/admin_validation_script1.js", "admin/admin_validation_script2.js");
	//load admin_fields.php
	click_event("#fields", "admin/admin_fields.php", "admin/admin_fields_script1.js", "admin/admin_fields_script2.js");
	//load admin_members.php
	click_event("#members", "admin/admin_members.php", "admin/admin_members_script1.js", "admin/admin_members_script2.js");
	//load backups.php
	click_event("#save", "backups/backups.php", "backups/backups_script1.js", "backups/backups_script2.js");
	//load admin_remove.php
	click_event("#remove", "admin/admin_remove.php", "admin/admin_remove_script1.js", "admin/admin_remove_script2.js");
});
