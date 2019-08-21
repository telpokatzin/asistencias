jQuery(function($) {	
	setTimeout(function(){$('#usuario').focus();}, 500);

	$("form").validate({
	 	 ignore:'.ignore'
	 	,submitHandler: function(form) {
	 		$(form).formAjaxSend({
			 	 url: base_url("login/auth")
	 			,success: function(response) {
	 				var reloadpage = $('button').data('reloadpage');

	 				if (response.success) {
	 					if (reloadpage == 1) {
	 						location.reload();
	 					} else redirect(response.redirect);//ACCESO CORRECTO PRIMER INGRESO
	 				//ERROR AUTENTICACION
	 				} else  swal(response.title, response.msg, response.type); 
	 			}
	 		});
	 	}
	})
});

