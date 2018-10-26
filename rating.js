function check(x) {
    var image = document.getElementsByClassName("star");
    for (i = 0; i < 5; i++) {
        image[i].src = "./icon/nullstar.png";
    }
    for (i = 0; i < x; i++) {
        image[i].src = "./icon/fullstar.png";
    }
}