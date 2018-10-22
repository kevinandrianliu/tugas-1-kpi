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
				<div class = "inside">
					<?php
						$servername = "localhost";
						$username = "root";
						# $password = "password";

						$conn = new mysqli($servername, $username, "", "wbd01");
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}

						$id = $_GET["id"];
						$query = "SELECT * FROM book WHERE id=$id";

						$result = $conn->query($query);

						if ($result) {
							$row = $result->fetch_assoc();
							echo "<div class = 'side' >";
							echo "<div class = 'pic2'>";
							echo '<img src="data:image/jpeg;base64,' . base64_encode($row["picture"]) . '" height="235px" max-width="235px" />';
							echo "</div>";

							echo "<div class='rating'>";

							$star = (int) $row["rating"];
							$x = 1;
							while ($x <= $star) {
								echo '<img src="./icon/fullstaricon.png" width="30px" height="29px"/>';
								$x += 1;
							}
							while ($x <= 5) {
								echo '<img src="./icon/nullstaricon.png" width="30px" height="29px"/>';
								$x += 1;
							}
							echo "</div>";
							echo "</div>";
							echo "<h1>" . $row["title"] . "</h1>";
							echo "<h4>" . $row["author"] . "</h4>";
							echo "<p>" . $row["descrip"] . "</p>";
						}

						$conn->close();
					?>
				</div>
				<div class = "order">

				</div>
			</div>
		</div>
	</body>
</html>
