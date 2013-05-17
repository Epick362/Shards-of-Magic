<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$query = $this->db->query("SELECT * FROM posts ORDER BY id ASC LIMIT 5");
		$this->content = $query->result();
		
		$this->template->set('subtitle',  'Home');
		$this->template->load('template', 'index', $this, 'index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */