$(document).ready(function() {     //won't do anything until the page is loaded
	//masquer connexion et afficher inscription
	$("#inscription").click(function() {
		$("#premier").slideUp("slow",function(){
			$("#deuxieme").slideDown("slow");
		});
	});
	//masquer inscription et afficher connexion
	$("#connx").click(function() {
		$("#deuxieme").slideUp("slow",function(){
			$("#premier").slideDown("slow");
		});
	});


});