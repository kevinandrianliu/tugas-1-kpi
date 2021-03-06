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
			$params = session_get_cookie_params();

			setcookie("access_token","",0, $params["path"], $params["domain"], TRUE, TRUE);
			setcookie("uname","",0, $params["path"], $params["domain"], TRUE, TRUE);
			
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
		<script src="inputtransaction.js"></script>
	</head>
	<body>
		<div id="dialogbox">
			<div id="dialogclose"></div>
			<img src="./icon/check.png"/>
			<div id="dialogcontent"></div>
		</div>
		<div id="dialogoverlay"></div>
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
				<div class="menu" id="browse" onclick="location.href='searchbook.php'">
					<p>Browse</p>
				</div>
				<div class="menu" id="history" onclick="location.href='history.php'">
					<p>History</p>
				</div>
				<div class="menu" id="profile" onclick="location.href='profile.php'">
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

						$conn = new mysqli($servername, $username, "", "wbd_schema");
						if ($conn->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}
						$id = $_GET["id"];
						$stmt = $conn->prepare('SELECT * FROM book WHERE id=?');
						$stmt->bind_param('i', $id);
						$stmt->execute();

						$result = $stmt->get_result();

						if ($result) {
							$row = $result->fetch_assoc();
							echo "<div class = 'side' >";
							echo "<div class = 'pic2'>";
							echo '<img src="' . $row["book_pic"]. '"/>';
							echo "</div>";

							echo "<div class='rating'>";
							
							$stmt2 = $conn->prepare('SELECT AVG(rating) FROM transaction WHERE book_id= ? AND rating > 0');
							$stmt2->bind_param('i', $id);
							$stmt2->execute();
							$result = $stmt2->get_result();

							$ratrow = $result->fetch_assoc();
							$star = (int) $ratrow["AVG(rating)"];
							
							$x = 1;
							while ($x <= $star) {
								echo '<img class="rate" src="./icon/fullstaricon.png"/>';
								$x += 1;
							}
							while ($x <= 5) {
								echo '<img class="rate" src="./icon/nullstaricon.png"/>';
								$x += 1;
							}
							echo "</div>";
							$rat = $ratrow["AVG(rating)"];
							if (is_null($rat)) {
								$rat = 0;
							}
							echo "<div>";
							echo number_format($rat, 1, '.', '');
							echo " / 5.0 </div>";
							echo "</div>";
							echo "<h1>" . $row["title"] . "</h1>";
							echo "<h4>" . $row["author"] . "</h4>";
							echo "<p>" . $row["description"] . "</p>";
						}

						$conn->close();
					?>
				</div>
				<div class = "order">
					<h2>Order</h2>
						Order:
						<select id="quantity">
							<option value="1" selected>1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
						</select>
						<button onclick="inputorder('<?php echo $uname ?>', '<?php echo htmlentities( $_GET['id'], ENT_QUOTES, 'UTF-8')?>')">Order</button>
				</div>
				<h2>Review</h2>
				<?php
					$servername = "localhost";
					$username = "root";
					# $password = "password";

					$conn = new mysqli($servername, $username, "", "wbd_schema");
					if ($conn->connect_error) {
						die("Connection failed: " . $conn->connect_error);
					}

					$id = $_GET["id"];
					$stmt3 = $conn->prepare('SELECT DISTINCT transaction.book_id, transaction.username, user.display_pic, transaction.rating, transaction.review FROM transaction INNER JOIN user ON (transaction.username = user.username)  WHERE book_id=?');
					$stmt3->bind_param('i', $id);
					$stmt3->execute();
					$result = $stmt3->get_result();


					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							if (is_null($row["review"])) {

							} else {
								echo '<div class = "review">';
								echo '<img id="pp" src="' . $row["display_pic"]. '">';
								echo '<div class="ratereview">';
								echo '<img id="star" src="./icon/fullstar.png">';
								echo '<p>' . number_format($row["rating"], 1, '.', '') . '/5.0</p>';
								echo '</div>';
								echo '<h4>' . $row["username"] . '</h4>';
								echo '<p>' . $row["review"] . '</p>';
								echo '</div>';
							}
						}
					}
					$conn->close();
				?>
			</div>
		</div>
	</body>
</html>
