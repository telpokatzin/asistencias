jQuery(function($) {
	//Tabla Contactos RH
	initDataTable('#contactos-rh', {
		 ajax: {
		 	url: base_url('empresas/get_contacto_rh')
	    	,dataSrc: ''
	    	,data: {dataEncription: $('#dataEncription').val()}
		}
		,createdRow: function (row, data, dataIndex) {
            $(row).data({id_contacto_rh: data.id_contacto_rh, id_empresa: data.id_empresa});
            $(row).find('a.edit').remove();
        }
		,columns: [
			 {data: 'nombre_completo'}
			,{data: 'correo'}
			,{className: 'text-right', data: function(data) {
					return $('.content-btns').html().replace(/no-autoinit/g, '');
				}
			}
		]
	});

	$('.main-panel')

	/**
	 * Element: <a.edit>
	 * Event: Click
	 * Description: Abrimos el modal para la edisión del contacto RH
	 */
	 .on('click', '#contactos-rh a.edit', function(e) {
	 	var tr = $(this).closest('tr');

	 	$.fn.formAjaxSend({
	 		 url: base_url('empresas/get_modal_update_CRH')
	 		,data: tr.data()
			,dataType: 'html'
	 		,success: function(response) {
				$('#content-modals').html(response);
				initModal('.modal');
	 		}
	 	});

		e.preventDefault();
	})

	/**
	 * Element: <a.remove>
	 * Event: Click
	 * Description: Eliminación del contacto RH
	 */
	.on('click', '#contactos-rh a.remove', function(e) {
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
        					tr.addClass('bg-danger');
        					showNotify(response.msg, response.type, 'notification_important');
        					tr.animateCSS('fadeOutLeft', function() {
        						IS.init.dataTable['contactos-rh'].row(tr).remove().draw();
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
	 * Description: Abrimos el modal para el registro de un nuevo contacto RH.
	 */
	.on('click', '#contactos-rh_wrapper a.addItem', function(e) {
		$.fn.formAjaxSend({
			 url: base_url('empresas/get_modal_nuevo_CRH')
			,data:{dataEncription: $('#dataEncription').val()}
			,dataType: 'html'
			,success: function(response) {
				$('#content-modals').html(response);
				initModal('.modal', {
					onOpenEnd: function() {
						initDataTable('#content-modals table');
					}
				});
			}
		});

		e.preventDefault();
	});



	$('#content-modals')//EVENTO DE LOS MODALES

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
	.on('click', 'a.add-contactoRH', function(e) {	
		var tr = $(this).closest('tr');
		$.fn.formAjaxSend({
			 url: base_url('empresas/process_save_contacto_rh')
			,data: $.extend(tr.data(), {dataEncription: $('#dataEncription').val()})
			,success: function(response) {
				if (response.success) {
        			showNotify(response.msg, response.type, 'notification_important');
					tr.addClass('bg-success');
        			tr.animateCSS('fadeOutLeft', function() {
        				IS.init.dataTable['colaboradores'].row(tr).remove().draw();
						IS.init.dataTable['contactos-rh'].ajax.reload(null, false);
        			});
				} else swal(response.title, response.msg, response.type);
			}
		})
		e.preventDefault();
	});
});