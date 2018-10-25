<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="loginregister.css">
		<script>
			function validate(element, value){	
				if(element === 'username'){
					var username = value;
				}
				else{
					var email = value;
				}
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if ((this.readyState == 4) && (this.status == 200)) {
						//consequence 
						if(xmlhttp.responseText == "valid"){
							if(element == 'username'){
								document.getElementById("uname").innerHTML = '<img src="./Login and register picture/orange_tick.png" class = "tick" alt = "picture">';
							}
							else{
								document.getElementById("mail").innerHTML = '<img src="./Login and register picture/orange_tick.png" class = "tick" alt = "picture">';
							}
						}
						else{
							if(element == 'username'){
								document.getElementById("uname").innerHTML = xmlhttp.responseText;
							}
							else{
								document.getElementById("mail").innerHTML = xmlhttp.responseText;
							}
							return;
						}
					}
				};
				xmlhttp.open("GET", "action.php?username=" + username + "&email=" + email + "&element=" + element, true);
				xmlhttp.send(); 
			}
		</script>
	</head>
	<body>
	<?php
		// define variables and set to empty values
		$nameregister = $nameerror = $passwordregister = $passworderror = $usernameerror = $emailerror = "";
		$confirmpasswordregister = $confirmpassworderror = $addressregister = $addresserror = $phonenumberregister = $phonenumbererror = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST") {		
			//validation in every field
			if(empty ($_POST["val"])){
				$usernameerror = " *Username is required";
			}
			if(empty ($_POST["email"])){
				$emailerror = " *Email is required";
			}
			if(empty ($_POST["nameregister"])){
				$nameerror = " *Name is required";
			}
			else{
				$nameregister = $_POST["nameregister"];
			}
			if(empty ($_POST["passwordregister"])){
				$passworderror = " *Password is required";
			}
			else{					
				$passwordregister = $_POST["passwordregister"];
			}
			if(empty ($_POST["confirmpasswordregister"])){
				$confirmpassworderror = " *Confirm Password is required";
			}
			else if ($passwordregister !== $_POST["confirmpasswordregister"]) {					
				$confirmpassworderror = " *Confirm Password is not same as in field password";
			}
			else{
				$confirmpasswordregister = $_POST["confirmpasswordregister"];
			}
			if(empty ($_POST["addressregister"])){
				$addresserror = " *Address is required";
			}
			else{					
				$addressregister = $_POST["addressregister"];
			}
			if(empty ($_POST["phonenumberregister"])){
				$phonenumbererror = " *Phone Number is required";
			}
			else{
				$numbersOnly = preg_replace("[^0-9]", "", $_POST["phonenumberregister"]);
				$numberOfDigits = strlen($numbersOnly);
				if (($numberOfDigits >= 9) and ($numberOfDigits <= 12)) {
					$phonenumberregister = $_POST["phonenumberregister"];
				} 
				else {
					$phonenumbererror = " *Invalid phone number";
				}
			}
			//getting variables and setting up the database
			$usernameregister = $_POST["val"];
			$emailregister = $_POST["email"];
			$dbserver = '127.0.0.1';
			$dbuser = 'root';
			$dbpass = '';
			$conn = mysqli_connect($dbserver,$dbuser,$dbpass);
			if(mysqli_connect_error()) {
				die('Could not connect: ' . mysqli_connect_error());
			}
			mysqli_select_db($conn,"wbd_schema");
			
			//validation involving searching in database
			$sql = "SELECT username FROM user WHERE username = '$usernameregister'";
			$selection_username = selector($conn, $sql);
			if($selection_username == "true"){
				$usernameerror = " *Username has been used";
			}
			$sql = "SELECT password FROM user WHERE password = '$passwordregister'";
			$selection_password = selector($conn, $sql);
			if($selection_password){
				$passworderror = " *Password has been used";
			}
			$sql = "SELECT name FROM user WHERE name = '$nameregister'";
			$selection_name = selector($conn, $sql);
			if($selection_name){
				$nameerror = " *Name has been used";
			}
			$sql = "SELECT email FROM user WHERE email = '$emailregister'";
			$selection_email = selector($conn, $sql);
			if($selection_email == "true"){
				$emailerror = " *Email has been used";
			}
			$sql = "SELECT address FROM user WHERE address = '$addressregister'";
			$selection_address = selector($conn, $sql);
			if($selection_address){
				$addresserror = " *Address has been used";
			}
			$sql = "SELECT phone_num FROM user WHERE phone_num = '$phonenumberregister'";
			$selection_phonenumber = selector($conn, $sql);
			if($selection_phonenumber){
				$phonenumbererror = " *Phone number has been used";
			}
			if($passworderror == "" and $nameerror == "" and $emailerror == "" and $addresserror == "" and $phonenumbererror == ""){
				//inserting the register field values
				$query = "INSERT INTO user (username,password,name,email,address,phone_num) VALUES (\"".$usernameregister."\",\"".$passwordregister."\",\"".$nameregister."\",\"".$emailregister."\",\"".$addressregister."\",\"".$phonenumberregister."\")";
				if (mysqli_query($conn,$query)){
					echo '<script language="javascript">';
					echo 'alert("Your register is successful")';
					echo '</script>';
				} else {
					echo mysqli_error($conn);
				}
			}
			else{}
			mysqli_close($conn);	
		}
		function selector($conn, $sql){
			//finding whether an instance of field has been used before
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$count = mysqli_num_rows($result);
			// If result matched $usernamelogin and $passwordlogin, table row must be 1 row
			if($count >= 1){
				return true;
			}
			else{
				return false;
			}
		}
	?>
		<div>
			<img class = "backgroundimage" src = "./Login and register picture/loginregister.png" alt="Login Register Background">
			<h2 class = "headerregister">REGISTER</h2>
			<form action="register.php" method = "post" class ="register">
				<table>
					<tr>
						<td> <p class = "formloginregister"> Name </p> </td>
						<td>  
							<input class = "text" name="nameregister" ><?php echo "<span id = \"error\">$nameerror</span>";?></input>
						</td>
					</tr>
					<tr>
						<td> <p class = "formloginregister"> Username </p> </td>
						<td>  
							<input name = "val" id = "val" name="usernameregister" onkeyup = "validate('username', this.value)"></input><span id="uname"><?php echo "<span id = \"error\">$usernameerror</span>";?></span>
						</td>
					</tr>
					<tr>
						<td> <p class = "formloginregister"> Email </p> </td>
						<td>  
							<input name = "email" id = "email" name="emailregister" onkeyup = "validate('email', this.value)"></input><span id="mail"><?php echo "<span id = \"error\">$emailerror</span>";?></span>
						</td>
					</tr>
					<tr>
						<td> <p class = "formloginregister"> Password </p> </td>
						<td>  
							<input type = "password" name="passwordregister"><?php echo "<span id = \"error\">$passworderror</span>";?></input>
						</td>
					</tr>
					<tr>
						<td> <p class = "formloginregister"> Confirm Password </p> </td>
						<td>  
							<input type = "password" name="confirmpasswordregister"><?php echo "<span id = \"error\">$confirmpassworderror</span>";?></input>
						</td>
					</tr>
					<tr>
						<td> <p class = "formloginregister"> Address </p> </td>
						<td>  
							<textarea name="addressregister"></textarea><?php echo "<span id = \"error\">$addresserror</span>";?>
						</td>
					</tr>
					<tr>
						<td> <p class = "formloginregister"> Phone Number </p> </td>
						<td>  
							<input class = "text" name="phonenumberregister" maxlength=12><?php echo "<span id = \"error\">$phonenumbererror</span>";?> </input>
						</td>
					</tr>
					<button class = "btn reg">REGISTER</button>
				</table>
			</form>
			<a href = "login.php" class = "link_register">Already have an account?</a>
		</div>
	</body>
</html> 