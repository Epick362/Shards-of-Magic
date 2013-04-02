<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if($this->tank_auth->is_logged_in()) {
			//redirect('/zone');
		}
	}

	function index()
	{
		$data->content = "Seems like you are the one running this game :)";
		
		$this->template->set('subtitle',  'Admin Panel | Index');
		$this->template->load('template', 'admin/index', $data, '');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */