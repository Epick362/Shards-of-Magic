<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ladder extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function page($page = 0)
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url()."/ladder/page/";
		$config['total_rows'] = $this->db->get('characters')->num_rows();
		$config['per_page'] = 10;
		if(!$page) $page = 0;

		$this->pagination->initialize($config); 
		$this->characters_ladder = $this->db->query("SELECT * FROM characters ORDER BY level DESC LIMIT ".$page.", ".$config['per_page']."")->result();

		foreach($this->characters_ladder as &$character) {
			$character->class_data = $this->core->getClassData($character->class);
			$character->world_data->zone->name = $this->core->getZoneName($character->map, $character->zone);
			$character->guild_data = $this->characters->getGuildData($character->cid);
		}

		$this->template->set('subtitle',  'Ladder');
		$this->template->ingame('game/ladder/ladder', $this, 'ladder');
	}
	
}