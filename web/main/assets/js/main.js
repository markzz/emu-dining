function changeLocationDropdown(locationObj){
	//Change dropbox title
	var title = document.getElementById("locationsDropdown");
	title.innerHTML = locationObj.childNodes[0].innerHTML;
	
	//Clear higlights of all other options
	var dropMenu = document.getElementById("locationsDropdownMenu");
	var c = dropMenu.childNodes.length;
	for(var i=0; i<c; i++){
		dropMenu.childNodes[i].id = "";
	}
	
	//Highlight new option
	locationObj.id = "highlighted";
}

function changeModalText(givenText, givenTitle){
	var modal = document.getElementById("dietModal");
	modal.innerHTML = givenText;
	
	var modalTitle = document.getElementById("dietModalTitle");
	modalTitle.innerHTML = givenTitle;
}