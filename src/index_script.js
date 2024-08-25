function error_msg(msg) {
	//display a message for the error
	var toDisp = "<div class='isa_error'><i class='fa fa-times-circle'></i>An error had occured:<br />";	
	toDisp += msg + "</div>";
	$( "#msg" ).html(toDisp);
}

function nosent_data(xhr, ajaxOptions, thrownError) {
	var msg = "";
	msg += thrownError + " - " + ajaxOptions;
	error_msg(msg);
}

function wait_form() {
	wait("#msg");
}

function wait(selector) {
	//display a message to wait
	var msg = "<div class='isa_info'><i class='fa fa-spinner fa-spin fa-3x fa-fw'></i>Loading, please wait.</div>";
	$(selector).html(msg);
}

function load_php(php) {
	$.ajax({
		type: 'POST',
		url: php,
		success: function(data) { $("#load").html(data); },
		error: function(xhr, ajaxOptions, thrownError) { nosent_data(xhr, ajaxOptions, thrownError); }
	});
}

function menu() {
	$(document).on("click", "#menu", function(event) {
		event.preventDefault();
		load_php("menu/menu.php");
	});
}

function add_newField() {
	$(document).on("click", "button[class*='new_field']", function(event) {
		//avoid to reload the page
		event.preventDefault();
		//search the field to copy
		var target = $(this).attr("target");
		var elmts = $("[class='copy_" + target + "']");
		//copy the field
		var disp = "<div class='pure-control-group'>";
		for(var i = 0; i < elmts.length; i++) {
			var toCopy = $(elmts[i]).html();
			disp += "<span>" + toCopy + "</span>";
		}
		disp += "<span><button class='pure-button added_field'><i class='fa fa-minus'></i></button></span>";
		disp += "</div>";
		//display the copied field
		$("#div_" + target).after(disp);
	});
}

function remove_newField() {
	$(document).on("click", "button[class*='added_field']", function(event) {
		//avoid to reload the page
		event.preventDefault();
		//remove the field
		$(this).parent().parent().remove();
	});
}

function verify_required() {
	//if a field is not filled and it has to be it, it is colored in red
	var ok = true;
	var req = $("input,select,textarea,datalist");
	for (var i = 0; i < req.length; i++) {
		var r = $( req[i] );
		var datalist = $("input[list='" + $(r).attr('id') + "']");
		if($(r).parent().attr("class")) { //don't verify the added fields
			if ($(r).attr("required") === "required" && $(datalist).length === 0 && !($(r).val() === "" || $(r).val() === "none")) {
				$(r).css("background-color", "white");
			} else if ($(r).attr("required") === "required" && $(datalist).length > 0 && !($(datalist).val() === "" || $(datalist).val() === "none")){
				//it is a datalist
				$(datalist).css("background-color", "white");
			} else if ($(r).attr("required") === "required" && $(datalist).length === 0) {
				ok = false;
				$(r).css("background-color", "#FFBABA");
			} else if ($(r).attr("required") === "required" && $(datalist).length > 0 && $(datalist).val() === "") { 
				//it is a datalist
				if ($(r).parent().attr("class")) { //to avoid to color an added field
					ok = false;
					$(r).parent().children("input[list]").css("background-color", "#FFBABA");
				}
			}
		} else if ($(r).attr("required") === "required") {
			$(r).css("background-color", "white");
			if ($(r).parent().attr("class")) //to avoid to color an added field
				$(r).parent().children("input[list]").css("background-color", "white");
		}
	}
	return ok;
}

function serialization() {
	var forms = $( 'form' );
	var req = "";
	for(var i = 0; i < forms.length; i++) {
		req += $( forms[i] ).serialize();
		//get back values from datalist
		var child = $(forms[i]).find("input[list]");
		for (var j = 0; j < child.length; j++) {
			req += "&" + $(child[j]).attr("list") + "=" + encodeURIComponent($(child[j]).val());
		};
		//end
		if (i !== forms.length - 1) req += ";";
	}
	return req;
}

function decodeURL(str) {
	var elem = document.createElement('textarea');
	elem.innerHTML = str;
	var decoded = elem.value;
	return decoded;
}

function padNumbers(string, length) {
	//http://blog.rodneyrehm.de/archives/14-Sorting-Were-Doing-It-Wrong.html
	/*to use with :
		list.sort(function(a, b) {
		    var an = padNumbers(a.toLowerCase()),
		        bn = padNumbers(b.toLowerCase());

		    return an > bn ? 1 : -1;
		});
	*/
    if (!length) {
        length = 10;
    }
    
    return string.replace(/(\d+(\S\d+)*)/g, function(m) {
        var delimiter, decimalLength;
        //console.log('------------------------------');
        //console.log(m);
        // preserve decimal delimiter
        m = m.replace(/(\D)(\d+)$/, function(m, delim, num) {
            delimiter = delim;
            decimalLength = num.length;
            return '###' + num;
        });
        
        // if decimal delimiter occurs multiple times
        // it is not a decimal delimiter, but thousand delimiter
        if (delimiter && m.indexOf(delimiter) > -1) {
            m = m.replace('###', '');
        } else {
            // TODO: decide what to do with decimals
            // that are 3-accurate - might be german thousand-delim
        }

        // remove thousand delimiters
        m = m.replace(/[^0-9#]/g, '');
        
        // left-padd integer
        m = m.replace(/^\d+/, function(m) {
            while (m.length < length) {
                m = "0" + m;
            }
            
            return m;
        });
        
        // add decimal component
        if (m.indexOf('#') < 0) {
            m += "###0";
        }
        
        // right-padd decimal
        m = m.replace(/#(\d+)$/, function(tmp, m) {
            while (m.length < length) {
                m += "0";
            }
            
            return m;
        });
        
        //console.log(m);
        return m;
    });
}

$( function() {
	//ADD/REMOVE A NEW FIELD
	add_newField();
	remove_newField();
	menu()
});