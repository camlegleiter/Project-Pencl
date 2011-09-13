$(document).ready(function() {
	
	// Expand Panel
	$("#open").click(function(){
		$("div#panel").slideDown("slow");
	
	});	
	
	// Collapse Panel
	$("#close").click(function(){
		$("div#panel").slideUp("slow");	
	});		
	
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$("#toggle a").click(function () {
		$("#toggle a").toggle();
	});		
		
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$("#showRegister").click(
	function () 
	{
		$("#toggleLogin").fadeOut(500, function() 
			{ 
				$("#toggleRegister").fadeIn(500); 
			}
		); 
	}
	);
	
	$("#showLogin").click(
	function () 
	{
		$("#toggleRegister").fadeOut(500, function() 
			{ 
				$("#toggleLogin").fadeIn(500); 
			}
		);
	}
	);


		
});