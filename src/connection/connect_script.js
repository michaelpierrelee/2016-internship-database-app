$( function() {
	$(document).on("click", "#ask_connect", function (event) {
		event.preventDefault();
		wait("#msg");
		$.ajax({
			type: 'POST',
			url: "connection/connect.php",
			data: { "data": $("#connection").serialize() },
			success: function(getback) {
				if (getback === "connected") {
					$("#msg").empty();
					wait("#load");
					$("#add_button_deco").html("<button class='pure-button' id='ask_deconnect'><i class='fa fa-sign-out'></i> Out</button>");
					load_php("menu/menu.php");
					$.getScript("menu/menu_script.js");
				} else {
					error_msg(getback)
					console.log("failed")
				}
			},
			error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
		});
	});
	$(document).on("click", "#ask_deconnect", function (event) {
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: "connection/deconnect.php",
			success: function(data) { location.reload(true); },
			error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
		});
		
	});
});