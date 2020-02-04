<?php
	//check if the cookie has expired or a user is logged in
	if (!(isset($_COOKIE["access_token"]))){
		header("Location: index.php");
	} else {
		$ac_token = $_COOKIE["access_token"];

		$dbserver = '127.0.0.1';
		$dbuser = 'root';
		$dbpass = '';
		$conn = mysqli_connect($dbserver,$dbuser,$dbpass);

		mysqli_select_db($conn,"wbd_schema");
		$data = mysqli_query($conn,"SELECT * FROM access_token WHERE token_id=\"$ac_token\"");
		$data_1 = mysqli_fetch_assoc($data);

		if (($data_1["token_id"] !== $ac_token) || ($data_1["expiry_time"] < date('Y-m-d H:i:s',time()))){
			setcookie("access_token","",0);
			setcookie("uname","",0);
			
			header("Location: index.php");
		} else {
			$uname = $data_1["username"];
		}

		mysqli_free_result($data);
	}
	if ((isset($_POST["name"])) && (isset($_POST["address"])) && (isset($_POST["phone"]))){	//check if the user accessed the page right, via form submit
		$name = $_POST["name"];
		$addr = $_POST["address"];
		$phone = $_POST["phone"];

		if ($_FILES["pic_path"]["name"] !== ""){
			$file_tmpname = $_FILES["pic_path"]["tmp_name"];
			$file_name = $_FILES["pic_path"]["name"];
			$file_dir = getcwd()."\\pp\\".$file_name;

			move_uploaded_file($file_tmpname,$file_dir);

			mysqli_select_db($conn,"wbd_schema");

			$query = "UPDATE user SET name=?,address=?,phone_num=?,display_pic=? WHERE username=?";
			$filename = "./pp/".$file_name;
			$stmt = $conn->prepare($query);
			$stmt->bind_param('sssss',$name, $addr, $phone, $filename, $uname);
		} else {
			$query = "UPDATE user SET name=?,address=?,phone_num=? WHERE username=?";
			$stmt = $conn->prepare($query);
			$stmt->bind_param('ssss',$name, $addr, $phone, $uname);
		}
		$stmt->execute();
		mysqli_close($conn);

		header("Location: profile.php");
	} else {
		header("HTTP/1.1 403 Forbidden");
	}
?>