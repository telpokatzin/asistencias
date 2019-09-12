document.addEventListener("DOMContentLoaded", function() {
    $('.content-preloader').fadeOut('slow');
});

var regexpresspassword = (/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[.$@$!%*?&])/);
jQuery(function($) {
    //SET SYSTEM TIME
    $('#system-time').length && reloj('system-time');

    //SWEET ALERT DEFAULT
    swal.setDefaults({
        cancelButtonText: general_lang.cancelar,
        confirmButtonText: general_lang.aceptar,
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
        buttonsStyling: false
    });

    //SET ERROR RULES
    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.input-group').removeClass('has-success').addClass('has-danger');
            $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
            $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
        },
        success: function(element) {
            $(element).closest('.input-group').removeClass('has-danger').addClass('has-success');
            $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
            $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
        },
        errorPlacement: function(error, element) {
            $(element).closest('.input-group').append(error);
            $(element).closest('.form-group').append(error);
        },
    });

    /*******************************************/
    /****     AUTORESIZE TEXTAREA           ****/
    /*******************************************/
    $('textarea').trigger('autoresize');
});

function reloj(objName){
    /**
     * Muestra hora actual en vivo
     * <body onload="reloj('objName')">
     * <div id="reloj" onload="reloj('reloj')"></div>
    **/ 
    var date = new Date(); 

    var hours   = date.getHours();
    var minutes = date.getMinutes();
    var ampm    = hours >= 12 ? 'pm' : 'am';
    hours   = hours % 12;
    hours   = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    
    var strTime = hours + ':' + minutes + ' ' + ampm;
    document.getElementById(objName).innerHTML=strTime;
    setTimeout("reloj('"+objName+"')",1000);
}

/**
 * Redirecciona a la url recibida
 * @param  {txt} uri recibida
 * @return {void}      
 */
function redirect(url, domain) {
    url = domain ? (domain +'/'+ url) : base_url(url); 
    location.href = url;
}

/**
 * Comprueba si un valor es de tipo entero
 * @return {Boolean}   [devuelve true si es entero, false si no lo es]
 */
function isInt(x) {
   var y = parseInt(x, 10);
   return !isNaN(y) && x == y && x.toString() == y.toString();
}

function initDataTable(element, options) {
    var element = element ? element : IS.initializer.dataTable;
    var Opdefault = {
         dom: 'lBfrtip'
        ,ajax: null
        ,buttons:{
            dom: {
                container: { className: 'dt-buttons' },
                button: { tag: 'a', className: 'btn btn-round btn-fab btn-sm'},
                buttonLiner: ''
            },
            buttons: [
                { extend: 'colvis', columns: ':not(.noVis)', text: '<i class="material-icons">menu</i>', className: 'bg-secondary tooltips hide' },
                { text: '<i class="material-icons">cloud_download</i>', className: 'btn-info download tooltips hide' },
                { text: '<i class="material-icons">add</i>', className: 'btn-success addItem tooltips hide' }
            ]
        }
        ,initComplete: function(settings, json) {
            var config       = settings.oInit;
            var recordsTotal = settings.fnRecordsTotal();
            var toolBar      = $(settings.nTableWrapper).find('.dt-buttons');

            //AGREGAMOS EL BOTON DE DESCARGAR
            if (recordsTotal && config.btnDownload) {
                toolBar.find('.download').attr('data-placement', 'auto')
                    .attr('data-title', general_lang.descargar).removeClass('hide');
            }

            //AGREGAMOS EL BOTON DE NUEVO
            if (config.btnAdd) {
                toolBar.find('.addItem').attr('data-placement', 'auto')
                    .attr('data-title', general_lang.nuevo).removeClass('hide');
            }

            //AGREGAMOS EL BOTON DE COLVIS
            if (config.btnColVis) {
                toolBar.find('.buttons-colvis').attr('data-placement', 'auto')
                    .attr('data-title', general_lang.showHideCols).removeClass('hide');
            }

            //ELIMINAMOS BOTONES NO NECESARIOS
            toolBar.find('a.hide').remove();
            console.log($(settings.nTableWrapper).find('.tooltips'))
            initTooltips($(settings.nTableWrapper).find('.tooltips'));
        }
        ,language: {
            url: base_url('assets/js/language/'+ language +'/datatables_'+ language +'.json')
        }
        ,lengthMenu: [
          [10, 25, 50, -1],
          [10, 25, 50, "All"]
        ]
        ,responsive: true
        ,searching:  true
        ,scrollX:    false
        ,details:    true
        ,iDisplayLength: 10
        ,bFilter: false
        ,ordering: true
        ,processing: false
        ,btnDownload: false
        ,bntDownloadClass: ''
        ,btnAdd: false
        ,btnAddClass: ''
    };

    options = options || {};
    var settings = $.extend({}, Opdefault, options);
    if (settings.ajax !== null) {
        settings.ajax.method = 'post';
        settings.ajax.dataType = 'json';
    }
   $(element).each(function() {
        var tbl     = $(this);
        var idTable = tbl.prop('id')

        var table       = tbl.DataTable(settings);
        var key         = table.table().node().id;
        IS.init.dataTable[key]   = table;
    });
}

