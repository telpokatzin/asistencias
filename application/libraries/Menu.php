<?php /*O3M*/
/**
* Descripcion:	Contruye un arbol de jerarquías partiendo de un array y lo devuelve en lista HTML
* @author:		Oscar Maldonado - O3M
* Creación:		2016-05-20
* Modificación:	2016-05-24-O3M
* @param array =  array('id'>[id_menu],'padre'=>[id_padre],'texto'=>[texto]);
*/
class Menu{
	public $id, $padre, $texto, $separador, $ico, $link;
	public $class_menu, $class_submenu, $tipo;
	private $li_simple, $li_titulo, $li_divisor;
	public $lang;
	public $base_url;

	public function __construct(){
		$this->id			= false;
		$this->padre 		= false;
		$this->texto		= false;
		$this->link			= false;
		$this->separador	= ' - ';
		$this->tipo			= 'tipo';
		#Clases CSS del menu
		$this->data_activates = 'data_activates';
		$this->lang			= false; #Diccionario
		$this->base_url		= base_url(); #URL base
        $this->CI =& get_instance();
	}

	private function get_text_menu($element) {
		if($this->lang) {
			$txt = (array_key_exists($element[$this->texto], $this->lang)) ? $this->lang[$element[$this->texto]] : $element[$this->texto];
		} else {
			$txt = $element[$this->texto];
		}

		return $txt;
	}

	/**
	 * Construye el HTML del menu desktop
	 * @param  array   $array  [description]
	 * @param  integer $parent [description]
	 * @return [type]          [description]
	 */
	function draw_menu_desktop($array = array(), $parent = 0) {
		$menu = '';	
		foreach($array as $element) {
			$submenuIN = '';
			$submenuOUT = '';
			if($element[$this->padre] == $parent) {
				$txt  		= $this->get_text_menu($element);
				$href 		= $this->base_url.$element[$this->link];
				$URL 		= $element[$this->link];
				$uri_string = $this->CI->uri->uri_string();
				$segment 	= $this->CI->uri->segment(1);
				$class 		= ($URL == $segment) ? 'active' : '';

				switch (strtoupper($element[$this->tipo])) {
					case 'SIMPLE':
						$interactiveIN  = '<a class="waves-effect waves-lime" href="'.$href.'" data-key="'.$element['id_menu'].'">';
						break;

					case 'PADRE':
						$class .= 'menu-parent';
						$interactiveIN = '<ul class="collapsible collapsible-accordion">
                							<li>';
                		$txt = '<a class="collapsible-header waves-effect waves-lime">'.$txt.'</a>';
						$submenuIN  = '<div class="collapsible-body"><ul>';
						$submenuOUT = '</ul></div>';
						break;
					case 'HIJO':
						$interactiveIN  = '<a href="'.$href.'">';
						break;
					case 'DATA':
						$pais 				= $this->CI->session->userdata('pais');
						$empresa 			= $this->CI->session->userdata('empresa');
						$email 				= $this->CI->session->userdata('email');
						$id_usuario_nomina 	= $this->CI->session->userdata('id_usuario_nomina');
						$nombre 			= $this->CI->session->userdata('nombre_completo');
						$letras 	= explode(' ', $nombre);
						$iniciales 	= array_map(function($text) {
							$text = trim($text);
							return isset($text[0]) ? $text[0] : '';
						}, $letras);

						$interactiveIN  = '<a href="javascript: void(0)">';
						$class = 'information-user';
						$txt = '<ul>
							<li class="valign-wrapper"><i class="tiny material-icons">flag</i> '.$pais.'</li>
							<li class="valign-wrapper"><i class="tiny material-icons">business</i> '.$empresa.'</li>
							<li class="valign-wrapper" title="'.$nombre.'"><i class="tiny material-icons">person_pin</i> '.implode('', $iniciales).'('.$id_usuario_nomina.')</li>
							<li class="valign-wrapper truncate user-email">
								<i class="tiny material-icons">email</i> '.$email.'
							</li>
						</ul>';
						break;
				}

				//OBTENEMOS LOS PENDIENTES DEL EMPLEADO
				$txt .= $this->CI->get_pendientes($element);
				$interactiveIN .= $txt;
				$interactiveOUT = strtoupper($element[$this->tipo])=='PADRE'? '' :'</a>';
				$menu .= '<li class="'.$class.'">';
				$menu .= $interactiveIN;
				$menu .= $interactiveOUT;
				$menu .= $submenuIN;
				$menu .= $this->draw_menu_desktop($array, $element[$this->id]);
				$menu .= $submenuOUT;
				$menu .= strtoupper($element[$this->tipo])=='PADRE'? '</li></ul></li>' : '</li>';
			}
		}
		return $menu;
	}

	/**
	 * Construye el HTML del menu mobile
	 * @param  array   $array  [description]
	 * @param  integer $parent [description]
	 * @return [type]          [description]
	 */
	public function draw_menu_mobile($array = array(), $parent = 0) {
		$menu = '';	
		$submenuIN = '';
		$submenuOUT = '';

		foreach($array as $element) {
			if($element[$this->padre] == $parent) {

				$txt  = $this->get_text_menu($element);
				$href = $this->base_url.$element[$this->link];

				switch (strtoupper($element[$this->tipo])) {
					case 'SIMPLE':
						$interactiveIN  = '<a href="'.$href.'">';
						$interactiveIN .= $txt;
						$interactiveOUT = '</a>';
						$menu .= '<li>';
						$menu .= $interactiveIN;
						$menu .= $interactiveOUT;
						$menu .= '</li>';
						break;
				}
				
			}
		}
		return $menu;
	}

	public function draw_menu_mobile_submenus($array = array(), $parent = 0) {
		$menu = '';	
		$submenuIN = '';
		$submenuOUT = '';

		foreach($array as $element) {
			$interactiveIN  = '';
			$interactiveOUT = '';
			if($element[$this->padre] == $parent) {
				$txt  = $this->get_text_menu($element);
				$href = $this->base_url.$element[$this->link];

				if(strtoupper($element[$this->tipo] != 'SIMPLE')) {
					switch (strtoupper($element[$this->tipo])) {
						case 'PADRE':
							$clase          = 'class="bold"';
							$interactiveIN  = '<a class="collapsible-header waves-effect">';
							
							$submenuIN  = '<div class="collapsible-body"><ul>';
							$submenuOUT = '</ul></div>';
							$txt .= '<i class="material-icons dropdown-icon right">arrow_drop_down</i>';
							break;
						case 'HIJO':
						case 'DATA':
							$clase          = '';
							$interactiveIN  = '<a href="'.$href.'">';

							$submenuIN  = '';
							$submenuOUT = '';
							break;

					}
					$interactiveIN .= $txt;
					$interactiveOUT = '</a>';

					$menu .= '<li '.$clase.'>';
					$menu .= $interactiveIN;
					$menu .= $interactiveOUT;
					$menu .= $submenuIN;
					$menu .= $this->draw_menu_mobile_submenus($array, $element[$this->id]);
					$menu .= $submenuOUT;
					$menu .= '</li>';
				}
				
			}

		}
		
		return $menu;
	}
}

?>