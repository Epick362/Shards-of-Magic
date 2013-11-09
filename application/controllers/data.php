<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Data extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		exit('No direct script access allowed');
	}

	function regenerate() {
		$cid = $this->session->userdata('character');
		$data = $this->db->select('cid, health, health_max, mana, mana_max, last_update, level')->where('cid', $cid)->get('characters')->row();
		$return = $this->active->regenerateResources( $data );

		$response = array('health' => $return['health'], 'health_max' => $data->health_max, 'mana' => $return['mana'], 'mana_max' => $data->mana_max);
		echo json_encode($response);
	}
}