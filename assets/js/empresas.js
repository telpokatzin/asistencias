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
			,{className: 'text-right', data: function(data) {
					return $('.content-btns').html().replace(/no-autoinit/g, '');
				}
			}
		]
	});

	$('.main-panel')

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
        					showNotify(response.msg, response.type, 'notification_important');
        					IS.init.dataTable['empresas'].row(tr).remove().draw();

        				} else swal(response.title, response.msg, response.type);
        			}
        		});
        	}
        });
		e.preventDefault();
	})
});