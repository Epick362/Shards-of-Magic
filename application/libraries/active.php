<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Active class
 *
 */
class Active
{
	function __construct()
	{
		$this->ci =& get_instance();
	}

	function regenerateResources( $player ) {
		$UpdateTime                   = time();
		$ProductionTime               = ($UpdateTime - $player->last_update);
		// Health
		$health = $player->health + (($ProductionTime * 0.5) * 1.4 * $player->level); // To add Drinking, Eating etc.
		if($health > $player->health_max) {
			$health = $player->health_max;
		}
		// Mana
		$mana = $player->mana + (($ProductionTime * 0.5) * 6 * $player->level); // To add Drinking, Eating etc.
		if($mana > $player->mana_max) {
			$mana = $player->mana_max;
		}

		$update = array(
				'health' => $health,
				'mana'   => $mana,
				'last_update' => $UpdateTime);

		$this->ci->db->where('cid', $player->cid)->update('characters', $update);

		return $update;
	}

	function DoResourcesCheck( $cid ) {
		$get_data = $this->ci->db->select('health, health_max, mana, mana_max')
						   ->where('cid', $cid)
						   ->get('characters');

		$player_data = $get_data->row_array();

		if($player_data['health'] > $player_data['health_max']) {
			$this->ci->db->set('health', $player_data['health_max'])
				   ->where('cid', $cid)
				   ->update('characters');					
		}
		if($player_data['mana'] > $player_data['mana_max']) {
			$this->ci->db->set('mana', $player_data['mana_max'])
				   ->where('cid', $cid)
				   ->update('characters');					
		}
	}

	function TravellingCheck( $cid ) {
		$get_data = $this->ci->db->select('*')
								 ->where('cid', $cid)
								 ->get('travel');
		$data = $get_data->row();
		
		if($data) {
			$time = time();
			
			if($data->end_time < $time) {
				$this->ci->db->where('cid', $cid);
				$this->ci->db->delete('travel');

				$this->ci->characters->updateLocation($data);
			}			
		}
	}
}
?>