/**
 * función para crear una etiqueta <a>
 * @param  String   url      ruta a enlazar
 * @param  Strinh   target   _blank|_self|_parent|_top
 * @param  Boolean  isDownload Bandera para ver si es un archivo descargable
 * @return String Vooid
 */
function gotoLink(url, target, isDownload) {
    url         = url ? url : base_url();
    target      = target ? target : '_self';
    var wOpen   = window.open(url);
    var isBlocked = (wOpen == null || typeof(wOpen)=='undefined');

    if (isBlocked) {
        var randomId = Math.floor((Math.random() * 100000));
        var elLink   = $("<a>", {href: url, target: target, id: randomId, text: 'click aquí'}).prop('outerHTML');
        var msg1     = '<p>Su navegador ha bloqueado el redireccionamiento automático, por favor habilita las ventanas emergentes y redireccionamientos. <br> <br>\
                        Haz '+elLink+' para el redireccionamiento manual.</p>';
        var msg2     = '<p>Su navegador ha bloqueado la descarga del archivo, por favor habilita las ventanas emergentes. <br> <br>\
                        Haz '+elLink+' para la descarga manual.</p>';

        swal({
             title: general_lang.alerta
            ,type: 'info'
            ,allowOutsideClick: false
            ,allowEscapeKey: false
            ,allowEnterKey: false
            ,showConfirmButton: false
            ,showCloseButton: true
            ,html: isDownload ? msg2 : msg1
        }).then(function(){}, function(){});

        jQuery('#'+ randomId).click(function(){
            swal.close();
        });
    }
}

/**
 * función para crear el formulario del envío de datos mediante POST
 * @param Array/Object data datos a enviar
 * @param String url ruta a enviar los datos
 * @param String target _blank|_self|_parent|_top|framename
 * @param boolean freturn true/false hace el submit del form creado, si no, retorna el objeto creado
 */
function form_send(data, url, target, freturn) {
    target = target ? target : '_self'
    var $form = $("<form>", {method:'post', action: url, id:'form-submit', target: target, class:'hide'});
    $.each(data, function(name, value){
        var input = $('<input>', {type: 'text', 'name':name, 'value': value});
        $form.append(input);
    });

    if ( freturn) {
        return $form;
    }

    var submit = $('<input>', {type:'submit', value:'submit'});
    $form.append(submit);
    $form.appendTo('body').submit();
    $form.remove();
}

/**
 * Función para verificar si es Json
 * @param str String a validar
 **/
function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

/**
 * Función para verificar si el STRING es parseable
 * @param  String  str string a verificar
 * @return boolean
 */
function isJsonStr(str) {
    try {
        var parsedStr = JSON.parse(str);
    } catch (e) {
        return false;
    }

    return typeof parsedStr == 'object'
}

function initDatePicker(el, options) {
    var selector = el ? el : IS.initializer.datepicker;
    var Opdefault = {
         closeOnSelect: true
    };

    options = options ? options : {};
    $.extend(Opdefault, options);
    var elems = document.querySelectorAll(selector);
    $.each(elems, function(index, el) {
        var key = $(el).prop('id');
        IS.init.datepicker[key] = $(el).pickadate(Opdefault);
        $(el).on('mousedown',function(event){ event.preventDefault(); });
    });
}

