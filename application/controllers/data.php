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

	header('Content-type: application/json');
	?>
	{
			"health": "<?php echo $return['health'];?>",
			"health_max": "<?php echo $data->health_max;?>",
			"mana": "<?php echo $return['mana'];?>",
			"mana_max": "<?php echo $data->mana_max;?>"
	}
	<?php
		exit(0);
	}
}