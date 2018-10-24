<?php
	//check if the cookie has expired or a user is logged in
	if (!(isset($_COOKIE["username"])) || $_COOKIE["username"] == ""){
		header("Location: index.php");
	}
	if ((isset($_POST["name"])) && (isset($_POST["address"])) && (isset($_POST["name"]))){	//check if the user accessed the page right, via form submit
		$uname = $_COOKIE["username"];
		$name = $_POST["name"];
		$addr = $_POST["address"];
		$phone = $_POST["phone"];

		$dbserver = '127.0.0.1';
		$dbuser = 'root';
		$dbpass = '';
		$conn = mysqli_connect($dbserver,$dbuser,$dbpass);

		if ($_FILES["pic_path"]["name"] !== ""){
			$file_tmpname = $_FILES["pic_path"]["tmp_name"];
			$file_name = $_FILES["pic_path"]["name"];
			$file_dir = getcwd()."\\pp\\".$file_name;

			move_uploaded_file($file_tmpname,$file_dir);

			if(mysqli_connect_error()) {
			   	die('Could not connect: ' . mysqli_connect_error());
			}

			mysqli_select_db($conn,"wbd_user_schema");

			if ($stmt = mysqli_prepare($conn,"SELECT display_pic FROM user WHERE username = \"".$uname."\"")){
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt,$old_pic);
				mysqli_stmt_fetch($stmt);

				if ($old_pic != "./icon/pp_default.jpg"){
					unlink($old_pic);
				}
			}

			mysqli_close($conn);
		}

		$conn = mysqli_connect($dbserver,$dbuser,$dbpass);

		if(mysqli_connect_error()) {
		   	die('Could not connect: ' . mysqli_connect_error());
		}

		mysqli_select_db($conn,"wbd_user_schema");
		if ($_FILES["pic_path"]["name"] !== ""){
			$query = "UPDATE user SET name='$name',address='$addr',phone_num='$phone',display_pic='./pp/$file_name' WHERE username='$uname'";
		} else {
			$query = "UPDATE user SET name='$name',address='$addr',phone_num='$phone' WHERE username='$uname'";
		}

		if (!(mysqli_query($conn,$query))){
			echo mysqli_error($conn);
		}
		mysqli_close($conn);

		header("Location: profile.php");
	} else {
		header("HTTP/1.1 403 Forbidden");
	}
?>