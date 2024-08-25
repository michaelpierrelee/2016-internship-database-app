function load_up_remove() {
	//load admin_fields_up
	wait("#msg");
	$.ajax({
		type: 'POST',
		url: "admin/admin_remove_get.php",
		success: function(data) {
			$("#promo_remove").html(data);
			$("#msg").empty();
		},
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});
}

$(function() {
	$(document).on("click", "#delete_promo", function(event) {
		event.preventDefault();
		if (confirm("Are you sure?")) {
			wait("#msg");
			$.ajax({
				type: 'POST',
				url: "admin/admin_remove_recep.php",
				data: { "data": $("#chosen_promo").val() },
				success: function(data) {
					var toDisp = "<div class='isa_success'><i class='fa fa-check-square-o'></i> Deletion done.</div>";
					if (data !== "1") nosent_data("", "Deletion failed", data);
					else {
						$( "#msg" ).html(toDisp);
						$("#promo_remove").empty();
					}
				},
				error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
			});
		}
	});

});