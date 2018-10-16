/**
 * 用于批量选择功能
 */
function check_one(el) {
	if (!el.checked)
		document.all("allbox").checked = false;
}

function check_all() {
	var box = document.getElementsByName('run_select[]');
	for (i = 0; i < box.length; i++) {
		if (document.getElementById('allbox_for').checked == true) {
			box[i].checked = true;
		} else {
			box[i].checked = false;
		}
	}

	return true;
}

function get_checked() {
	checked_str = "";
	var box = document.getElementsByName('run_select[]');
	for ( var i = 0; i < box.length; i++) {
		if (box[i].checked) {
			checked_str += box[i].value + ',';
		}
	}
	return checked_str;
}
