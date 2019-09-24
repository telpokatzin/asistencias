jQuery(function($) {
	//TURNO AUTOCOMPLETE
	$('#turno').on('autocomplete.select', function(evt, item) {
		$.fn.formAjaxSend({
	 		 url: base_url('empresas/process_save_turno_empresa')
	 		,data: item
	 		,blockScreen: false
	 		,isPromise: true
		}).done(function(response) {
			response.success 
				? showNotify(response.msg, response.type, 'notification_important')
				: ISSwal(response.title, response.msg, response.type);
		});
	});

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
				initModal('.modal', {
					onOpenEnd: () => {
						$('.modal.fade form').validate({
							errorPlacement: function(error, element) {
								$('.errorPlacement').html(error);
							}
						});
					}
				});
	 		}
	 	});

		e.preventDefault();
	});

	$('#content-modals')//EVENTO DE LOS MODALES

	/**
	 * Element: <form.form-registro-dias-habiles>
	 * Event: submit
	 * Description: Enviamos los días hábiles de la empresa
	 */
	.on('submit', '.form-registro-dias-habiles', function(e) {
		$(this).formAjaxSend({
	 		 data: {dataEncription: $('#dataEncription').val()}
			,success: function(response) {
				if (response.success) {
					showNotify(response.msg, response.type, 'notification_important');
					$('.modal.show').modal('hide');
					$.fn.formAjaxSend({
				 		 url: base_url('empresas/get_dias_habiles_empresa/0/0')
				 		,data: {dataEncription: $('#dataEncription').val()}
						,dataType: 'html'
						,blockScreen: false
				 		,success: function(response) {
				 			$('.content-dias-habiles').html(response);
				 		}
					});
				} else ISSwal(response.title, response.msg, response.type);
			}
		});
		
		e.preventDefault();
	});
});