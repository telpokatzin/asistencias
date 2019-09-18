jQuery(function($) {
	$('.main-panel')

	/**
	 * Element: <li.weekDay>
	 * Event: Click
	 * Description: Abrimos el modal para definir los días hábiles de la semana
	 */
	 .on('click', 'li.weekDay', function(e) {
	 	var tr = $(this).closest('tr');

	 	$.fn.formAjaxSend({
	 		 url: base_url('empresas/get_modal_dias_habiles')
	 		,data: {dataEncription: $('#dataEncription').val()}
			,dataType: 'html'
	 		,success: function(response) {
				$('#content-modals').html(response);
				initModal('.modal');
	 		}
	 	});

		e.preventDefault();
	})

	

	$('#content-modals')//EVENTO DE LOS MODALES

	/**
	 * Element: <form.form-update-crh>
	 * Event: submit
	 * Description: Enviamos los datos de actualización del contacto RH
	 */
	// .on('submit', '.form-update-crh', function(e) {
	// 	if ($(this).valid()) {
	// 		$(this).formAjaxSend({
	// 			success: function(response) {
	// 				if (response.success) {
 //    					showNotify(response.msg, response.type, 'notification_important');
 //    					IS.init.dataTable['turnos'].ajax.reload(null, false);
 //    					$('.modal.show').modal('hide');
 //    				} else swal(response.title, response.msg, response.type);
	// 			}
	// 		})
	// 	}
	// 	e.preventDefault();
	// })
});