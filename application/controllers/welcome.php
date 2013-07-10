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
		$this->template->load('template', 'welcome', $this);
	}

	function select() {
		$this->load->library('form_validation');

		$this->form_validation->set_rules('dropdown', 'Dropdown', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			redirect('welcome');
		}
		else
		{
			$this->session->set_userdata('character', $this->input->post('dropdown'));
			redirect('character/');
		}
	}
}

// End of File