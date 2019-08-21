<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class IS_Exception extends Exception {

    public function __construct($message='', $title='', $typeMsg='', $class='', $code = 0, Exception $previous = null) {
        //SET DATA TO ALERT
        $this->title    = $title;
        $this->typeMsg  = $typeMsg;
        $this->class    = $class;
        
        //SET DATA EXCEPTION
        $message = $message ? $message : lang('general_throw_exception');
        parent::__construct($message, $code, $previous);
        log_message('debug', 'IS_Exception - ' . $this->getTraceAsString());
    }

    // representación de cadena personalizada del objeto
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * Retornamos ¿Qué tipo de mensaje es?
     * Esto servira para mostrarlo el un alerta, ya sea sweet|toats|etc...
     * @return String $typeMsg
     */
    public function getTypeMessage() {
        return $this->typeMsg ? $this->typeMsg: 'error';
    }

    /**
     * Obtenemos el titulo del mensaje para la alerta
     * @return String $title
     */
    public function getTitle() {
        return $this->title ? $this->title : lang('general_error');
    }

    public function getClass() {
        return $this->class;
    }
}

/* End of file IS_Exception.php */
/* Location: ./application/core/IS_Exception.php */