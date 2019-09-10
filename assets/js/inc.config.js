/**
 * Funcion para obtener la URL del sistema
 * @param subdirectory String un subdirectorio del sitio
 * @return base_url String URL del sitio
 */
function base_url(subdirectory) {
    var folder      = 'compilaideas',
        pathname    = folder ? location.pathname.split(folder+'/') : [''];
        pathname    = pathname[0].trim('/');
        folder      = (folder && pathname) ? '/' + folder : folder;
        subdirectory= (subdirectory && folder) ? '/' + subdirectory : subdirectory;

    return location.origin +'/'+ pathname + folder + subdirectory;
}