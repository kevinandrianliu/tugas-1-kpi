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
		<title>Pro Book - Search Book</title>
		<link rel="stylesheet" type="text/css" href="./searchbook.css">
		<link rel="stylesheet" type="text/css" href="./base.css">
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
					<a href="logout.php">
						<img src="./icon/io.png" id="logout_pic">
					</a>
				</div>
			</div>
			<div class="menus">
				<!-- Warna page yang sedang dipilih = #F26600-->
				<div class="menu" id="browse" onclick="location.href='searchbook.html'">
					<p>Browse</p>
				</div>
				<div class="menu" id="history" onclick="location.href='history.php'">
					<p>History</p>
				</div>
				<div class="menu" id="profile" onclick="location.href='edit_profile.php'">
					<p>Profile</p>
				</div>
			</div>
		</div>
		<div>
			<div class = "search">
			<h1>Search Result</h1>
				<div class = "result">
					<?php
						$servername = "localhost";
						$username = "root";
						# $password = "password";

						$conn = new mysqli($servername, $username, "", "wbd_schema");

						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						$titlequer = "%" . $_GET["judul"] . "%";
						
						$query = "SELECT id, title, author, description, book_pic FROM book WHERE title LIKE '$titlequer'";
						$result = $conn->query($query);

						if ($result) {
						echo "<p>Found " . $result->num_rows . " results.</p>";
							while($row = $result->fetch_assoc()) {
								echo "<div class = 'persection'>";
								echo "<div class='pic' >";
								echo '<img src="' . $row["book_pic"] . '" height="200px" max-width="200px" />';
								echo "</div>";
								echo "<h3>" . $row["title"] . "</h3>";
								echo "<h4>" . $row["author"] . "</h4>";
								echo "<p>" . $row["description"] . "</p>";
								echo "<button onclick='location.href=\"bookdetail.php?id=" . $row["id"] . "\"'>Detail</button>";
								echo "</div>";
							}
						}
						
						$conn->close();
					?>
				</div>
			</div>
		</div>
	</body>
</html>
