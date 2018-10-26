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
		mysqli_close($conn);
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
				$conn = mysqli_connect($dbserver,$dbuser,$dbpass);

				if(mysqli_connect_error()) {
				   	die('Could not connect: ' . mysqli_connect_error());
				}
				mysqli_select_db($conn, "wbd_schema");
				$data = mysqli_query($conn,"SELECT name,email,address,phone_num,display_pic FROM user WHERE username = \"".$uname."\"");
				$data_1 = mysqli_fetch_assoc($data);
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
		<div class="main_info">
			<div>
				<div class="prof_edit">
					<a href="edit_profile.php"><img src="./icon/edit.png" id="pedit"></a>
				</div>
				<div class="prof_pic">
					<img src=<?php echo $data_1["display_pic"] ?> id="pp">
				</div>
				<div class="prof_name">
					<p><?php echo $data_1["name"] ?></p>
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
							<th class="info_data"><?php echo $data_1["email"] ?></th>
						<tr>
							<th class="info_pic">
								<img src="./icon/address.png" id="pic">
							</th>
							<th class="info_type">Address</th>
							<th class="info_data"><?php echo $data_1["address"] ?></th>
						</tr>
						<tr>
							<th class="info_pic">
								<img src="./icon/telephone.png" id="pic">
							</th>
							<th class="info_type">Phone Number</th>
							<th class="info_data"><?php echo $data_1["phone_num"] ?></th>
						</tr>
						<tr>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<?php 
			mysqli_free_result($data);
			mysqli_close($conn);
		?>
	</body>
</html>
