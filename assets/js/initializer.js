(function($) {
    IS = { 
         initializer: {
             autocomplete: '.autocomplete:not(.no-autoinit)'
            ,carousel: '.carousel:not(.no-autoinit)'
            ,chips: '.chips:not(.no-autoinit)'
            ,collapsible: '.collapsible:not(.no-autoinit)'
            ,dataTable: 'table:not(.no-autoinit)'
            ,datepicker: '.datepicker:not(.no-autoinit)'
            ,dropdown: '.dropdown-trigger:not(.no-autoinit)'
            ,formSelect: 'select:not(.no-autoinit):not(.select2):not(.ui)'
            ,formSelect2: 'select.select2:not(.no-autoinit)'
            ,formSelectUI: 'select.ui:not(.no-autoinit)'
            ,floatingActionButton: '.fixed-action-btn:not(.no-autoinit)'
            ,materialbox: '.materialboxed:not(.no-autoinit)'
            ,modal: '.modal:not(.no-autoinit)'
            ,parallax: '.parallax:not(.no-autoinit)'
            ,pushpin: '.pushpin:not(.no-autoinit)'
            ,rangeslider: '.rangeslider:not(.no-autoinit)'
            ,scrollSpy: '.scrollspy:not(.no-autoinit)'
            ,sidenav: '.button-collapse:not(.no-autoinit)'
            ,tabs: '.tabs:not(.no-autoinit)'
            ,tapTarget: '.tap-target:not(.no-autoinit)'
            ,timepicker: '.timepicker:not(.no-autoinit)'
            ,tooltip: '.tooltipped:not(.no-autoinit)'
            ,modal: '.modal'
        }
        ,init: {
             dataTable: {}
            ,datepicker: {}
            ,timepicker: {}
            ,select2: {}
            ,selectUI: {}
        }
    };

    /*******************************************/
    /****              INIT DATATABLE       ****/
    /*******************************************/
    if($(IS.initializer.dataTable).length) {
        initDataTable(IS.initializer.dataTable);
    }

    /*******************************************/
    /****   INIT SELECT-MATERIALIZECSS      ****/
    /*******************************************/
    if($(IS.initializer.formSelect).length) {
        $(IS.initializer.formSelect).material_select();
    }

    /*******************************************/
    /****         INIT SELECT-2             ****/
    /*******************************************/
    if($(IS.initializer.formSelect2).length) {
        initSelect2(IS.initializer.formSelect2);
    }

    /*******************************************/
    /****           INIT SELECT UI          ****/
    /*******************************************/
    if($(IS.initializer.formSelectUI).length) {
        initSelectUI(IS.initializer.formSelectUI);
    }

    /*******************************************/
    /****         INIT TABS                 ****/
    /*******************************************/
    if($(IS.initializer.tabs).length) {
        $(IS.initializer.tabs).tabs();
    }

    /*******************************************/
    /****         INIT DATEPICKER           ****/
    /*******************************************/
    if($(IS.initializer.datepicker).length) {
        initDatePicker(IS.initializer.datepicker);
    }

    /*******************************************/
    /****         INIT TIMEPICKER           ****/
    /*******************************************/
    if($(IS.initializer.timepicker).length) {
        initTimePicker(IS.initializer.timepicker);
    }

    /*******************************************/
    /****         INIT COLLAPSIBLE          ****/
    /*******************************************/
    if($(IS.initializer.collapsible).length) {
        initCollapsible(IS.initializer.collapsible);
    }

    /*******************************************/
    /****         INIT DROPDOWN          ****/
    /*******************************************/
    if($(IS.initializer.dropdown).length) {
        initDropdown(IS.initializer.dropdown);
    }

    /*******************************************/
    /****         INIT SIDENAV              ****/
    /*******************************************/
    if($(IS.initializer.sidenav).length) {
        var elems = document.querySelectorAll(IS.initializer.sidenav);
        $(elems).sideNav();
    }

    /*******************************************/
    /****   INIT RANGE SLIDER               ****/
    /*******************************************/
    if($(IS.initializer.rangeslider).length) {
        initRangeSlider(IS.initializer.rangeslider);
    }

    /*******************************************/
    /****       INIT TOOLTIPED              ****/
    /*******************************************/
    setTimeout(function() {
        if($(IS.initializer.tooltip).length) {
            initTooltipped(IS.initializer.tooltip);
        }
    }, 1000);

})(jQuery);