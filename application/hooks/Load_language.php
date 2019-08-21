<?php if (!defined( 'BASEPATH')) exit('No direct script access allowed'); 
class Load_language {

    public function initialize() {
        $CI =& get_instance();
        $default_lang   = config_item('language');
        $lang_files     = array(
             'db'
            ,'mail'
            ,'menu'
            ,'error'
            ,'login'
            ,'general'
            ,'empresas'
        );
        $lang_folder    = $CI->session->userdata('language');
        $lang_folder    = ($lang_folder ? $lang_folder : $default_lang);
        $CI->config->set_item('language', $lang_folder);

        $CI->lang->load($lang_files);
    }
}