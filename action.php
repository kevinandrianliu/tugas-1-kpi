<?php
	// define variables and set to empty values
	$usernameregister = $error = $emailregister = "";
	
	//getting elements from AJAX
	$tempusernameregister = $_GET["username"];
	$tempemailregister = $_GET["email"];
	$element = $_GET["element"];
	
	//validation
	if($element == 'username'){
		if(strlen($tempusernameregister) == 0){
			echo " *Username is required";
		}
		else if((strlen($tempusernameregister) < 1) or (strlen($tempusernameregister) > 20)){					
			echo " *Username must be 1 to 20 characters long";
		}
		else{
			$usernameregister = $tempusernameregister;
			echo "valid";
		}
	}
	else{
		if(strlen($tempemailregister) == 0){
			echo " *Email is required";
		}	
		else if(filter_var($tempemailregister, FILTER_VALIDATE_EMAIL)){
			$emailregister = $tempemailregister;
			echo "valid";
		}
		else{
			echo " *Email is invalid";
		}
	}
?>
