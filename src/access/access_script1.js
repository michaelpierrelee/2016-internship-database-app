function add_ints(data, bool) {
	display_table(data, bool);
	$.ajax({
		type: 'POST',
		url: "access/access_getfields.php",
		success: function(data_details) {
			$.ajax({
				type: 'POST',
				url: "access/access_verif.php",
				success: function(verif) {
					var admin = false;
					if (verif === "1")
						admin = true;
					detail_generate(data_details, admin); //generate dialog boxes for every internship
					$("#msg").empty()
				},
				error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
			});

		},
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});
}

function convert_null(value, toConvert) {
	if (value === null || value === "none" || value === "")
		return toConvert;
	else
		return value;
}

function encodeElmt(str) {
	str = String(str);
	return encodeURIComponent(
		str.replace("'", "%27").replace(")", "%28").replace("(", "%29")
		);
}

function decodeElmt(str) {
	str = str.toString();
	try {
		str = decodeURIComponent(str).replace("%27", "'").replace("%28", ")").replace("%29", "(");
		return decodeURL(str);
	} catch (e) {
		error_msg(e + " - " + "This string is causing a problem: '" + str + "'.<br />Please search the internship with it and correct the involved field.")
	}
}

function tbody_generate(code, ints, fields) {
	/*GENERATE THE TBODY OF THE INTERNSHIP DATABASE*/

	var value = "";
	for (var i = 0; i < ints.length; i++) {
		code += "<tr class='tr_access'>";
		code += "<td class='details' target='" + ints[i]["id"] + "' onClick=''><i class='fa fa-search-plus'></i></td>";
		for (var f = 0; f < fields.length; f++) {
			value = ints[i][fields[f]];
			//convert value into a string
			value = convert_null(value, "ø");
			/*for (var j = 0; j < value.length; j++) {
				console.log(value[j])
				value[j] = decodeElmt(value[j]);
			}*/
			if (value.length === 1) {
				value = convert_null(value[0], "ø");
				if (value.length > 34)
					value = value.slice(0, 30) + "..."; //don't display a too large string
			} else if (value.length > 1) {
				var temp = "";
				for (var k = 0; k < value.length; k++) {
					value[k] = convert_null(value[k], "ø");
					temp += value[k];
					if (decodeElmt(value[k]).length > 34)
						value[k] = value[k].slice(0, 30) + "..."; //don't display a too large string
					if (k < value.length - 1) temp += "; ";
				}
				value = temp;
			}
			if (fields[f] !== "id")
				code += "<td class='to_color' onClick=''>" + value + "</td>";
		}
		code += "</tr>";
	}
	return code;
}

function detail_generate(fields, admin) {
	var fields = $.parseJSON(fields);
	var code = "";
	for (var i = 0; i < ints_data.length; i++) {
		code += "<div id='" + ints_data[i]["id"] + "' title='Internship details'>";
		for (var step in fields) {
			code += "<form class='pure-form'><fieldset><legend><strong>" + step + "</strong></legend>";
			for (var f in fields[step]) {
				var field = fields[step][f];
				code += "<div class='pure-g'><span class='pure-u-1 pure-u-md-2-5 field_detail'>" + field + "</span>";
				var inCode = "";
				if (ints_data[i][field].length === 1) {
					inCode = ints_data[i][field][0];
				}
				else {
					for (var j = 0; j < ints_data[i][field].length; j++) {
						inCode += ints_data[i][field][j]
						if (j < ints_data[i][field].length - 1)	inCode += ";<br />";
					};
				}
				
				code += "<span class='pure-u-1 pure-u-md-3-5'>" + inCode + "</span></div>";
			}
			code += "</fieldset></form>";
		}
		if (admin)
			code += "<p><button class='pure-button valid_internship' target='" + ints_data[i]["id"] + "'><i class='fa fa-undo'></i> Send back to validation</button></p>";
		code += "</div>";
	}

	$("#div_details").html(code);

	//dialogs
	$("div[role='dialog']").empty() //remove old dialog boxes
	for (var i = 0; i < ints_data.length; i++) {
		$( "[id=" + ints_data[i]["id"] + "]" ).dialog( {
			autoOpen: false
		});

	}
}

