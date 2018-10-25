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
	var dp = document.getElementById("pic_path").value;
	var legal = true;

	if (dp.length > 0){
		var splitted_file = dp.split(".");
		var legal_file = ["jpg","jpeg","png","gif"];

		if ((legal_file.indexOf((splitted_file[splitted_file.length-1]).toLowerCase())) == -1){
			legal = false;
			document.getElementById("pic_error").innerHTML = "*Hanya .jpg, .png, dan .gif yang diperbolehkan.";
		}
	}
	if (name.length == 0){
		document.getElementById("name_error").innerHTML = "*field harus diisi.";
		legal = false;
	} else if (name.length > 20){
		document.getElementById("name_error").innerHTML = "*panjang nama maksimal adalah 20.";
		legal = false;
	}

	if (addr.length == 0){
		document.getElementById("addr_error").innerHTML = "*field harus diisi.";
		legal = false;
	}

	if (phone.length == 0){
		document.getElementById("phone_error").innerHTML = "*field harus diisi.";
		legal = false;
	} else if ((phone.length < 9) || (phone.length > 12)){
		document.getElementById("phone_error").innerHTML = "*panjang no. telepon harus diantara 9 dan 12.";
		legal = false;
	}

	var phone_test = 999999999999 - phone;
	if (isNaN(phone_test)){
		document.getElementById("phone_error").innerHTML = "*no. telepon harus berupa angka.";
		legal = false;
	}

	if (legal){
		document.edit_form.submit();
	}
};