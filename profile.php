<?php
	if (!(isset($_COOKIE["username"])) || $_COOKIE["username"] == ""){
		header("Location: index.php");
	} else {
		$uname = $_COOKIE["username"];
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Profile - <?php echo $uname?></title>
		<link rel="stylesheet" type="text/css" href="./base.css">
		<link rel="stylesheet" type="text/css" href="./profile.css">
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
					<p><u>Hi, <?php echo $uname ?></u></p>
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
		<div class="main_info">
			<div>
				<div class="prof_edit">
					<a href="edit_profile.php"><img src="./icon/edit.png" id="pedit"></a>
				</div>
				<div class="prof_pic">
					<img src=<?php echo $dp ?> id="pp">
				</div>
				<div class="prof_name">
					<p><?php echo $name ?></p>
				</div>
			</div>
			<div class="profile_info">
				<p>My Profile</p>
				<div>
					<table>
						<tr>
							<th class="info_pic">
								<img src="./icon/username.png" id="pic">
							</th>
							<th class="info_type">Username</th>
							<th class="info_data"><?php echo $uname ?></th>
						</tr>
							<th class="info_pic">
								<img src="./icon/mail.png" id="pic">
							</th>
							<th class="info_type">Email</th>
							<th class="info_data"><?php echo $email ?></th>
						<tr>
							<th class="info_pic">
								<img src="./icon/address.png" id="pic">
							</th>
							<th class="info_type">Address</th>
							<th class="info_data"><?php echo $addr ?></th>
						</tr>
						<tr>
							<th class="info_pic">
								<img src="./icon/telephone.png" id="pic">
							</th>
							<th class="info_type">Phone Number</th>
							<th class="info_data"><?php echo $phone ?></th>
						</tr>
						<tr>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<?php mysqli_close($conn)?>
	</body>
</html>
