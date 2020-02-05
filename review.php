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
		<title>Profile - <?php echo $uname?></title>
		<link rel="stylesheet" type="text/css" href="./base.css">
		<link rel="stylesheet" type="text/css" href="./review.css">
		<script src="./review.js"></script>
		<script src="./rating.js"></script>
	</head>
	<body>	
		<?php
			$dbserver = '127.0.0.1';
			$dbuser = 'root';
			$dbpass = '';
			$conn = mysqli_connect($dbserver,$dbuser,$dbpass);

			if(mysqli_connect_error()) {
				die('Could not connect: ' . mysqli_connect_error());
			}
			mysqli_select_db($conn, "wbd_schema");
			if ($_SERVER["REQUEST_METHOD"] == "POST"){
				$rating = $_POST["form_rating"];
				$review = $_POST["form_review"];
				$order_id = $_POST["fetch_order_id"];
				mysqli_select_db($conn, "wbd_schema");
				$query = "UPDATE transaction SET review = '$review', rating = '$rating' WHERE username='$uname' and order_id = '$order_id'";
				$stmt = $conn->prepare($query);
				$stmt->bind_param('sisi',$review, $rating, $uname, $order_id);
				if (!$stmt->execute()){
					echo mysqli_error($conn);
				}
				mysqli_close($conn);
				header("Location: history.php");
			}
			else{
				$order_id = $_GET["order_id"];
				$query = "SELECT title, author, book_pic FROM transaction, book WHERE transaction.book_id = book.id and order_id = '$order_id'";
				if ($stmt = mysqli_prepare($conn,$query)) {
					mysqli_stmt_execute($stmt);

					mysqli_stmt_bind_result($stmt,$regrevtitle,$regrevauthor,$regrevbook_pic);

					while (mysqli_stmt_fetch($stmt)){
						$title = $regrevtitle;
						$author = $regrevauthor;
						$book_pic = $regrevbook_pic;
						$ratingerror= $reviewerror = "";
					}
				} else {
					echo mysqli_error($conn);
				}
			}
			mysqli_close($conn);
		?>
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
				<div class="menu" id="profile" onclick="location.href='edit_profile.php'">
					<p>Profile</p>
				</div>
			</div>
		</div>
		<div class="detail">
            <form action = "review.php" name="edit_form" method = "post">
                <div class="bookdetail">
                    <img src='<?php echo "$book_pic";?>' id = "book">
                    <h1><?php echo "$title";?></h1>
                    <h4><?php echo "$author";?></h4>
                </div>
                <div class="rating">
					<h2>Add Rating </h2>
					<div class="ratestar">
						<label><input name="form_rating" type="radio" value="1" /><img src='./icon/nullstar.png' class="star" id = "1" onclick="check(1)"></label>
						<label><input name="form_rating" type="radio" value="2" /><img src='./icon/nullstar.png' class="star" id = "2" onclick="check(2)"></label>
						<label><input name="form_rating" type="radio" value="3" /><img src='./icon/nullstar.png' class="star" id = "3" onclick="check(3)"></label>
						<label><input name="form_rating" type="radio" value="4" /><img src='./icon/nullstar.png' class="star" id = "4" onclick="check(4)"></label>
						<label><input name="form_rating" type="radio" value="5" /><img src='./icon/nullstar.png' class="star" id = "5" onclick="check(5)"></label>
					</div>	
                    <span class = "error" id = "rating_error"> </span>
                </div>
                <div class="comment">
                    <h2>Add Comment </h2><br>
                    <textarea name = "form_review" id = "form_review"></textarea>
                    <span class = "error" id= "review_error"></span>
                    <input type = "hidden" name = "fetch_order_id" id = "fetch_order_id" value = <?php echo $order_id;?>></input>
                    <div class="button">
                        <input type = "button" class = 'btn back' onclick = "redirect()" value = "Back"></input>
                        <input type = "button" class = 'btn submit' onclick = "validate()" value = "Submit"></input>
                    </div>
                </div>
            </form>	
        </div>	
	</body>
</html>