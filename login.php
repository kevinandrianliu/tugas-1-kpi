<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="loginregister.css">
	</head>
	<body>
	<?php
		// define variables and set to empty values
		$usernamelogin = $usernameerror = $passwordlogin = $passworderror = "";
		$dbserver = '127.0.0.1';
		$dbuser = 'root';
		$dbpass = '';
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if(empty ($_POST["usernamelogin"])){
				$usernameerror = " *Username is required";
			}
			else{					
				$usernamelogin = $_POST["usernamelogin"];
			}
			if(empty ($_POST["passwordlogin"])){
				$passworderror = " *Password is required";
			}
			else{					
				$passwordlogin = $_POST["passwordlogin"];
			}
			if($usernameerror == "" and $usernameerror == ""){
				// Create connection
				$conn = mysqli_connect($dbserver,$dbuser,$dbpass);
				if(mysqli_connect_error()) {
					die('Could not connect: ' . mysqli_connect_error());
				}
				
				//finding in database
				mysqli_select_db($conn, "wbd_schema");
				$sql = "SELECT username FROM user WHERE username = ? and password = ?";
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ss',$usernamelogin, $passwordlogin);
				$stmt->execute();
				$result = $stmt->get_result();
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);		 
				$count = mysqli_num_rows($result);
			  
				// If result matched $usernamelogin and $passwordlogin, table row must be 1 row
				if($count === 1) {
					$uname = $usernamelogin;
					$ac_token = md5(mt_rand());
					$ex_timestamp_database = time() + (60*60*24);
					$ex_timestamp_cookie = $ex_timestamp_database + (10*365*24*60*60);
					$ex_date_database = date('Y-m-d H:i:s', $ex_timestamp_database);
					setcookie("access_token",$ac_token,$ex_timestamp_cookie);

					$dbserver = "127.0.0.1";
					$dbuser = "root";
					$dbpass = "";
					$conn = mysqli_connect($dbserver,$dbuser,$dbpass);

					mysqli_select_db($conn,"wbd_schema");
					mysqli_query($conn,"DELETE FROM access_token WHERE username=\"$uname\"");
					mysqli_query($conn,"INSERT INTO access_token (token_id,username,expiry_time) VALUES (\"$ac_token\",\"$uname\",'$ex_date_database')");

					mysqli_close($conn);
					header('Location: searchbook.php');
				}
				else{
					$usernameerror = " *Either username is invalid";
					$passworderror = " or password is invalid";
					mysqli_close($conn);
				}
			}
			else{}
		}
	?>
		<div>
			<img class = "backgroundimage" src = "./icon/loginregister.png" alt="Login Register Background">
			<h2 class = "headerlogin">LOGIN</h2>
			<form action = "login.php" method = "post" class = "login">
				<table>
					<tr>
						<td> <p class = "formlogin"> Username </p> </td>
						<td> 
							<input class = "text1" name="usernamelogin"> <?php echo "<span id = \"error\">$usernameerror</span>";?> </input>
						</td>
					</tr>
					<tr>
						<td> <p class = "formlogin"> Password </p> </td>
						<td>  
							<input type = "password" name="passwordlogin"> <?php echo "<span id = \"error\">$passworderror</span>";?> </input>
						</td>
					</tr>
				</table>
				<button class = "btn">LOGIN</button>
			</form>
			<a href = "register.php">Don't have an account?</a>
		</div>
	</body>
</html> 