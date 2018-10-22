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
					<p><u>Hi, tayotayo</u></p>
				</div>
				<div class="info" id="logout">
					<img src="./icon/io.png" width="40px" height="45px">
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
		<div>
			<div class = "search">
			<h1>Search Result</h1>
				<div class = "result">
					<?php
						$servername = "localhost";
						$username = "root";
						# $password = "password";

						$conn = new mysqli($servername, $username, "", "wbd01");

						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						$titlequer = "%" . $_GET["judul"] . "%";
						
						$query = "SELECT title, author, descrip, picture FROM book WHERE title LIKE '$titlequer'";
						$result = $conn->query($query);

						if ($result) {
							while($row = $result->fetch_assoc()) {
								echo "<div class = 'persection'>";
								echo "<h3>" . $row["title"] . "</h3>";
								echo "<h4>" . $row["author"] . "</h4>";
								echo "<p>" . $row["descrip"] . "</p>";
								echo "</div>";
							}
						}
						
						$conn->close();
					?>
					<div class = "persection">
						<h3>Baaaa</h3>
						<h4>NALSKSJ</h4>
						<p>asdhjhjkfhkjshklfjh sldflkjalkfjlkdsjklf dhslfhj</p>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
