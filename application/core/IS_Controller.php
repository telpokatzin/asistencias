<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IS_Controller extends MX_Controller {

    public function __construct() {
        parent::__construct();

        /** Verificamos la autenticacion del usuario */
        self::check_userAuthentication();
        self::encryption_initialize();
        !isset($_POST['dataEncription']) OR $this->decripterData();

        //FORMATO DE FECHAS
        define('DATEFORMAT', get_var('dateFormat'));
        define('TIMEFORMAT', get_var('timeFormat'));
        define('TIMESTAMP', get_var('timeStamp'));

        // Logs
        if(get_var('log_onoff') && $this->session->userdata('is_logged')) {
            LogTxt($this->session->get_userdata(), LOCALPATH.get_var('log_path_access'));
        }
    }

    private function encryption_initialize() {
        $this->encryption->initialize(
            array(
                 'cipher'   => 'aes-256'
                ,'mode'     => 'cbc'
                ,'key'      => bin2hex(get_var('custom_key_IS'))
            )
        );
    }

    /**
     * Descencriptamos los datos y lo pasamos al $_POST
     * @return Void String
     */
    protected function decripterData() {
        $JSONString = $this->encryption->decrypt($this->input->post('dataEncription'));
        $data = json_decode($JSONString, TRUE);
        unset($_POST['dataEncription']);

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $_POST[$key] = $value;
            }
        }

        return TRUE;
    }

    private function check_userAuthentication() {
        $uriLogin   = array('', 'login', 'login/index');
        $uri        = $this->uri->uri_string();

        if (!$this->input->is_ajax_request() AND strpos($uri, 'apis/') === FALSE) {
            //SI YA ESTA AUTENTICADO, REDIRECCIONAMOS A INICIO
            if ($this->session->userdata('isLogged')) {
                if (in_array($uri, $uriLogin)) {
                    redirect(base_url('inicio'));
                }
            
            //PEDIMOS LA AUTENTICACION DEL USUARIO
            } elseif (!in_array($uri, $uriLogin) && !strstr($uri, 'pruebas')) {
                echo modules::run('global/login/index', TRUE);
                die();
            }
        }
    }

    /**
    * unifica las vistas header & footer con las vistas parseadas
    * de la seccion seleccionada
    * @param string $view
    * @param array $data
    * @param array $Fincludes Incluir archivos al sistema JS|CSS ...
    * @param string $ext
    * @return void
    */
    public function load_view($view=FALSE, $data=array(), $Fincludes=array(), $folder='main', $ext='.html') {
        $ext             = ($ext!='.html')? '' : $ext;
        $includes        = self::get_file_includes($Fincludes);
        $includes_header = $includes['header'];
        $includes_footer = $includes['footer'];
        $dataPage        = array('page_content' => '');
        $parse           = array();

        // INCLUDES HEADER Y VENDOR
        // SE CARGAN EL CORE Y LOS PLUGINS DEL SISTEMA ADEMAS DE LOS JS Y CSS EXTRAS
        $parse['system-lang'] = json_encode($this->lang->language);
        $dataPage['includes_header'] = $this->parser_view("$folder/includes-header", $parse, TRUE, $includes_header);
        $dataPage['includes_vendor'] = $this->parser_view("$folder/includes-vendor" , $parse);
        //FIN HEADER Y VENDOR

        //CONSTRUCCION DEL MENU
        // $menu                           = $this->user_menu();
        // $parse['MENU_DESKTOP']          = $menu['MENU_DESKTOP'];
        // $parse['MENU_MOBILE']           = $menu['MENU_MOBILE'];
        // $parse['MENU_MOBILE_SUBMENUS']  = $menu['MENU_MOBILE_SUBMENUS'];
        // debug($parse['MENU_MOBILE_SUBMENUS']);
         
        $dataPage['sidebar-left']   = $this->parser_view("$folder/sidebar-left", $parse);

        //SE CARGA EL CONTENIDO DE LA PAGINA
        if($view) {
            $parse['section'] = isset($data['section']) ? $data['section'] : '';
            $dataContent['page-header'] = $this->parser_view("$folder/page-header", $parse);
            $dataContent['page-footer'] = $this->parser_view("$folder/page-footer",$parse); 
            $dataContent['content']     = $this->parser_view($view, $data);
            $dataContent['titulo']      = isset($dat['titulo'])     ? $data['titulo'] : '';
            $dataContent['subtitulo']   = isset($data['subtitulo']) ? $data['subtitulo'] : '';
            $dataPage['page_content'] = $this->parser_view("$folder/page-content", $dataContent);
        }

        // FOOTER
        $dataPage['includes_footer'] = $this->parser_view("$folder/includes-footer", $parse, TRUE, $includes_footer);
        
        $dataPage['body_class'] = isset($data['body_class']) ? $data['body_class'] : '';
        $dataPage['PRELOADER']  = $this->parser_view("$folder/preloader", array());

        $this->parser_view("$folder/page-main", $dataPage, FALSE);
    }

   /**
    * parseamos la vista HTML y retorma el resultado
    * @param string $view
    * @param array $data
    * @param boolean $autoload
    * @param array $Fincludes Incluir archivos al sistema JS|CSS ...
    * @param string $ext
    * @return void
    */
    public function parser_view($view, $data=array(), $return=TRUE ,$Fincludes=array() ,$ext='.html') {
        $ext      = ($ext!='.html') ? '': $ext;
        $includes = $this->load_scripts($Fincludes);

        $data['base_url']       = base_url();
        $data['URLPATH']        = URLPATH;
        $data['system_date']    = timestamp_complete();
        $data['system_time']    = date('g:i a');
        $data['anio']           = date('Y');
        $data['nombre_completo']= $this->session->userdata('nombre_completo');
        $data['general_logout'] = lang('general_logout');
        $data['language']       = config_item('language');
        $data['inc_js']         = $includes['js'];
        $data['inc_css']        = $includes['css'];

        //GLOBAL
        $data['SITETITLE']      = get_var('site_title');
        $data['APPTITLE']       = get_var('app_title');
        $data['TEMPLATE_PATH']  = base_url(get_var('path_template'));
        $data['VENDOR_PATH']    = base_url(get_var('path_vendor'));
        $data['FONTS_PATH']     = base_url(get_var('path_fonts'));
        $data['IMG_PATH']       = base_url(get_var('path_img'));
        $data['CSS_PATH']       = base_url(get_var('path_css'));
        $data['JS_PATH']        = base_url(get_var('path_js'));
        $data['DOCS_PATH']      = base_url(get_var("path_docs"));

        $template = $this->parser->parse($view.$ext, $data, TRUE);

        if ($return) return $template;

        echo $template;
    }

    private function get_file_includes(array $files) {
        $includes_header = array();
        $includes_footer = array();

        //OBTENEMOS LOS ARCHIVOS A INGRESAS EN EL HEADER
        if (isset($files['header'])) {
            $includes_header = $files['header'];
            unset($files['header']);
        }

        //OBTENEMOS LOS ARCHIVOS A INGRESAS EN EL FOOTER
        if (isset($files['footer'])) {
            $includes_footer = $files['footer'];
            unset($files['footer']);
        }

        /**
         * Si los archivos no estan definidos donde cargarlos [header|footer]
         * lo cargamos en el footer
         */
        if(count($files)) $includes_footer = array_merge($includes_footer, $files);

        return array(
             'header' => $includes_header
            ,'footer' => $includes_footer
        );
    }

    /**
     * Configuración para la construccioón del menú de acuerdo al perfil del usuario
     * @param  string $ids_menu [description]
     * @return [type]            [description]
     */
    public function user_menu() {
        $this->load->model('Menu_model', 'db_menu');
        $sqlData = array(
             'id_llave_global'  => $this->session->userdata('id_llave_global')
            ,'externo'          => $this->session->userdata('externo')
            ,'id_sistema'       => config_item('id_sistema')
        );

        $dataMenu             = $this->db_menu->get_menu($sqlData);
        $this->menu->id       = 'id_menu';
        $this->menu->padre    = 'id_padre';
        $this->menu->link     = 'link';
        $this->menu->tipo     = 'tipo';
        $this->menu->texto    = 'texto';
        $this->menu->base_url = URLPATH;      
        $this->menu->lang     = $this->lang->language;
        $menu['MENU_DESKTOP'] = $this->menu->draw_menu_desktop($dataMenu);
        // $menu['MENU_MOBILE']  = $this->menu->draw_menu_mobile($dataMenu);
        // $menu['MENU_MOBILE_SUBMENUS']  = $this->menu->draw_menu_mobile_submenus($dataMenu);               
        return $menu;
    }

    /**
    * Carga archivos js & css en el header
    * @param array $data
    * @return array
    */
    public function load_scripts(array $data) {
        $VERSION    = get_var('version');
        $JS_PATH    = rtrim(get_var("path_js", ''), '/');
        $CSS_PATH   = rtrim(get_var("path_css", ''), '/');
        $js         = '';
        $css        = '';

        //CARGA DE ARCHIVO JS
        if (isset($data['js']) AND is_array($data['js'])) {
            foreach ($data['js'] as $fileData) {
                $filename = isset($fileData['name']) ? trim($fileData['name'], '/') : '';
                $filePath = isset($fileData['dirname']) ? rtrim($fileData['dirname'], '/') : '';
                $filePath = rtrim(base_url("$JS_PATH/$filePath"), '/');

                $js.="<script type='text/javascript' src='$filePath/$filename.js?V=$VERSION'></script>";
            }
        }

        //CARGA DE ARCHIVO CSS
        if (isset($data['css']) AND is_array($data['css'])) {
            foreach ($data['css'] as $fileData) {
                $filename = isset($fileData['name']) ? trim($fileData['name'], '/') : '';
                $filePath = isset($fileData['dirname']) ? rtrim($fileData['dirname'], '/') : '';
                $filePath = rtrim(base_url("$CSS_PATH/$filePath"), '/');

                $js.="<link rel='stylesheet' type='text/css' href='$filePath/$filename.css?V=$VERSION'/>";
            }
        }

       return array(
             'js'  => $js
            ,'css' => $css
       );
    }

}

/* End of file IS_Controller.php */
/* Location: ./application/core/IS_Controller.php */