jQuery(function($) {
	initDataTable('#empresas', {
		 ajax: {
		 	url: base_url('empresas/get_empresas_ajax')
	    	,dataSrc: ''
		 }
		,createdRow: function (row, data, dataIndex) {
            $(row).attr('data-id_empresa', data.id_empresa);
        }
		,columns: [
			 {data: 'empresa'}
			,{data: 'razon_social'}
			,{className: 'text-right', data: function(data) {
					return $('.content-btns').html().replace(/no-autoinit/g, '');
				}
			}
		]
	});

	$('.main-panel')

	/**
	 * Element: <a.settings>
	 * Event: Click
	 * Description: Redireccionamiento a la vista de configuracion de la empresa
	 */
	 .on('click', 'a.settings', function(e) {
	 	var tr = $(this).closest('tr');

		form_send(tr.data(), base_url('empresas/configuraciones'));

		e.preventDefault();
	})

	/**
	 * Element: <a.edit>
	 * Event: Click
	 * Description: Edición de la empresa
	 */
	 .on('click', 'a.edit', function(e) {
	 	var tr = $(this).closest('tr');

	 	$.fn.formAjaxSend({
	 		 url: base_url('empresas/get_modal_update_empresa')
	 		,data: tr.data()
			,dataType: 'html'
	 		,success: function(response) {
				$('#content-modals').html(response);
				$('#content-modals .modal').modal();
	 		}
	 	});

		e.preventDefault();
	})

	/**
	 * Element: <a.remove>
	 * Event: Click
	 * Description: Eliminación de la empresa
	 */
	.on('click', 'a.remove', function(e) {
   		var tr = $(this).closest('tr');
		swal({
            title: general_lang.esta_seguro,
            text: general_lang.delete_row,
            type: 'warning',
            showCancelButton: true
        }).then(function(response) {
        	if(response.value) {
        		$('tmp').formAjaxSend({
        			 url: base_url('empresas/process_remove_empresa')
        			,data: tr.data()
        			,success: function(response) {
        				if (response.success) {
        					tr.addClass('bg-danger');
        					showNotify(response.msg, response.type, 'notification_important');
        					tr.animateCSS('fadeOutLeft', function() {
        						IS.init.dataTable['empresas'].row(tr).remove().draw();
        					});

        				} else swal(response.title, response.msg, response.type);
        			}
        		});
        	}
        });
		e.preventDefault();
	})

	/**
	 * Element: <a.add-item>
	 * Event: Click
	 * Description: Abrimos el modal para el registro de una nueva empresa.
	 */
	.on('click', 'a.add-item', function(e) {
		$.fn.formAjaxSend({
			 url: base_url('empresas/get_modal_nueva_empresa')
			,dataType: 'html'
			,success: function(response) {
				$('#content-modals').html(response);
				$('#content-modals .modal').modal();
			}
		});

		e.preventDefault();
	});

	$('#content-modals')//EVENTO DE LOS MODALES
	.on('hidden.bs.modal', '.modal', function(e) {
		$('#content-modals').html('');
	})

	/**
	 * Element: <div.#modal-registro-empresa>
	 * Event: shown.bs.modal
	 * Description: Agregamos la validación del formulario dentro del modal para el registro de una nueva empresa
	 */
	.on('shown.bs.modal', '#modal-registro-empresa', function(e) {
		$('.form-registro-empresa').validate({
			submitHandler: function(form) {
				$(form).formAjaxSend({
					success: function(response) {
						if (response.success) {
        					showNotify(response.msg, response.type, 'notification_important');
        					IS.init.dataTable['empresas'].ajax.reload(null, false);
        					$('.modal.show').modal('hide');
        				} else swal(response.title, response.msg, response.type);
					}
				});
			}
		});
		e.preventDefault();
	})

	/**
	 * Element: <div.#modal-update-empresa>
	 * Event: shown.bs.modal
	 * Description: Agregamos la validación del formulario dentro del modal para la actualización de la empresa
	 */
	.on('shown.bs.modal', '#modal-update-empresa', function(e) {
		$('.form-update-empresa').validate({
			submitHandler: function(form) {
				$(form).formAjaxSend({
					success: function(response) {
						if (response.success) {
        					showNotify(response.msg, response.type, 'notification_important');
        					IS.init.dataTable['empresas'].ajax.reload(null, false);
        					$('.modal.show').modal('hide');
        				} else swal(response.title, response.msg, response.type);
					}
				});
			}
		});
		e.preventDefault();
	});
});