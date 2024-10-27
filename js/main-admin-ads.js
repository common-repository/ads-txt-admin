function addRow() {
	var id = getLastID() + 1;

	var rowClone = jQuery('.table-fields-row:last').clone();
	rowClone.css("display", "none");
	jQuery('input.um-add-btn').before(rowClone);

	var lastRowElems = jQuery('.table-fields-row:last').children().children().filter(".form-control");
	var attrName = ["rows[" + id + "][domain]", "rows[" + id + "][publisher_id]", "rows[" + id + "][type]", "rows[" + id + "][authority_id]"];

	for (var i = 0; i < lastRowElems.length; i++) {
		jQuery(lastRowElems[i]).attr("name", attrName[i]);
		if (i !== 2) {
			jQuery(lastRowElems[i]).val('');
		}
		if (jQuery(lastRowElems[i]).val() === 0) {
			jQuery(lastRowElems[i]).css("border", "1px solid red");
		}
	}

	if (jQuery(rowClone).css("display").length != 0) {
		jQuery(rowClone).fadeIn();
	}
	clearValidation();
}

function validateFields() {
	var allFieldsArray = jQuery('.table-fields-row').children().children().filter(".form-control");

	var statusChecker = {
		submit: "false",
		domain: "false",
		fields: "false"
	};

	function verifyFields(i) {
		if (jQuery(allFieldsArray[i]).val().length === 0) {
			statusChecker.submit = false;
			jQuery(allFieldsArray[i]).css("border", "1px solid rgba(255,0,0,0.7");
			statusChecker.fields = false;
		} else {
			statusChecker.fields = true;
			if (i % 4 === 0) {
				if (jQuery(allFieldsArray[i]).val().length !== 0) {
					if (isValidDomain(jQuery(allFieldsArray[i]).val()) !== true) {
						statusChecker.domain = false;
						jQuery(allFieldsArray[i]).css({
							"border": "1px solid rgba(255,0,0,0.7)",
							"background-color": "rgba(255,0,0,0.1)",
							"color": "#a94442",
							"font-weight": "bold"
						});
					} else {
						statusChecker.domain = true;
					}
				}
				else {
					statusChecker.domain = false;
				}

			}
		}
	}

	for (var i = 0; i < allFieldsArray.length; i++) {
		if ((i % 4 !== 3) && (i % 4 !== 2)) {
			verifyFields(i);
		}

	}

	if (statusChecker.domain === true && statusChecker.fields === true) {
		statusChecker.submit = true;
	}

	if (statusChecker.submit === true) {
		jQuery('.form-rows-wrapper').submit();
	} else {
		if (statusChecker.domain === false) {
			showError("domain", false);
		} else {
			showError("domain", true);
		}

		if (statusChecker.fields === false) {
			showError("field", false);
		} else {
			showError("field", true);
		}
	}
}

function showError(type, status) {
	if (type === "domain" && status === false) {
		jQuery('.err-domain').fadeIn();
	} else if (type === "domain" && status === true) {
		jQuery('.err-domain').fadeOut();
	}

	if (type === "field" && status === false) {
		jQuery('.err-fields').fadeIn();
	} else if (type === "field" && status === true) {
		jQuery('.err-fields').fadeOut();
	}

}

function isValidDomain(v) {
	if (!v) return false;
	var re = /^(?!:\/\/)[a-zA-Z0-9-][a-zA-Z0-9-]+\.[a-zA-Z]{2,64}?$/gi;
	return re.test(v);
}

function clearValidation() {
	jQuery(".form-control").css({
		'background-color': 'white',
		'border': '1px solid #ddd'
	});
	jQuery(".form-control").on('click', function () {
		jQuery(this).css({
			'background-color': 'white',
			'border': '1px solid #ddd'
		})
	});
}

function removeRow(el) {
	if (jQuery('.table-fields-row').length > 1) {
		jQuery(el).parent().parent().remove();
		if (!jQuery('.form-rows-wrapper').find(".um-add-btn").length) {
			addPlusBtn();
		}
	}
}

function getLastID() {
	var nextId = 0;
	var lastChildEl = jQuery('.table-fields-row:last'),
		inputs = lastChildEl.find('.col-sm-2 input');

	if (inputs.length) {
		var nextId = parseInt(inputs.attr('name').match(/\d/g).join(""));
	}

	return nextId;
}
