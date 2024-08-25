function sent_validation(data) {
	if (data === "1") {
		//display a success message
		var msg1 = "<div class='isa_success'><p><i class='fa fa-check-square-o'></i> The internship has been validated.</p></div><div id='id1'></div>";
		var msg2 = "<p><button class='pure-button' id='next_validation'><i class='fa fa-arrow-right'></i> Next internship to validate</button></p>";
		$( "#form_tabs" ).html(msg1);
		$( "#id1" ).html(msg2);
	} else {
		error_msg(data);
	}
}

function removed(data) {
	if (data === "1") {
		//display a success message
		var msg1 = "<div class='isa_warning'><p><i class='fa fa-check-square-o'></i> The internship has been deleted.</p></div><div id='id1'></div>";
		var msg2 = "<p><button class='pure-button' id='next_validation'><i class='fa fa-arrow-right'></i> Next internship to validate</button></p>";
		$( "#form_tabs" ).html(msg1);
		$( "#id1" ).html(msg2);
	} else {
		error_msg(data);
	}
}

function load_form(getData) {
	$.ajax({
		type: 'POST',
		url: "form/form_generate.php",
		data: {},
		dataType: "text",
		success: function(data) {
			$("#val_form").html(data);
			$("#form_tabs").tabs();
			$("#send").attr("id", "send_validation"); //to avoid incompatibility with "add internship"
			load_form_after(getData);
		},
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});	
}

function load_form_after(data) {
	var obj = $.parseJSON(data);

	if (obj["id_db"] > 0) {
		//change the values
		$("#nb_interns").html(obj["count"]);
		$("#current").html(obj["id_db"]);
		$("#date").html(obj["date"]);
		$("#val_form").data("id_db", obj["id_db"]);
		$("#val_form").data("date", obj["date"]);
		//complete the form
		var last = obj["internship"];
		for (var key in last) {
			if (last[key] === null) last[key] = ["none"];
			//add new fields if it is needed
			if (last[key].length > 1) {
				//copy the fields
				for (var i = 1; i < last[key].length; i++) {
					if (last[key][i] !== "") {
						var target = key.split(" ").join("_");
						var elmts = $("[class='copy_" + target + "']");
						//copy the field
						var disp = "<div class='pure-control-group'>";
						for(var j = 0; j < elmts.length; j++) {
							var toCopy = $(elmts[j]).html();
							disp += "<span>" + toCopy + "</span>";
						}
						disp += "<span><button class='pure-button added_field'><i class='fa fa-minus'></i></button></span>";
						disp += "</div>";
						//display the copied field
						$("#div_" + target).after(disp);
					}
				};
				//complete fields with the same id
				var fields = $("[id='" + key + "']");
				var datal = $("input[list='" + key + "']")
				for (var i = 0; i < last[key].length; i++) {
					if (last[key][i] !== "none") {
						var value = String(last[key][i])
						console.log(value)
						if (value.length > 25)
							value = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
						$(fields[i]).val(value);
						$(datal[i]).val(value); //for datalists if it's needed
					}
					
				}
			} else if (last[key][0] !== "none") {
				//complete fields with the same id, normal behaviour
				var value = String(last[key]);
				value = decodeURL(value);
				if (value.length < 25)
					value = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
				$("[id='" + key + "']").val(value);
				$("input[list='" + key + "']").val(value); //for datalists
			}
		}
		//add a trash button
		$("#send_validation").after("<button class='pure-button' id='remove_internship' target='" + obj["id_db"] + "'><i class='fa fa-trash-o'></i> Remove</button></p>");

	} else {
		//if there are no internships to validate
		var msg = "<div class='isa_success'><p><i class='fa fa-check-square-o'></i> All internships were validated.</p></div>";
		$("#val_form").html(msg);
		$("#info").html("");
	}
}

$(function() {

	//LOAD NEXT INTERNSHIP TO VALIDATE
	$(document).on("click", "#next_validation", function(event) {
		$.getScript("admin/admin_validation_script2.js");
	});

	//SEND FORM INTO DATABASE
	$(document).on("click", "#send_validation", function(event) {
		//avoid to reload the page
		event.preventDefault();
		//verify that the fields with the attributes required are filled
		var ok = true;
		//send
		if (ok) {
			//get back results form all forms
			var req = serialization();
			//waiting message
			wait_form();
			//send data to the server
			$.ajax({
				type: 'POST',
				url: "admin/admin_validation_recep.php",
				data: {"data": req, "id_db": $("#val_form").data("id_db"), "date": $("#val_form").data("date")},
				dataType: "text",
				success: function(data) { sent_validation(data); },
				error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
			});
		} else {
			error_msg("Some fields are not filled out (in red).");
		}
	});

	//REMOVE INTERNSHIP FROM DATABASE
	$(document).on("click", "#remove_internship", function(event) {
		event.preventDefault();
		var id = $(this).attr("target");
		$.ajax({
			type: 'POST',
			url: "admin/admin_validation_remove.php",
			data: {"id": id},
			dataType: "text",
			success: function(data) { removed(data); },
			error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
		});	
	});
});