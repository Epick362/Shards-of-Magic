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
		$query = $this->db->query("SELECT * FROM posts ORDER BY id ASC LIMIT 5");
		$data->content = $query->result();
		
		$this->template->set('subtitle',  'Home');
		$this->template->load('template', 'index', $data, 'index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */