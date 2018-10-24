<?php
	//check if cookie is set and a user is already in
	if (!(isset($_COOKIE["username"])) || $_COOKIE["username"] == ""){
		header("Location: index.php");	//if not, redirect to login page
	} else {
		$uname = $_COOKIE["username"];	//else, retrieve the cookie
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
				$dbserver = '127.0.0.1';
				$dbuser = 'root';
				$dbpass = '';
				$conn = mysqli_connect($dbserver,$dbuser,$dbpass);

				if(mysqli_connect_error()) {
				   	die('Could not connect: ' . mysqli_connect_error());
				}
				mysqli_select_db($conn, "wbd_user_schema");
				if ($stmt = mysqli_prepare($conn,"SELECT name,email,address,phone_num,display_pic FROM user WHERE username = \"".$uname."\"")) {
					mysqli_stmt_execute($stmt);

					mysqli_stmt_bind_result($stmt,$name,$email,$addr,$phone,$dp);

					mysqli_stmt_fetch($stmt);
				}
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
					<img src="./icon/io.png" id="logout_pic">
				</div>
			</div>
			<div class="menus">
				<!-- Warna page yang sedang dipilih = #F26600-->
				<div class="menu" id="browse">
					<p>Browse</p>
				</div>
				<div class="menu" id="history">
					<p>History</p>
				</div>
				<div class="menu" id="profile">
					<p>Profile</p>
				</div>
			</div>
		</div>
		<div class="edit_profile">
			<p>Edit Profile</p>
			<form action="edit_profile_submit.php" name="edit_form" method="POST" enctype="multipart/form-data">
				<table>
					<tr class="pp_tr">
						<td>
							<div class="pp_container">
								<img src=<?php echo "\"".$dp."\""; ?> id="pp">
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
							<input type="text" name="name" id="name" value=<?php echo "\"$name\""; ?>>
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
							<textarea name="address" id="address"><?php echo $addr; ?></textarea>
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
							<input type="text" name="phone" id="phone" value=<?php echo $phone; ?>>
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
	</body>
</html>
