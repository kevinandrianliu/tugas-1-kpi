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
			if($usernameerror == "" and $passworderror == ""){
				// Create connection
				$conn = mysqli_connect($dbserver,$dbuser,$dbpass);
				if(mysqli_connect_error()) {
					die('Could not connect: ' . mysqli_connect_error());
				}
				mysqli_select_db($conn,"wbd_schema");
				$ip = get_client_ip();
				$uagent = $_SERVER['HTTP_USER_AGENT'];
				$data = mysqli_query($conn,"SELECT * FROM login_attempt WHERE ip=\"$ip\", uagent=\"$uagent\" AND uname=\"$usernamelogin\"");
				if (empty(mysqli_fetch_assoc($data))){
					mysqli_query($conn,"INSERT INTO login_attempt (ip, uagent, uname, total) VALUES (\"$ip\",\"$uagent\", \"$usernamelogin\", 1)");
				}
				$ac_token = md5(mt_rand());
				$ex_timestamp_cookie = time() + (60*60);
				setcookie("access_token",$ac_token,$ex_timestamp_cookie);
				
				//finding in database
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
					$params = session_get_cookie_params();
					setcookie("access_token",$ac_token,$ex_timestamp_cookie, $params["path"], $params["domain"], TRUE, TRUE);

					$dbserver = "127.0.0.1";
					$dbuser = "root";
					$dbpass = "";
					$conn = mysqli_connect($dbserver,$dbuser,$dbpass);

					mysqli_select_db($conn,"wbd_schema");
					$sql1 = "DELETE FROM access_token WHERE username= ?";
					$stmt1 = $conn->prepare($sql1);
					$stmt1->bind_param('s',$uname);
					$stmt1->execute();

					$sql2 = "INSERT INTO access_token (token_id,username,expiry_time) VALUES (?,?,?)";
					$stmt2 = $conn->prepare($sql2);
					$stmt2->bind_param('sss',$ac_token, $uname, $ex_date_database);
					$stmt2->execute();
					mysqli_query($conn,"DELETE FROM login_attempt WHERE ip=$ip, uagent=$uagent AND uname=$usernamelogin");

					mysqli_close($conn);
					header('Location: searchbook.php');
				}
				else{
					$usernameerror = " *Either username is invalid";
					$passworderror = " or password is invalid";
					$data = mysqli_query($conn,"SELECT total FROM login_attempt WHERE ip=\"$ip\", uagent=\"$uagent\" AND uname=\"$usernamelogin\"");
					$data_1 = mysqli_fetch_assoc($data);
					if ($data_1["total"] > 3){
						setcookie("access_token","",0);
						mysqli_close($conn);
					}
					else{
						mysqli_query($conn,"UPDATE login_attempt SET total = total + 1 WHERE ip=\"$ip\", uagent=\"$uagent\" AND uname=\"$usernamelogin\"");
				
					}
				}
			}
			else{}
		}

		function get_client_ip() {
			$ipaddress = '';
			if (getenv('HTTP_CLIENT_IP'))
				$ipaddress = getenv('HTTP_CLIENT_IP');
			else if(getenv('HTTP_X_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
			else if(getenv('HTTP_X_FORWARDED'))
				$ipaddress = getenv('HTTP_X_FORWARDED');
			else if(getenv('HTTP_FORWARDED_FOR'))
				$ipaddress = getenv('HTTP_FORWARDED_FOR');
			else if(getenv('HTTP_FORWARDED'))
			   $ipaddress = getenv('HTTP_FORWARDED');
			else if(getenv('REMOTE_ADDR'))
				$ipaddress = getenv('REMOTE_ADDR');
			else
				$ipaddress = 'UNKNOWN';
			return $ipaddress;
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