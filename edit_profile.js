function browse_file(){
	document.getElementById("file-dir").click();
};

function pass_file_dir(){
	var file_directory = document.getElementById("file-dir").value;
	var dir = file_directory.split("\\");
	document.getElementById("pic_path").value = dir[dir.length-1];
};

function validate(){
	var name = document.getElementById("name").value;
	var addr = document.getElementById("address").value;
	var phone = document.getElementById("phone").value;
	var form = document.getElementById("save_butt");

	console.log(name);
	console.log(addr);
	console.log(phone);
	console.log(name.length);
	console.log(addr.length);
	console.log(phone.length);

	var prevDef = function(e){
		e.preventDefault();
	};

	if ((name.length == 0) || (addr.length == 0) || (phone.length == 0)){
		alert("NO");
	} else {
		document.edit_form.submit();
	}
};