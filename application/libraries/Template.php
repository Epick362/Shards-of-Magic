<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
		var $template_data = array();
		
		function set($name, $value)
		{
			$this->template_data[$name] = $value;
		}
	
		function load($template = '', $view = '' , $view_data = array(), $css = '', $return = FALSE)
		{               
			$this->CI =& get_instance();
			$this->template_data['css'] = $css;
			if ($this->CI->ion_auth->logged_in()){
				$this->set('navigation', $this->CI->load->view('template/nav-ingame', $view_data, TRUE));
			}else{
				$this->set('navigation', $this->CI->load->view('template/nav', $view_data, TRUE));
			}
			$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));			
			return $this->CI->load->view($template, $this->template_data, $return);
		}

		function ingame($view = '', $view_data = array(), $css = '', $return = FALSE)
		{
			$this->CI =& get_instance();

			if (!$this->CI->ion_auth->logged_in()){
				redirect('auth/login');
			}
			$this->load('template', $view, $view_data, $css, $return);
		}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */