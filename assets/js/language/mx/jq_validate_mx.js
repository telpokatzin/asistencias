(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		define( ["jquery", "../jquery.validate"], factory );
	} else if (typeof module === "object" && module.exports) {
		module.exports = factory( require( "jquery" ) );
	} else {
		factory( jQuery );
	}
}(function( $ ) {

/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: ES (Spanish; Español)
 */
var ico_error = '<i class="material-icons ico-middle">error_outline</i> ';
$.extend( $.validator.messages, {
	required: ico_error + "Este campo es obligatorio.",
	remote: ico_error + "Por favor, rellena este campo.",
	email: ico_error + "Por favor, escribe una dirección de correo válida.",
	url: ico_error + "Por favor, escribe una URL válida.",
	date: ico_error + "Por favor, escribe una fecha válida.",
	dateISO: ico_error + "Por favor, escribe una fecha (ISO) válida.",
	number: ico_error + "Por favor, escribe un número válido.",
	digits: ico_error + "Por favor, escribe sólo números enteros.",
	creditcard: ico_error + "Por favor, escribe un número de tarjeta válido.",
	equalTo: ico_error + "Por favor, escribe el mismo valor de nuevo.",
	extension: ico_error + "Por favor, escribe un valor con una extensión aceptada.",
	maxlength: $.validator.format( ico_error + "Por favor, no escribas más de {0} caracteres." ),
	minlength: $.validator.format( ico_error + "Por favor, no escribas menos de {0} caracteres." ),
	rangelength: $.validator.format( ico_error + "Por favor, escribe un valor entre {0} y {1} caracteres." ),
	range: $.validator.format( ico_error + "Por favor, selecciona un valor entre {0} y {1}." ),
	max: $.validator.format( ico_error + "Por favor, escribe un valor menor o igual a {0}." ),
	min: $.validator.format( ico_error + "Por favor, escribe un valor mayor o igual a {0}." ),
	nifES: ico_error + "Por favor, escribe un NIF válido.",
	nieES: ico_error + "Por favor, escribe un NIE válido.",
	cifES: ico_error + "Por favor, escribe un CIF válido."
} );
return $;
}));