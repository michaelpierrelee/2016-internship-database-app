function load_up_backups() {
	//load admin_fields_up
	wait("#msg");
	$.ajax({
		type: 'POST',
		url: "backups/backups_get.php",
		success: function(data) {
			$("#backups").html(data);
			$("#msg").empty();
		},
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});
}

$(function() {
	//MAKE A BACKUP
	$(document).on("click", "#export", function(event) {
		event.preventDefault();
		if (confirm("Are you sure to make an export?")) {
			$.ajax({
				type: 'POST',
				url: "backups/backups_export.php",
				success: function(data) {
					var toDisp = "<div class='isa_success'><i class='fa fa-check-square-o'></i>Backup done.</div>";
					if (data !== "1") nosent_data("", "Fail. No options", data);
					else $( "#msg" ).html(toDisp);
					load_up_backups();
				},
				error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
			});
		}
	});

	//RESTORE A BACKUP
	$(document).on("click", "#restore", function(event) {
		event.preventDefault();
		//if (confirm("Are you sure to make this import?")) {
			$.ajax({
				type: 'POST',
				url: "backups/backups_import.php",
				data: {"timestamp": $("#import").serialize()},
				success: function(data) {
					var toDisp = "<div class='isa_success'><i class='fa fa-check-square-o'></i>Import done.</div>";
					if (data === "0") nosent_data("Fail", "No backup executed", data);
					else if (data === "-1") nosent_data("", "Fail. Only one or too many backups of tables occured", data);
					else $( "#msg" ).html(toDisp);
				},
				error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
			});
		//}
	});

	//DELETE A BACKUP
	$(document).on("click", "#delete", function(event) {
		event.preventDefault();
		if (confirm("Are you sure to remove this backup?")) {
			$.ajax({
				type: 'POST',
				url: "backups/backups_delete.php",
				data: {"timestamp": $("#import").serialize()},
				success: function(data) {
					var toDisp = "<div class='isa_success'><i class='fa fa-check-square-o'></i>Backup deleted.</div>";
					if (data === "0") nosent_data("", "Fail. No deletions", data);
					else if (data === "-1") nosent_data("", "Fail. Only one or too many deletions occured", data);
					else $( "#msg" ).html(toDisp);
					load_up_backups();
				},
				error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
			});
		}
	});
});