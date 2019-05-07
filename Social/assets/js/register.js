$(document).ready(function() {
     //Klikatessa rekisteröidy, piilota signup ja näytä rekisteröinti formi
	$("#signup").click(function() {
		$("#first").slideUp("slow", function(){
			$("#second").slideDown("slow");
		});
	});

	//Klikatessa kirjaudu, piilota rekisteröinti ja näytä signup formi
	$("#signin").click(function() {
		$("#second").slideUp("slow", function(){
			$("#first").slideDown("slow");
		});
	});


});