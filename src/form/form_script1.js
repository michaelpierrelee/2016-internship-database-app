function sent_form(data) {
	if (data === "1") {
		//display a success message and propose to get back to the main menue
		var msg = "<div class='isa_success'><p><i class='fa fa-check-square-o'></i>The request was sent. The internship will be validated soon by an administrator. Thank you.</p></div>";
		//display it
		$( "#form_tabs" ).html(msg);
	}
	else {
		error_msg(data);
	}
}

$( function() {
	//SEND FORM INTO DATABASE
	$(document).on("click", "#send", function(event) {
		//avoid to reload the page
		event.preventDefault();
		//verify that the fields with the attributes required are filled
		var ok = verify_required();
		//send
		if(ok) {
			//get back results form all forms
			var req = serialization()
			//waiting message
			wait_form();
			//send data to the server
			$.ajax({
				type: 'POST',
				url: "form/form_recep.php",
				data: {"data": req},
				dataType: "text",
				success: function(data) { sent_form(data); },
				error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
			});
		} else {
			error_msg("Some fields are not filled out (in red).");
		}
	});
});




