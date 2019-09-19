(function ( $ ) {
	$.fn.extend({
		/**
		 * Función principal para realizar el envió de los datos via ajax a partir de un formulario
		 */
		formAjaxSend: function(options) {
			var options 	= options ? options : {};
			var dataExtra 	= (typeof options.data == 'undefined') ? {} : options.data;
			options.data 	= undefined;
			
			//DATOS QUE SE OBTIENE DEL FORMULARIO
			var $form 		= $(this);
				form_url 	= $form.prop('action'),
				form_data 	= $form.find(':not(.encript-md5):not(.send-ignore):not([type=password])').serializeArray();
				btnSubmit 	= $form.find('[type="submit"], .submit');

			//Se busca las contraseñas a encriptar
			$(this).find('.encript-md5, [type=password]').each(function() {
				form_data[form_data.length] = {
					 'name': $(this).prop('name')
					,'value':$.md5($(this).val())
				};
			});

			var settings = {
		         url: form_url					//ruta a enviar los datos
		        ,method: 'post'					//forma de enviar los datos del formulario.
		        ,data: form_data 				//datos a enviar ya sea un objeto o array, si no se recibe trata de obtener del form. example {data1: valor1, data2: valor2}
				,dataType: 'json' 				//dataType tipo de datos que se espera recibir del lado del servidor. xml | json | script | html
				,async: true
				,cache: true
				,contentType: 'application/x-www-form-urlencoded; charset=UTF-8'
				,processData: true
        		,blockScreen: true
        		,isPromise: false
				,beforeSend: function(jqXHR) {} //función a realizar antes de procesar la petición al servidor
				,success: function(response) {} //script a ejecutar despues de procesar la petición al servidor
				,error: function(jqXHR) {} 		//función a realizar al generar un error en la petición al servidor
				,complete: function(jqXHR) {} 	//función a realizar al completar la petición al servidor
		    };
		    
		    $.extend(true, settings, options);

			//SI NO SE HA DEFINIDO UNA RUTA DEL EVENTO AJAX, SE MANDA UNA ALERTA.
			if (!settings.url) {
				ISSwal(general_lang.error, general_lang.ruta_no_definido, 'error');
				return false;
			} 
			
			//AGREGAMOS LOS DATOS EXTRAS A LA DATA
			$.each(dataExtra, function(key, value) {
				settings.data[settings.data.length] = {
					 'name': key
					,'value': value
				}
			});

			var promesa = $.ajax({
				url: settings.url
				,method: settings.method
				,data: settings.data
				,dataType: settings.dataType
				,async: settings.async
				,cache: settings.cache
				,contentType: settings.contentType
				,processData: settings.processData
				,beforeSend: function(jqXHR, obj) {
					settings.blockScreen && $('.content-preloader').fadeIn('slow');

					//DESACTIVAMOS EL BOTÓN DEL SUBMIT
					btnSubmit.elDisable();
					settings.beforeSend(jqXHR, obj);
				}
				,success: function(response, textStatus, jqXHR) {
					settings.success(response);
				}
				,error: function(jqXHR, textStatus, errorThrown) {
					// console.log(jqXHR);				
					settings.error(jqXHR);
				}
				,statusCode: {
				    0: function() {
				    	ISSwal(general_lang.error, error_lang.error_0, 'error');
				    },
				    301: function() { //Moved Permanently
				    	ISSwal(general_lang.error, error_lang.error_301, 'error');
				    },
				    400: function() { //Bad Request
				    	ISSwal(general_lang.error, error_lang.error_400, 'error');
				    },
		            401: function() { //Unauthorized
		            	ISSwal({
						  	 text: sessionLang.content
						  	,type: 'info'
				 			,onClose: function() {
	 							location.reload();
				 			}
				 		});
		            },
				    404: function() { //Not Found
				      ISSwal(general_lang.error, error_lang.error_404, 'error');
				    },
				    500: function() { //Internal Server Error
				      ISSwal(general_lang.error, error_lang.error_500, 'error');
				    }
				}
				,complete: function(jqXHR) {
					settings.blockScreen && $('.content-preloader').fadeOut('slow');

					//ACTIVAMOS EL BOTÓN DEL SUBMIT
					btnSubmit.elEnable();
					settings.complete(jqXHR)
				}
			});

			return settings.isPromise ? promesa : this;
		}

		/**
		 * Función principal para realizar el upload de archivos via ajax
		 */
		,uploadFileAjax: function(options) {
			//DATOS QUE SE OBTIENE DEL FORMULARIO
			var $form 		= $(this);
				form_url 	= $form.prop('action'),
				btnSubmit 	= $form.find('[type="submit"], .submit'),
				myDropzone 	= options.dropzone;
				myDZEvents 	= options.DZEvents;
				options.DZEvents = undefined;

			var settings = {
		         url: form_url		//ruta a enviar los datos
        		,blockScreen: true
		    };

		    $.extend(true, settings, options);

			//SI NO SE HA DEFINIDO UNA RUTA DEL EVENTO AJAX, SE MANDA UNA ALERTA.
			if (!settings.url) {
				ISSwal(general_lang.error, general_lang.ruta_no_definido, 'error');
				return false;
			} 

			//agregamos los datos del formulario a enviar
			myDropzone.options.params = $form.serializeObject();

		    myDropzone.on('sending', function(file, xhr, formData) {
				settings.blockScreen && $('.content-preloader').fadeIn('slow');

				//DESACTIVAMOS EL BOTÓN DEL SUBMIT
				btnSubmit.elDisable();
				typeof myDZEvents.sending != 'function' || myDZEvents.sending(file, xhr, formData);
		    });

		    //AGREGAMOS LOS EVENTOS DEL DROPZON
		    DZEvents[myDropzone.element.id] = myDZEvents;

			//agregamos los eventos al finalizar el envío del archivo
		    myDropzone.on('complete', function(file) {
				settings.blockScreen && $('.content-preloader').fadeOut('slow');
				myDropzone.removeFile(true);

				//ACTIVAMOS EL BOTÓN DEL SUBMIT
				btnSubmit.elEnable();
				typeof myDZEvents.complete != 'function' || myDZEvents.complete(file);
		    });

		    myDropzone.options.url = settings.url;
			myDropzone.options.btnSubmit.trigger('click');
			return this;
		}

		/**
		 * Method para realizar una carga dinamica de un select
		 */
		,loadFormSelect: function(options) {
			// Default settings:
		    var settings = {
		    	 url: base_url()
		    	,data: {}
		        ,select: null 	//Identificador del select(id, class, etc)
		        ,content: this 	//Identificador del contenedor del select(id, class, etc) para mostrar el loading
		        ,loading: '<div class="progress secondary-color-dark"><div class="indeterminate"></div></div>'
		    };
		    $.extend(settings, options);

			$('form.tmp').formAjaxSend({
				 url: settings.url
				,data: settings.data
				,dataType: 'html'
				,blockScreen: false
				,beforeSend: function() {
					$(settings.content).html(settings.loading);
				}
				,success: function(response) {
					var contentClass = settings.content.attr('class').replace(/ /g, '.');
					$(settings.content).html(response);
					var select = $(settings.content).find('select');

					if (select.hasClass('select2')) {
						initSelect2('.'+contentClass+' select');
					} else if (select.hasClass('ui')) {
						initSelectUI('.'+contentClass+' select');
					} else {
						select.material_select();
					}
				}
			});

			return this;
		}

		/**
		 * Method para realizar una carga dinamica de una tabla
		 */
		,loadDataTable: function(options) {
			// Default settings:
		    var settings = {
		    	 url: base_url()
		    	,data: {}
		        ,table: IS.initializer.dataTable 	//Identificador de la tabla(id, class, etc)
		        ,content: this 	//Identificador del contenedor de la tabla(id, class, etc)
		        ,blockScreen: true
		        ,success: function() {}
		        ,DTSettings: {}
		    };
		    
		    $.extend(settings, options);
			$('form.tmp').formAjaxSend({
				 url: settings.url
				,data: settings.data
				,dataType: 'html'
				,blockScreen: settings.blockScreen
				,success: function(response) {
					$(settings.content).html(response);
					var table = $(settings.content).find(settings.table);
					table.length && initDataTable(table, settings.DTSettings);
					settings.success();
				}
			});

			return this;
		}
		
		/**
		 * jQuery serializeObject
		 * @copyright 2014, David G. Hong <davidhong.code@gmail.com>
		 * @link https://github.com/hongymagic/jQuery.serializeObject
		 * @license MIT
		 * @version 2.0.3
		 */
		,serializeObject: function() {
			"use strict";
			var a = {};
			$.each(this.serializeArray(), function(b, c) {
				var d = a[c.name];
				"undefined"!= typeof d&&d!==null ? $.isArray(d) ? d.push(c.value) : a[c.name] = [d,c.value] : a[c.name] = c.value

			});

			return a;
		}
		,elDisable: function() {
			return $(this).addClass('disabled').prop('disabled', true);
		}
		,elEnable: function() {
			return $(this).removeClass('disabled').prop('disabled', false);
		}
		
		/**
		 * Aplicacion de animación a un elemento
		 * Inspirado en https://github.com/daneden/animate.css
		 * @param  Object   Opciones para la animación
		 * @example
		 * $('el').animateCSS(animationName, callbackComplete);
		 * OR
		 * $('el').animateCSS({
		 * 		 animation: 'bounce'
		 * 		,animationStart: function() {}
		 * 		,animationEnd: function() {}
		 * });
		 *
		 */
		,animateCSS: function(options) {
			if ('string' == typeof options) {
				options = {
					 animation: arguments[0]
					,animationEnd: arguments[1] || function() {}
				}
			}

			var settings = {
				 el: this
				,animation: 'bounce'
				,animationStart: function() {}
				,animationEnd: function() {}
			}

			$.extend(settings, options);
	        var element = '.this-apply-animation';
	        settings.el.addClass(element.replace('.', ''));

		    const node = document.querySelector(element);
		    node.classList.add('animated', settings.animation);
			
		    function handleAnimationStart() {
		        node.removeEventListener('webkitAnimationEnd', handleAnimationStart); //para Chrome, Safari y Opera
		        node.removeEventListener('animationend', handleAnimationStart);

		        settings.animationStart();
		    }

		    function handleAnimationEnd() {
		        node.classList.remove('animated', 'this-apply-animation', settings.animation);
		        node.removeEventListener('webkitAnimationEnd', handleAnimationEnd); //para Chrome, Safari y Opera
		        node.removeEventListener('animationend', handleAnimationEnd);

		        settings.animationEnd();
		    }

		    //EVENTO AL INICIAR LA ANIMACIÓN
    		node.addEventListener("webkitAnimationStart", handleAnimationStart); //para Chrome, Safari y Opera
    		node.addEventListener('animationstart', handleAnimationStart);

			//EVENTO AL FINALIZAR LA ANIMACIÓN
    		node.addEventListener("webkitAnimationEnd", handleAnimationEnd); //para Chrome, Safari y Opera
    		node.addEventListener('animationend', handleAnimationEnd);
		}
	});
 
}(jQuery));