function display_filters(ints, fields, disp_filters) {
	/*GENERATE THE FIELDS TO CHOICE THE FILTERS*/
	var code = "<form id='form_filters' class='pure-form'>";
	var code_neg = "<fieldset><legend><strong>Negative Filters</strong></legend>";
	code_neg += "<div id='lab_neg_filters'></div><div id='select_neg_filters'>";
	var code_sel = "</div></fieldset><fieldset><legend><strong>Selective Filters</strong></legend>";
	code_sel += "<div id='lab_sel_filters'></div><div id='select_sel_filters'>";

	for (var f in fields) {
		//create a list of elements for this field and remove redudancies
		var temp = [];
		for (var i in ints) {
			var t = ints[i][fields[f]];
			t = convert_null(t, "ø");
			if (t.length >= 1) {
				for (var j in t) {
					t[j] = convert_null(t[j], "ø");
					var ok = true; //chech for redundancies
					var k = 0;
					while (ok && k < temp.length) {
						if (String(temp[k]).toLowerCase() === t[j].toLowerCase()) ok = false;
						k++;
					} 
					if (ok) temp.push(t[j]);
				}
			} else if ($.inArray(t, temp) === -1) {
				temp.push(t)
			}
		}

		//create code
		if (fields[f] !== "id") {
			//arrange
			temp = temp.sort(sortFilters);
			//NEGATIVE FILTERS
			if (disp_filters[fields[f]]["negative"] === "true") {
				code_neg += "<div class='group_filters'><label class='label_filters' for='neg_" + fields[f] + "' >" + fields[f] + "</label><br />";
				code_neg += "<select id='neg_" + fields[f] + "' class='pure-input-rounded select_neg_filters'>";
				code_neg += "<option value='none'></option>";
				for (var t in temp) {
					toDisp = decodeElmt(temp[t]);
					if (toDisp.length > 35) toDisp = toDisp.substring(0, 34) + "...";
					code_neg += "<option value='" + encodeElmt(temp[t])  + "'>" + toDisp + "</option>";
				}
				code_neg += "</select></div>";
			}
			//SELECTIVE FILTERS
			if (disp_filters[fields[f]]["selective"] === "true") {
				code_sel += "<div class='group_filters'><label class='label_filters' for='sel_" + fields[f] + "' >" + fields[f] + "</label><br />";
				code_sel += "<select id='sel_" + fields[f] + "' class='pure-input-rounded select_sel_filters'>";
				code_sel += "<option value='none'></option>";
				for (var t in temp) {
					toDisp = decodeElmt(temp[t]);
					if (toDisp.length > 35) toDisp = toDisp.substring(0, 34) + "...";
					code_sel += "<option value='" + encodeElmt(temp[t])  + "'>" + toDisp + "</option>";
				}
				code_sel += "</select></div>";
			}
		}
	};

	code += code_neg + code_sel + "</div></fieldset></form>";
	$("#choices").html(code);
}

