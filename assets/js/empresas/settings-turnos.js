jQuery(function($) {
	initDataTable('#turnos', {
		 ajax: {
		 	url: base_url('empresas/get_turnos_empresa')
	    	,dataSrc: ''
	    	,method: 'post'
	    	,dataType: 'json'
	    	,data: {dataEncription: $('#dataEncription').val()}
		}
        ,select: {
            style:    'os',
            selector: 'td:first-child'
        }
		,createdRow: function (row, data, dataIndex) {
            $(row).data({id_turno_empresa: data.id_turno_empresa, id_empresa: data.id_empresa});
        }
		,columns: [
			 {orderable: false, className: 'select-checkbox', data: function() {return '';}}
			,{data: 'turno'}
			,{data: 'custom_entrada'}
			,{data: 'custom_salida'}
			,{className: 'text-right', data: function(data) {
					return $('.content-btns').html().replace(/no-autoinit/g, '');
				}
			}
		]
	});

	$('.main-panel')//Tabla Contactos RH

	/**
	 * Element: <a.edit>
	 * Event: Click
	 * Description: Abrimos el modal para la edisión del contacto RH
	 */
	 .on('click', '#turnos a.edit', function(e) {
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
	.on('click', '#turnos a.remove', function(e) {
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
	.on('click', '#turnos_wrapper a.add-item', function(e) {
		$.fn.formAjaxSend({
			 url: base_url('empresas/get_modal_nuevo_CRH')
			,data:{dataEncription: $('#dataEncription').val()}
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
	 * Description: Agregamos la validación del formulario al abrir el modal
	 */
	.on('shown.bs.modal', '.modal', function(e) {
		$('.modal form.form-validate').validate();
		e.preventDefault();
	})

	/**
	 * Element: <form.form-update-crh>
	 * Event: submit
	 * Description: Enviamos los datos de actualización del contacto RH
	 */
	.on('submit', '.form-update-crh', function(e) {
		if ($(this).valid()) {
			$(this).formAjaxSend({
				success: function(response) {
					if (response.success) {
    					showNotify(response.msg, response.type, 'notification_important');
    					IS.init.dataTable['contactos-rh'].ajax.reload(null, false);
    					$('.modal.show').modal('hide');
    				} else swal(response.title, response.msg, response.type);
				}
			})
		}
		e.preventDefault();
	})

	/**
	 * Element: <form.form-new-crh>
	 * Event: submit
	 * Description: Enviamos los datos para el registro del nuevo contacto RH
	 */
	.on('submit', '.form-new-crh', function(e) {
		if ($(this).valid()) {
			$(this).formAjaxSend({
				 data:{dataEncription: $('#dataEncription').val()}
				,success: function(response) {
					if (response.success) {
    					showNotify(response.msg, response.type, 'notification_important');
    					IS.init.dataTable['contactos-rh'].ajax.reload(null, false);
    					$('.modal.show').modal('hide');
    				} else swal(response.title, response.msg, response.type);
				}
			})
		}
		e.preventDefault();
	});
});