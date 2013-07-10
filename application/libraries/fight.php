<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Fight class
 *
 */
class Fight
{
	function __construct()
	{
		$this->ci =& get_instance();

		$this->max_rounds = 16;
	}

	function getCombatData( $cid ) {
		$query = $this->ci->db->where('id', $cid)->get('combat');
		if($query->result()) {
			$data = $query->row_array();
			return $data;
		}else{
			return FALSE;
		}
	}

	function isInCombat( $cid ) {
		$query = $this->ci->db->where('attacker', $cid)->or_where('opponent', $cid)->get('combat');
		if($query->result()) {
			return TRUE;
		}else{
			return FALSE;
		}		
	}

	function getCharacterDamage( $player_data, $slot = 'mainhand' ) {
		$combat = array();

		if($player_data->equip[$slot]['id'] != 0) {
			$combat[$slot]['damage'] = floor(mt_rand($player_data->equip[$slot]['min_damage'], $player_data->equip[$slot]['max_damage']) / ($player_data->equip[$slot]['speed'] / 1000 ));
		}

		if(!array_key_exists($slot, $combat)) {
			$combat[$slot]['damage'] = floor(mt_rand(3, 4));
		}

		if(!array_key_exists('speed', $player_data->equip[$slot])) {
			$player_data->equip[$slot]['speed'] = 1000;
		}

		switch($player_data->class) {
			case 1:
				$attackpower = round($player_data->int * 2  + ($player_data->level * 3));
				break;
			case 2:
				$attackpower = round($player_data->str + ($player_data->dex * 2) + ($player_data->level * 2));
				break;
			case 3:
				$attackpower = round($player_data->str * 2 + ($player_data->level * 3));
				break;
			case 4:
				$attackpower = round($player_data->int * 2  + ($player_data->level * 3));
				break;
			case 5:
				$attackpower = round($player_data->dex * 2 + ($player_data->level * 3));
				break;
			case 6:
				$attackpower = round($player_data->str * 2 + ($player_data->level * 3));
				break;
		}
		$combat['attack'] = round(($combat[$slot]['damage'] + ($attackpower / 14)) * ($player_data->equip[$slot]['speed'] / 1000));
		return $combat['attack'];
	}
}
?>