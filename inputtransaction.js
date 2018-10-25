function AlertOrder() {
    this.render = function (dialog) {
        var width = window.innerWidth;
        var height = window.innerHeight;
        var overlay = document.getElementById("dialogoverlay");
        var box = document.getElementById("dialogbox");
        overlay.style.display = "block";
        overlay.style.height = height + "px";
        box.style.left = (width/2) - (500*0.5) + "px";
        box.style.top = "100px";
        box.style.display = "block";
        document.getElementById("dialogcontent").innerHTML = dialog;
        document.getElementById("dialogclose").innerHTML = "<span class='close' onclick='Alert.close()'>&times;</span>";
    }

    this.close = function() {
        document.getElementById("dialogbox").style.display = "none";
        document.getElementById("dialogoverlay").style.display = "none";
    }
}

function getCookie(cname) {
    var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i <ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}

function inputorder() {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			Alert.render(this.responseText);
		}
	}
	var x = document.getElementById("quantity").value;
	xhttp.open("GET", "inputorder.php?usr=" + getCookie("username") + "&id=" + getCookie("bookid") + "&q=" + x, true);
	xhttp.send();
}

var Alert = new AlertOrder();