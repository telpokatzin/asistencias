jQuery(function($) {
	initDataTable('#contactos-rh', {
		 ajax: {
		 	url: base_url('empresas/get_contacto_rh')
	    	,dataSrc: ''
	    	,method: 'post'
	    	,dataType: 'json'
	    	,data: {dataEncription: $('#dataEncription').val()}
		 }
		,createdRow: function (row, data, dataIndex) {
            $(row).data({id_contacto_rh: data.id_contacto_rh, id_empresa: data.id_empresa});
        }
		,columns: [
			 {data: 'nombre'}
			,{data: 'correo'}
			,{className: 'text-right', data: function(data) {
					return $('.content-btns').html().replace(/no-autoinit/g, '');
				}
			}
		]
	});

	$('#contactos-rh')//Tabla Contactos RH

	/**
	 * Element: <a.edit>
	 * Event: Click
	 * Description: Abrimos el modal para la ediíon del contacto RH
	 */
	 .on('click', 'a.edit', function(e) {
	 	var tr = $(this).closest('tr');

	 	$.fn.formAjaxSend({
	 		 url: base_url('empresas/get_modal_update_CRH')
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
	 * Description: Eliminación del contacto RH
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
        			 url: base_url('empresas/process_remove_CRH')
        			,data: tr.data()
        			,success: function(response) {
        				if (response.success) {
        					showNotify(response.msg, response.type, 'notification_important');
        					IS.init.dataTable['contactos-rh'].row(tr).remove().draw();

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
	 * Description: Abrimos el modal para el registro de un nuevo contacto RH.
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
	.on('shown.bs.modal', '.modal', function(e) {
		$('.modal form.form-validate').validate();
		e.preventDefault();
	})

	/**
	 * Element: <div.#modal-registro-empresa>
	 * Event: shown.bs.modal
	 * Description: Agregamos la validación del formulario dentro del modal para el registro de una nueva empresa
	 */
	// .on('shown.bs.modal', '#modal-registro-empresa', function(e) {
	// 	$('.form-registro-empresa').validate({
	// 		submitHandler: function(form) {
	// 			$(form).formAjaxSend({
	// 				success: function(response) {
	// 					if (response.success) {
 //        					showNotify(response.msg, response.type, 'notification_important');
 //        					IS.init.dataTable['empresas'].ajax.reload(null, false);
 //        					$('.modal.show').modal('hide');
 //        				} else swal(response.title, response.msg, response.type);
	// 				}
	// 			});
	// 		}
	// 	});
	// 	e.preventDefault();
	// })

	/**
	 * Element: <div.#modal-update-empresa>
	 * Event: shown.bs.modal
	 * Description: Agregamos la validación del formulario dentro del modal para la actualización de la empresa
	 */
	// .on('shown.bs.modal', '#modal-update-empresa', function(e) {
	// 	$('.form-update-empresa').validate({
	// 		submitHandler: function(form) {
	// 			$(form).formAjaxSend({
	// 				success: function(response) {
	// 					if (response.success) {
 //        					showNotify(response.msg, response.type, 'notification_important');
 //        					IS.init.dataTable['empresas'].ajax.reload(null, false);
 //        					$('.modal.show').modal('hide');
 //        				} else swal(response.title, response.msg, response.type);
	// 				}
	// 			});
	// 		}
	// 	});
	// 	e.preventDefault();
	// });
});