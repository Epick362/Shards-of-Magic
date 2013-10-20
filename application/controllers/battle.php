<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Battle extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index() {
		$this->load->library('spell');

		$this->content = "yay";
		// 1 -> Physical
		// 2 -> Fire
		// 3 -> Frost
		// 4 -> Earth
		// 5 -> Void

		// SPELL ID 1 -> DAMAGING SPELL
		$array = serialize(array( "damage" => array(5 => 666, 3 => 16000) ));

		// SPELL ID 2 -> HEALING/SHIELD SPELL
		$array = serialize(array( "heal" => 25,
						"shield" => 150,
						"applyBuffs" => array(2, 3)));
		// SPELL ID 3 -> 

		$this->template->ingame('game/battle/battle', $this, 'battle');
	}
}

// End of Battle File