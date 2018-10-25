function validate(){
	var rating = document.getElementById("form_rating").value;
	var review = document.getElementById("form_review").value;
	console.log(rating);
	console.log(review);
	var form = document.getElementsByClassName("btn submit");
	var legal = true;
	if (rating.length == 0){
		document.getElementById("rating_error").innerHTML = "*field harus diisi.";
		legal = false;
	}
	else if ((rating < 1) || (rating > 5)){
		document.getElementById("rating_error").innerHTML = "*rating harus di antara 1 sampai 5.";
		legal = false;
	}
	if (review.length == 0){
		document.getElementById("review_error").innerHTML = "*field harus diisi.";
		legal = false;
	}
	if (legal){
		document.edit_form.submit();
	}
};

function redirect(){
	window.location = "history.php";
};