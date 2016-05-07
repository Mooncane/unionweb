/*
**	Modal windows - for Journalizer 2015
**  Copyright Jeppe Schmidt 2015 (www.jeppeschmidt.dk)
**  Please include this section for reuse
**
*/

var modal = {};
var modaldiv;

modal.show = function(divname){
	// Create backdrop
	var d = document.createElement("div");
	d.className = 'modal-backdrop in';
	d.id = 'modal-backdrop';
	document.body.appendChild(d);

	// Create modal box
	modaldiv = document.getElementById(divname);
	modaldiv.style.display = 'block';
};

modal.hide = function(){
	modaldiv.style.display = 'none';
	var lastch = document.getElementById('modal-backdrop');
	lastch.parentNode.removeChild(lastch);
};