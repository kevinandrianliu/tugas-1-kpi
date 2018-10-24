<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="loginregister.css">
	</head>
	<body>
	<?php
		include ("script.php");
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
				$sql = "SELECT username FROM user WHERE username = '$usernamelogin' and password = '$passwordlogin'";
				$result = mysqli_query($conn,$sql);
				$row = mysqli_fetch_array($result,MYSQLI_ASSOC);		 
				$count = mysqli_num_rows($result);
			  
				// If result matched $usernamelogin and $passwordlogin, table row must be 1 row
				if($count === 1) {
					setcookie($usernamelogin, $passwordlogin, time() + (3600), "/"); // one hour only
					$user = $_COOKIE[$usernamelogin];
					mysqli_close($conn);
					header("Location: searchbook.html");
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
			<img class = "backgroundimage" src = "loginregister.png" alt="Login Register Background">
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