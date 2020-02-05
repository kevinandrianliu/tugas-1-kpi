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
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>History - <?php echo $uname?></title>
		<link rel="stylesheet" type="text/css" href="./base.css">
		<link rel="stylesheet" type="text/css" href="./history.css">
	</head>
	<body>
		<div>
			<div class="header">
				<div class="info" id="store-name">
					<p>Pro</p>
					- Book
				</div>
				<div class="info" id="user">
					<p><u>Hi,  <?php echo $uname ?></u></p>
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
		<div class="history">
			<p>History</p>
			<?php
				function convertDate($date){
					$month = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
					$splitted_date = explode("-", $date);

					return $splitted_date[2]." ".$month[$splitted_date[1]-1]." ".$splitted_date[0];
				}

				mysqli_select_db($conn, "wbd_schema");
				$data = mysqli_query($conn,"SELECT title,book_pic,order_id,amount,date_bought,rating FROM book INNER JOIN transaction ON book.id = transaction.book_id WHERE username = '$uname'");

				$data_1 = mysqli_fetch_assoc($data);
				while (!(is_null($data_1))){
					echo "<div  class=\"bookhistory\">";
					echo "<div class=\"book\" id=\"book_pic\">";
					echo "<img src=\"".$data_1["book_pic"]."\" id=\"pic\">";
					echo "</div>";
					echo "<div class=\"book\" id=\"book_desc\">";
					echo "<h2>".$data_1["title"]."</h2>";
					echo "<p>Jumlah : ".$data_1["amount"]."</p>";
					if ($data_1["rating"] > 0){
						echo "<p>Anda sudah memberikan review</p>";
					} else {
						echo "<p>Belum direview</p>";
					}
					echo "</div>";
					echo "<div class=\"book\" id=\"book_desc_2\">";
					echo "<p>".convertDate($data_1["date_bought"])."</p>";
					echo "<p>Nomor Order : #".$data_1["order_id"]."</p>";
					echo "<form action=\"./review.php\" method=\"GET\">";
					echo "<input type=\"hidden\" name=\"order_id\" value=\"".$data_1["order_id"]."\"/>";
					if (!($data_1["rating"] > 0)){
						echo "<input type=\"submit\" value=\"Review\" id=\"button_review\">";
					}
					echo "</form>";
					echo "</div>";
					echo "</div>";
					$data_1 = mysqli_fetch_assoc($data);
				}

				mysqli_close($conn);
			?>
		</div>
	</body>
</html>
