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

		$this->ci->db->where('user_id', $player->user_id)->update('characters', $update);

		return $update;
	}

	function DoResourcesCheck( $uid ) {
		$get_data = $this->ci->db->select('health, health_max, mana, mana_max')
						   ->where('user_id', $uid)
						   ->get('characters');

		$player_data = $get_data->row_array();

		if($player_data['health'] > $player_data['health_max']) {
			$this->ci->db->set('health', $player_data['health_max'])
				   ->where('user_id', $uid)
				   ->update('characters');					
		}
		if($player_data['mana'] > $player_data['mana_max']) {
			$this->ci->db->set('mana', $player_data['mana_max'])
				   ->where('user_id', $uid)
				   ->update('characters');					
		}
	}

	function TravellingCheck( $uid ) {
		
		$get_data = $this->ci->db->select('*')
								 ->where('user_id', $uid)
								 ->get('travel');
		$data = $get_data->row();
		
		$time = time();
		
		if($data->end_time < $time) {
			$this->ci->db->where('user_id', $uid);
			$this->ci->db->delete('travel');

			$this->ci->characters->updateLocation($data);
		}
	}
}
?>
