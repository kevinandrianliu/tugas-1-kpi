<?php
	//check if cookie is set and a user is already in
	if (!(isset($_COOKIE["access_token"]))){
		header("Location: login.php");
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
			
			header("Location: login.php");
		} else {
			$uname = $data_1["username"];
		}

		mysqli_free_result($data);
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Edit Profile - <?php echo $uname?></title>
		<link rel="stylesheet" type="text/css" href="./base.css">
		<link rel="stylesheet" type="text/css" href="./edit_profile.css">
		<script src="./edit_profile.js"></script>
	</head>
	<body>
		<div>
			<?php
				$data = mysqli_query($conn,"SELECT name,email,address,phone_num,display_pic FROM user WHERE username = \"".$uname."\"");
				$data_1 = mysqli_fetch_assoc($data);
			?>
			<div class="header">
				<div class="info" id="store-name">
					<p>Pro</p>
					- Book
				</div>
				<div class="info" id="user">
					<p><u>Hi, <?php echo $uname; ?></u></p>
				</div>
				<div class="info" id="logout">
					<a href="logout.php">
						<img src="./icon/io.png" id="logout_pic">
					</a>
				</div>
			</div>
			<div class="menus">
				<!-- Warna page yang sedang dipilih = #F26600-->
				<a href="./searchbook.php">
					<div class="menu" id="browse">
						<p>Browse</p>
					</div>
				</a>
				<a href="./history.php">
					<div class="menu" id="history">
						<p>History</p>
					</div>
				</a>
				<a href="./profile.php">
					<div class="menu" id="profile">
						<p>Profile</p>
					</div>
				</a>
			</div>
		</div>
		<div class="edit_profile">
			<p>Edit Profile</p>
			<form action="edit_profile_submit.php" name="edit_form" method="POST" enctype="multipart/form-data">
				<table>
					<tr class="pp_tr">
						<td>
							<div class="pp_container">
								<img src=<?php echo $data_1["display_pic"] ?> id="pp">
							</div>
						</td>
						<td class="pp_td">
							<p>Update profile picture</p>
							<input type="text" id="pic_path">
							<input type="button" onclick="browse_file()" class="buttons" id="browse_butt" value="Browse ...">
							<div class="errors" id="pic_error"></div>
							<input type="file" name="pic_path" id="file-dir" oninput="pass_file_dir()" hidden>
						</td>
					</tr>
					<tr>
						<td>
							Name
						</td>
						<td>
							<input type="text" name="name" id="name" value=<?php echo "\"".$data_1["name"]."\""; ?>>
						</td>
						<td>
							<div class="errors" id="name_error"></div>
						</td>
					</tr>
					<tr>
						<td class="add_text">
							Address
						</td>
						<td>
							<textarea name="address" id="address"><?php echo $data_1["address"]; ?></textarea>
						</td>
						<td>
							<div class="errors" id="addr_error"></div>
						</td>
					</tr>
					<tr>
						<td>
							Phone Number
						</td>
						<td>
							<input type="text" name="phone" id="phone" value=<?php echo $data_1["phone_num"]; ?>>
						</td>
						<td>
							<div class="errors" id="phone_error"></div>
						</td>
					</tr>
					<tr>
						<td class="td_back_butt">
							<a href="profile.php"><input type="button" class="buttons" id="back_butt" value="Back"></a>
						</td>
						<td class="td_save_butt">
							<input type="button" onclick="validate()" class="buttons" id="save_butt" name="btnsubmit" value="Save">
						</td>
					</tr>
				</table>
			</form>
			<?php
				mysqli_free_result($data);
				mysqli_close($conn);
			?>
	</body>
</html>
