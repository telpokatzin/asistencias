/**
 * Eliminación de caracteres de un String
 * @url https://www.sitepoint.com/trimming-strings-in-javascript/
 */

/**Elimina los caracteres del comienzo de la cadena**/
String.prototype.trimLeft = function(charlist) {
  if (charlist === undefined)
    charlist = "\s";

  return this.replace(new RegExp("^[" + charlist + "]+"), "");
};

/**Elimina los caracteres del final de la cadena**/
String.prototype.trimRight = function(charlist) {
  if (charlist === undefined)
    charlist = "\s";

  return this.replace(new RegExp("[" + charlist + "]+$"), "");
};

/**Elimina caracteres de ambos extremos**/
/**se renombró la función ya que se genera error al llamar swal**/
String.prototype.trim = function(charlist) {
  return this.trimLeft(charlist).trimRight(charlist);
};