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
						"applyBuffs" => array(5)));

		// SPELL ID 3 -> 
		$array = serialize(array("stats" => array("sta" => 25, "int" => -25),
									"duration" => 120));


		$this->template->set('jsfile', 'battle');
		$this->template->ingame('game/battle/battle', $this, 'battle');
	}

	function cast() {
		switch($this->input->get('key')) {
			case 81: 
				$spellID = 1;
				break;
			case 87:
				$spellID = 2;
				break;
			case 69:
				$spellID = 3;
				break;
			case 82:
				$spellID = 4;
				break;
			default: $spellID = 1;
		}

		$Cast = $this->spell->Cast($this->cid, $spellID, array('target' => $this->input->get('target')));

		if($Cast) {
			$response = "Character ID: ".$this->cid."! Key: ".$this->input->get('key')." Spell: ".$spellID." Target: ".$Cast[0]['cid']." Health left: ".$Cast[0]['health'];
		}else{
			$response = "On COOLDOWN!";
		}
		echo json_encode($response);
	}
}

// End of Battle File