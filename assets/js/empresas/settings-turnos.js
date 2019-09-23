jQuery(function($) {
	//Tabla Turnos de la empresa
	initDataTable('#turnos', {
		initComplete: function(settings, json) {
			//TURNO AUTOCOMPLETE
			var dataTb = settings.oInstance.fnGetData();
			var list_turnos = _.map(dataTb, function(obj, key) {
			 	return _.extend(obj, {value: obj.id_turno, text: obj.turno});
			});

			$('#turno').autoComplete({
				 minLength: 2
				,events: {
					search: _.debounce(function() {
						var qry = arguments[0], 
						callback = arguments[1];
				
						turnos = _.filter(list_turnos, function (obj) {
							return obj.turno.search(new RegExp('['+qry+']', 'gi')) !== -1;
						});

						callback(turnos);
					}, 300)
				}
			});
		}
		,ajax: {
		 	url: base_url('empresas/get_turnos_empresa')
	    	,dataSrc: ''
	    	,method: 'post'
	    	,dataType: 'json'
	    	,data: {dataEncription: $('#dataEncription').val()}
		}
		,createdRow: function (row, data, dataIndex) {
            $(row).data({id_turno: data.id_turno, id_empresa: data.id_empresa});
        }
		,columns: [
			 {data: 'turno'}
			,{data: 'custom_entrada'}
			,{data: 'custom_salida'}
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
	 * Description: Abrimos el modal para la edisión del turno
	 */
	 .on('click', '#turnos a.edit', function(e) {
	 	var tr = $(this).closest('tr');

	 	$.fn.formAjaxSend({
	 		 url: base_url('empresas/get_modal_update_turno')
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
	 * Description: Eliminación del turno
	 */
	.on('click', '#turnos a.remove', function(e) {
   		var tr = $(this).closest('tr');
		ISSwal({
            title: general_lang.esta_seguro,
            text: general_lang.delete_row,
            type: 'warning',
            showCancelButton: true
        }).then(function(response) {
        	if(response.value) {
        		$('tmp').formAjaxSend({
        			 url: base_url('empresas/process_remove_turno')
        			,data: tr.data()
        			,success: function(response) {
        				if (response.success) {
        					tr.addClass('bg-danger');
        					showNotify(response.msg, response.type, 'notification_important');
        					tr.animateCSS('fadeOutLeft', function() {
        						IS.init.dataTable['turnos'].row(tr).remove().draw();
        					});
        				} else ISSwal(response.title, response.msg, response.type);
        			}
        		});
        	}
        });
		e.preventDefault();
	})

	/**
	 * Element: <a.addItem>
	 * Event: Click
	 * Description: Abrimos el modal para el registro de un nuevo turno
	 */
	.on('click', '#turnos_wrapper a.addItem', function(e) {
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
 //    				} else ISSwal(response.title, response.msg, response.type);
	// 			}
	// 		})
	// 	}
	// 	e.preventDefault();
	// })

	/**
	 * Element: <form.form-new-crh>
	 * Event: submit
	 * Description: Enviamos los datos para el registro del nuevo contacto RH
	 */
	// .on('submit', '.form-new-crh', function(e) {
	// 	if ($(this).valid()) {
	// 		$(this).formAjaxSend({
	// 			 data:{dataEncription: $('#dataEncription').val()}
	// 			,success: function(response) {
	// 				if (response.success) {
 //    					showNotify(response.msg, response.type, 'notification_important');
 //    					IS.init.dataTable['turnos'].ajax.reload(null, false);
 //    					$('.modal.show').modal('hide');
 //    				} else ISSwal(response.title, response.msg, response.type);
	// 			}
	// 		})
	// 	}
	// 	e.preventDefault();
	// });
});