function initTimePicker(el, options) {
    var selector = el ? el : IS.initializer.timepicker;
    var elems = document.querySelectorAll(selector);
    var Opdefault = {
         autoclose: true
        ,twelvehour: true
        ,donetext: jQuery.fn.pickadate.defaults.done
        ,cleartext: jQuery.fn.pickadate.defaults.clear
        ,canceltext: jQuery.fn.pickadate.defaults.close
        ,formatSubmit: 'HH:i'
        ,showAMPM: false
    };

    options = options ? options : {};
    $.extend(Opdefault, options);
    $.each(elems, function(index, el) {
        var key = $(el).prop('id');
        IS.init.timepicker[key] = $(el).pickatime(Opdefault)
            .on('mousedown',function(event){
                event.preventDefault();
            });
    });
}

function initCollapsible(el, options) {
    var selector = el ? el : IS.initializer.collapsible;
    var elems = document.querySelectorAll(selector);
    var Opdefault = {};

    options = options ? options : {};
    $.extend(Opdefault, options);
    
    $(elems).collapsible();
}

function initDropdown(el, options) {
    var selector = el ? el : IS.initializer.dropdown;
    var elems = document.querySelectorAll(selector);
    var Opdefault = {};

    options = options ? options : {};
    $.extend(Opdefault, options);
    
    $(elems).dropdown(Opdefault);
}

function initModal(el, options) {
    var options = options || {};
    var selector = el ? el : IS.initializer.modal;
    var elems = document.querySelectorAll('#content-modals ' + selector);
    var Opdefault = {
         keyboard: true
        ,show: true
    };

    $.extend(Opdefault, options);

    $(elems).modal(Opdefault);

    $(elems)

    //onOpenStart
    .on('show.bs.modal', function() {
        if(options.onOpenStart != undefined && options.onOpenStart.constructor == Function) options.onOpenStart();
    })
    
    //onOpenEnd
    .on('shown.bs.modal', function() {
        $('#content-modals .bmd-label-floating + input').bmdText();
        if(options.onOpenEnd != undefined && options.onOpenEnd.constructor == Function) options.onOpenEnd();
    })
    
    //onCloseStart
    .on('hide.bs.modal', function() {
        if(options.onCloseStart != undefined && options.onCloseStart.constructor == Function) options.onCloseStart();
    })
    
    //Event onCloseEnd
    .on('hidden.bs.modal', function() {
        if(options.onCloseEnd != undefined && options.onCloseEnd.constructor == Function) options.onCloseEnd();
        if ($('#content-modals table').length) {
            var key = $('#content-modals table').attr('id');
            $('#'+key).DataTable().destroy();
        }
        
        $('#content-modals').html('');
    });
}

function initSelect2(el, options) {
    var selector = el ? el : IS.initializer.formSelect2;
    var elems = document.querySelectorAll(selector);
    var Opdefault = {
         selectOnClose: true
        ,minimumResultsForSearch: 10
    };

    options = options ? options : {};
    $.extend(Opdefault, options);
    $.each(elems, function(index, el) {
        var key = $(el).prop('id');
        IS.init.select2[key] = $(el).select2(Opdefault);
    });
}

function initSelectUI(el, options) {
    var selector = el ? el : IS.initializer.formSelectUI;
    var elems = document.querySelectorAll(selector);
    var Opdefault = {
         buttonWidth: 'auto'
        ,menuWidth: '100%'
        // ,menuHeight: '200px'
    };

    options = options ? options : {};
    $.extend(Opdefault, options);
    $.each(elems, function(index, el) {
        var key     = $(el).prop('id');
        var options = $(el).find('option').length;

        if (options >=5) {
            IS.init.selectUI[key] = $(el).multiselect(Opdefault).multiselectfilter();
        } else {
            IS.init.selectUI[key] = $(el).multiselect(Opdefault)
        }

    });
}

function initTooltips(el, options) {
    if (el.constructor === String) {
        var selector = el ? el : IS.initializer.tooltip;
        var elems = document.querySelectorAll(selector);
    } else elems = el;

    var Opdefault = {
        position: 'top'
    };

    options = options ? options : {};
    $.extend(Opdefault, options);

    $(elems).each(function(index, el) {
        var settings = $.extend({}, Opdefault, options, $(this).data());
        
        $(this).tooltip(settings);
    });
}

function showNotify(message, type, icon) {
    $.notify({
        icon: icon,
        message: message
    }, {
        type: type,
        placement: {
            from: 'top',
            align: 'right'
        }
    });
}