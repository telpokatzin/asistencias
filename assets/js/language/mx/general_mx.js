var ico_error = '<i class="material-icons ico-middle">error_outline</i> ';
var general_lang = {
	sistema: 'Sistema',
	menu: 'Menú',
	aceptar: 'Aceptar',
	cancelar: 'Cancelar',
	current_step: 'Step',
	finish: 'Finalizar',
	cerrar: 'Cerrar',
	si: 'Sí',
	no: 'No',
	siguiente: 'Siguiente',
	atras: 'Atras',
	volver: 'Volver',
	buscar: 'Búscar',
	cargando: 'Cargando...',
	iniciar_sesion: 'Iniciar sesión',
	cerrar_sesion: 'Cerrar sesión',
	salir: 'Salir',
	guardar: 'Guardar',
	guardando: 'Guardando...',
	success: '¡Exito!',
	error: '¡Error!',
	informacion: '¡Información!',
	success_edit: '¡Exito editando el registro!',
	error_edit:   '¡Error editando el registro!',
	success_create: '¡Exito guardando el registro!',
	error_create: '¡Error guardando el registro!',
	success_delete: '¡Exito eliminando el registro!',
	error_delete: '¡Error eliminando el registro!',
	cancel_delete: '¡Se ha cancelado la eliminación!',
	esta_seguro: '¿Está seguro?',
	delete_row: '¡Está a punto de eliminar el registro!',
	si_borralo: 'Sí, hazlo',
	error: '¡Error!',
	alerta: '¡Alerta!',
	nuevo: 'Nuevo',
	descargar: 'Descargar',
	descargar_nominativo: 'Descargar nominativo',
	empresa_required: 'Selecciona una empresa para generar la gráfica',
	wait_moment_report: 'Espere un momento, este reporte puede tardar varios minutos.',
	wait_moment: '<div><h5 class="center-align">Cargando...</h5></div>',
	sol_finalizado: '¡Solicitud finalizado!',
	indicaciones_finalizado: 'Para generar nuevamente el documento final, ingrese al módulo de empleados.'
};

var sessionLang = {
	title: 'La sesión ha caducado',
	content: 'La sesión ha caducado, por favor, vuelva a iniciar sesión para continuar usando el sistema'
};


var loginLang = {
	usuario_required: 	ico_error+"Ingrese su usuario", 
	clave_required: 	ico_error+"Ingrese su clave",
	clave_wrong: 		'Su usuario y/o contraseña son incorrectas',
	recuperar_pass: 	'Recuperar Contraseña',
	ingresa_correo: 	'Por favor ingrese su correo electrónico',
	restablecer_password: 'Restablecer Contraseña',
	restablecer_password_txt: 'Se ha restablecido su Contraseña',
	restablecer_password_txt_error: '¡Ocurrio un error Por favor intentelo más tarde!'
};

// JQUERY VALIDATOR
var validator_lang = {
	ponderacion_max: ico_error + "Ingrese un valor menor que {0}%.",
	ponderacion_min: ico_error + "Ingrese un valor mayor que {0}%.",
	ponderacion_range: ico_error + "Ingrese un valor entre {0}% y {1}%.",
	anio_required: 'Seleccione el año',
	mes_required: 'Seleccione el mes'
};

var xhrStatusLang = {
 	error500: 'Error 500',
 	error404: 'Error 404',
 	error500Msg: '¡Error procesando la petición!. Por favor inténtelo más tarde',
 	error404Msg: '¡No encontrado!',
 	errorDefault: '¡Hubo un error!'
};

var error_lang = {
	error_0: 'Se ha encontrado un error interno. Por favor contacte con el administrador del sistema',
	error_301: '!Sitio fuera de servicio temporalmente!, intentelo más tarde.',
	error_400: '!Solicitud Incorrecta!, consulte con el administrador del sistema.',
	error_404: '!Sitio no encontrado!, consulte con el administrador del sistema.',
	error_500: '¡Ocurrio un error porcesando la petición!, intentelo más tarde' 
};