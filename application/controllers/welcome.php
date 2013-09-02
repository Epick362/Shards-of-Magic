<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	function __construct() {
		parent::__construct();
		if(!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
	}

	function index() {
		$this->load->helper('form');
		$this->characters = $this->db->where('user_id', $this->ion_auth->user()->row()->user_id)->get('characters')->result();

		$this->template->set('subtitle',  'Character Select');
		$this->template->load('template', 'welcome', $this, 'welcome');
	}

	function select() {
		$this->load->library('form_validation');

		$this->form_validation->set_rules('selected-character', 'Character', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			redirect('welcome');
		}
		else
		{
			$this->characters = $this->db->where('user_id', $this->ion_auth->user()->row()->user_id)->get('characters')->result();
			$check = FALSE;
			foreach($this->characters as $character) {
				$arr_character = get_object_vars($character);
				if($arr_character['cid'] == $this->input->post('selected-character')) {
					$check = TRUE;
				}
			}

			if($check) {
				$this->session->set_userdata('character', $this->input->post('selected-character'));
				redirect('character/');
			}else{
				redirect('welcome/');
			}
		}
	}
}

// End of File