function display_table(data, def_ints_data) {
	/*GENERATE THE INTERNSHIP DATABASE*/
	wait("#db");
	if (!def_ints_data) {
		var obj = $.parseJSON(data);
		var fields = obj["field names"];
		var disp_filters = obj["filters"];
		//REWORK ARRAY because the internships are not delimited in ints
		var ints = new Array();
		//reshape according to fields.length
		while(obj["internships"].length) ints.push(obj["internships"].splice(0, fields.length));
		//rework lists in array
		for (var i in ints) {
			var temp = {};
			for (var j = 0; j < ints[i].length; j++) {
				var k = Object.keys(ints[i][j])[0];
				//handle when there are special charateres in the elements
				if (ints[i][j][k] !== null && ints[i][j][k].length >= 1) {
					var to_temp = [];
					for (var elmt in ints[i][j][k]) {
						if (ints[i][j][k][elmt] !== null && ints[i][j][k][elmt] !== "")
							to_temp.push(ints[i][j][k][elmt]);
							//to_temp.push(encodeElmt(ints[i][j][k][elmt]));
						else
							to_temp.push("ø");
					}
					temp[k] = to_temp;
				}
				else if (ints[i][j][k] !== null)
					temp[k] = ints[i][j][k];
				else
					temp[k] = ["ø"];
			}
			ints[i] = temp;
		}
		if (typeof ints_data !== "undefined" && ints_data !== null) {
			ints = ints.concat(ints_data);
		}
	}
	else {
		var ints = ints_data;
		var fields = fields_data;
		var disp_filters = filters_data;
	}

	//DISPLAY THE NUMBER OF INTERNSHIPS
	$("#nb_ints_disp").html(ints.length);
	$("#nb_ints").html(obj["total"]);
	if (String(ints.length) === obj["total"])
		$("#plus_ints").prop('disabled', true);

	//GENERATE TABLE CODE
	var code = "<table id='access_table' class='pure-table pure-table-horizontal pure-table-striped'><thead id='head_table'><tr>";
	code += "<th>Details</th>";
	//generate thead
	for (var f in fields) {
		if (fields[f] !== "id")
			code += "<th id='" + fields[f] + "' class='order' onClick=''><i class='fa fa-caret-right'></i> " + fields[f] + "</th>";
	}
	code += "</tr></thead><tbody id='body_table'>";
	//generate tbody
	code = tbody_generate(code, ints, fields);
	code += "</tbody></table>";

	//GENERATE DIALOGS CODE
	code += "<div id='div_details'></div>";	

	//DISPLAY HTML CODE
	$("#db").html(code);
	//display filters
	display_filters(ints, fields, disp_filters)

	//GLOBAL VARIABLE, they are reset when the page menu_script.js is reloaded
	fields_data = fields;
	ints_data = ints;
	current_data = ints_data;
}

function sortIncrease(a, b) {
	//sort a and b according to the 2nd column for a 2D array
	if (typeof a[1] !== 'undefined' && a[1] !== null) {
		var an = String(a[1]);
	}
	else
		return "1";
	if (typeof b[1] !== 'undefined' && b[1] !== null) {
		var bn = String(b[1]);
	}
	else
		return "-1";
	an = padNumbers(an.toLowerCase());
    bn = padNumbers(bn.toLowerCase());
    return an > bn ? 1 : -1;
    //return a[1].localeCompare(b[1]);
}

function sortDecrease(a, b) {
	//sort a and b according to the 2nd column for a 2D array
	if (typeof a[1] !== 'undefined' && a[1] !== null)
		var an = String(a[1]); 
	else
		return "-1";
	if (typeof b[1] !== 'undefined' && b[1] !== null)
		var bn = String(b[1]);
	else
		return "1";
	if (an === "ø" && bn !== "ø") return 1
	else if (an !== "ø" && bn === "ø") return -1
	else if (an === "ø" && bn === "ø") return 0
	an = padNumbers(an.toLowerCase());
    bn = padNumbers(bn.toLowerCase());

    return an < bn ? 1 : -1;
    //return b[1].localeCompare(a[1]);
}

function sortTBODY(sortFunction, id_f) {
	wait("#msg");
	//sort an array with only the key of the array and the column, according to the column
	var ints_col = new Array();
	for (var i = 0; i < current_data.length; i++)
		ints_col.push( [i, current_data[i][id_f]] );
	ints_col = ints_col.sort(sortFunction);
	//sort current_data
	var new_current_data = new Array();
	var k = 0;
	for (var i = 0; i < ints_col.length; i++) {
		k = ints_col[i][0];
		new_current_data.push(current_data[k])
	};
	//regenerate tbody
	$("tbody").html(tbody_generate("", new_current_data, fields_data))
	$("#msg").html("");
}

$(function() {
	$.getScript("access/access_filters.js");

	//CLICK ON AN INTERNSHIP AND CHANGE ITS COLOR
	$(document).on("click", "td[class='to_color']", function(event) {
		event.preventDefault();
		if($(this).parent()[0].tagName !== "THEAD") {
			var children = $(this).parent().children("[class='to_color']");
			var colors = [
				"background-color: rgba(46,204,113,0.5)",
				"background-color: rgba(231,76,60,0.5)",
				"background-color: rgba(52,152,219,0.5)",
				"background-color: rgba(155,89,182,0.5)"
				];
			var id_color = $.inArray(children.attr("style"), colors);
			if (id_color < colors.length - 1)
				children.attr("style", colors[id_color + 1]);		
			else
				children.removeAttr("style");
		}
	});

	//ADD MORE INTERNSHIPS
	$(document).on("click", "#plus_ints", function(event) {
		event.preventDefault();
		wait("#msg");
		$.ajax({
			type: 'POST',
			url: "access/access_getall.php",
			data: {"start": $("#nb_ints_disp").html()},
			success: function(data) {
				//console.log(data)
				add_ints(data, false);
			},
			error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
		});

	});
	//CLICK ON A HEAD AND CHANGE THE ORDER
	$(document).on("click", "th[class='order']", function(event) {
		wait("#msg");
		//save data on the order of the column
		var id_f = $(this).attr("id");
		var child = $(this).children()[0];
		var arrow = $(child).attr("class");
		//reset all arrows
		var flds = $("th[class='order']");
		for (var i = 0; i < flds.length; i++)
			$( $(flds[i]).children()[0] ).attr("class", "fa fa-caret-right");
		//new order
		if (arrow === "fa fa-caret-right" || arrow === "fa fa-caret-down") { //initial order or decrease order
			//change the arrow
			$(child).attr("class", "fa fa-caret-up");
			//sort and regenerate the tbody
			sortTBODY(sortIncrease, id_f);

		} else { //increase order
			$(child).attr("class", "fa fa-caret-down");
			//sort the internships according to the column
			sortTBODY(sortDecrease, id_f);
		}
		$("#msg").empty()
	});

	//CHOICE A FILTER
	$(document).on("change", "select[class*='select_neg_filters']", function(event) {
		wait("#msg");
		chooseFilter(this, "neg");
		$("#msg").empty();
	});
	$(document).on("change", "select[class*='select_sel_filters']", function(event) {
		wait("#msg");
		chooseFilter(this, "sel");
		$("#msg").empty();
	});

	//REMOVE A FILTER
	$(document).on("click", "div[class='neg_filter']", function(event) {
		wait("#msg");
		removeFilter(this, "neg");
		$("#msg").empty()
	});
	$(document).on("click", "div[class='sel_filter']", function(event) {
		wait("#msg");
		removeFilter(this, "sel");
		$("#msg").empty()
	});

	//DISPLAY DETAILS OF A FIELD
	$(document).on("click", "td[class='details']", function () {
		var target = $(this).attr("target");
		$( "[id=" + target + "]" ).dialog("open");
	});

	//SEND BACK AN INTERNSHIP TO VALIDATION
	$(document).on("click", "[class*=valid_internship]", function(event) {
		event.preventDefault();
		var id = $(this).attr("target");
		th = this;
		$.ajax({
			type: 'POST',
			url: "admin/send_to_validation.php",
			data: {"id": id},
			dataType: "text",
			success: function(data) {
				load_new_page("#validation", "admin/admin_validation.php", "admin/admin_validation_script1.js", "admin/admin_validation_script2.js");
				$(th).remove();
			},
			error: function(xhr, ajaxOptions, thrownError) { alert("data no sent:" + JSON.stringify(thrownError)); }
		});
	});

});