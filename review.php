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
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Profile - <?php echo $uname?></title>
		<link rel="stylesheet" type="text/css" href="./base.css">
		<link rel="stylesheet" type="text/css" href="./review.css">
		<script src="./review.js"></script>
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
				if (!(mysqli_query($conn,$query))){
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
		<form action = "review.php" name="edit_form" method = "post">
			<table>
				<tr>
					<td id="book_desc">
						<h1><?php echo "$title";?></h1>
						<h3><?php echo "$author";?></h3>
					</td>
					<td>
					<img src='<?php echo "$book_pic";?>' id = "book">
					</td>
				</tr>
						 
				<tr>
					<td>
						<h2>Add Rating </h2><br>
					</td>
				</tr>

				<tr>
					<td>
						<input type = "text" name = "form_rating" id = "form_rating"></input><br><br><br>
					</td>
					<td>
						<span class = "error" id = "rating_error"> </span>
					</td>
				</tr>
			
				<tr>
					<td>
						<h2>Add Comment </h2><br>
					</td>
				</tr>
			
				<tr>
					<td>
						<textarea name = "form_review" id = "form_review"></textarea>
					</td>
					<td>
						<span class = "error" id= "review_error"></span>
					</td>
				</tr>
				<input type = "hidden" name = "fetch_order_id" id = "fetch_order_id" value = <?php echo $order_id;?>></input>
				<tr>
				<td>
					
					<input type = "button" class = 'btn back' onclick = "redirect()" value = "Back"></input>
					<input type = "button" class = 'btn submit' onclick = "validate()" value = "Submit"></input>
				</td>
				</tr>
			</table>
		</form>		
	</body>
</html>