<?php
	//check if cookie is set and a user is already in
	if (!(isset($_COOKIE["username"])) || $_COOKIE["username"] == ""){
		header("Location: login.php");	//if not, redirect to login page
	} else {
		$uname = $_COOKIE["username"];	//else, retrieve the cookie
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Pro Book - Search Book</title>
		<link rel="stylesheet" type="text/css" href="./searchbook.css">
	</head>
	<body>
		<div>
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
					<div class="menu" id="browse" onclick="location.href='searchbook.html'">
						<p>Browse</p>
					</div>
					<div class="menu" id="history">
						<p>History</p>
					</div>
					<div class="menu" id="profile" onclick="location.href='edit_profile.html'">
						<p>Profile</p>
					</div>
				</div>
			</div>
		<div>
			<div class = "search">
				<div id = "searchbar">
					<h1>Search book</h1>
					<form action="searchresult.php" method="get">
						<input id="forminputter" type="text" name="judul" required>
						<button>Search</button>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
