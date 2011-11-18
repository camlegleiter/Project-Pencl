//Create buttons that will go on the left that will change the color of the font.

var fontButton = document.images["fontButton"];

function changeImage() {
	document.images["fontButton"].src = "fontButtonHover.jpg";
	return true;
}

function changeImageBack() {
	document.images["fontButton"].src = "fontButton.jpg";
	return true;
}

function clickImage() {
	document.images["fontButton"].src = "fontButtonClick.jpg";
	return true;
}

function unclickImage() {
	changeImage();
	//show different text colors
	return true;
}

function changeTextColor(var color) {
	var text = document.getElementById("adddiv");
	text.style.color = color;
	return true;
}