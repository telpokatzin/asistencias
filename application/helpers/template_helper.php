<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('dropdown')) {
    function dropdown($params) {
        $data       = (isset($params['data']))?$params['data']:'';
        $name       = (isset($params['name']))?$params['name']:'';
        $id         = (isset($params['id']))?$params['id'] : $name;
        $selected   = (isset($params['selected']))?explode(',',$params['selected']):'';
        $value      = (isset($params['value']))?$params['value']:FALSE;
        $text       = (isset($params['text']))?$params['text']:'';
        $class      = (isset($params['class']))?$params['class']:'';
        $disabled   = (isset($params['disabled']))? 'disabled':'';
        $readonly   = (isset($params['readonly']))?'readonly':'';
        $multiple   = (isset($params['multiple']))?'multiple':'';
        $add_leyenda= (isset($params['add_leyenda']))?$params['add_leyenda']:TRUE;

        $leyenda            = (array_key_exists('leyenda' ,$params))?$params['leyenda']: lang('general_select_option');
        $disabled_leyenda   = (isset($params['disabled_leyenda'])) ? 'disabled' : '';
        $attr_data          = '';
        $multiple ? $name   .= '[]' : FALSE;

        //debug($attr_data);
        $options = '';
        if(is_array($data)){    
            foreach ($data as $key => $values) {
                $option_selected='';
                if($selected){  
                        $option_selected = (in_array($values[$value],$selected))?'selected':'';
                        $options.='<option value="'.$values[$value].'" '.$option_selected.'>'.($values[$text]).'</option>';            
                }else{
                    $options.='<option value="'.$values[$value].'"'.$option_selected.'>'.($values[$text]).'</option>';    
                }       
            }
        }

        $optionDefault = $add_leyenda ?"<option value='' $disabled_leyenda selected>$leyenda</option>" : '';
        $select = "<select name='$name' id='$id' class='$class' $attr_data $multiple $disabled $readonly>
                $optionDefault
                $options
            </select>";

        return $select;
    }        
}