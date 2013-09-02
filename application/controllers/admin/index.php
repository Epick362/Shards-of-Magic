<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->content = "Seems like you are the one running this game :)";
		
		$this->template->ingame('admin/index', $this);
	}
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */