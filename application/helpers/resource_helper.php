<?php

	function setCharacterHealth( $uid, $class_id, $equip ) {
		$ci =& get_instance();

		$get_data = $ci->db->select('sta, health, health_max')
						   ->where('user_id', $uid)
						   ->get('characters');

		$player_data = $get_data->row_array();

		$health_max = $player_data['sta']; // checkpoint1  - stam * 5 * ( lvl + 1 )
		if ($equip) {
			if (array_key_exists('mainhand', $equip)) {
				if (array_key_exists('id', $equip['mainhand'])) {
					$health_max += $equip['mainhand']['sta'];
				}
			}
			if (array_key_exists('offhand', $equip)) {
				if (array_key_exists('id', $equip['offhand'])) {
					$health_max += $equip['offhand']['sta'];
				}
			}
			if (array_key_exists('head', $equip)) {
				if (array_key_exists('id', $equip['head'])) {
					$health_max += $equip['head']['sta'];
				}
			}
			if (array_key_exists('shoulders', $equip)) {
				if (array_key_exists('id', $equip['shoulders'])) {
					$health_max += $equip['shoulders']['sta'];
				}
			}
			if (array_key_exists('cloak', $equip)) {
				if (array_key_exists('id', $equip['cloak'])) {
					$health_max += $equip['cloak']['sta'];
				}
			}
			if (array_key_exists('chest', $equip)) {
				if (array_key_exists('id', $equip['chest'])) {
					$health_max += $equip['chest']['sta'];
				}
			}
			if (array_key_exists('pants', $equip)) {
				if (array_key_exists('id', $equip['pants'])) {
					$health_max += $equip['pants']['sta'];
				}
			}
			if (array_key_exists('boots', $equip)) {
				if (array_key_exists('id', $equip['boots'])) {
					$health_max += $equip['boots']['sta'];
				}
			}
		}
		$health_max = $health_max * 10;

		if ( $player_data['health_max'] == 0 ) {
			$health = $health_max;
		}else{
			$health = $player_data['health'];
		}

		$health_data = array(
               'health' => $health,
               'health_max' => $health_max
        );

		$ci->db->where('user_id', $uid);
		$ci->db->update('characters', $health_data);
	}

	function setCharacterMana( $uid, $class_id, $equip ) {
		$ci =& get_instance();

		$get_data = $ci->db->select('int, mana, mana_max')
						   ->where('user_id', $uid)
						   ->get('characters');

		$player_data = $get_data->row_array();

		$mana_max = $player_data['int']; 
		if ($equip) {
			if (array_key_exists('mainhand', $equip)) {
				if (array_key_exists('id', $equip['mainhand'])) {
					$mana_max += $equip['mainhand']['int'];
				}
			}
			if (array_key_exists('offhand', $equip)) {
				if (array_key_exists('id', $equip['offhand'])) {
					$mana_max += $equip['offhand']['int'];
				}
			}
			if (array_key_exists('head', $equip)) {
				if (array_key_exists('id', $equip['head'])) {
					$mana_max += $equip['head']['int'];
				}
			}
			if (array_key_exists('shoulders', $equip)) {
				if (array_key_exists('id', $equip['shoulders'])) {
					$mana_max += $equip['shoulders']['int'];
				}
			}
			if (array_key_exists('cloak', $equip)) {
				if (array_key_exists('id', $equip['cloak'])) {
					$mana_max += $equip['cloak']['int'];
				}
			}
			if (array_key_exists('chest', $equip)) {
				if (array_key_exists('id', $equip['chest'])) {
					$mana_max += $equip['chest']['int'];
				}
			}
			if (array_key_exists('pants', $equip)) {
				if (array_key_exists('id', $equip['pants'])) {
					$mana_max += $equip['pants']['int'];
				}
			}
			if (array_key_exists('boots', $equip)) {
				if (array_key_exists('id', $equip['boots'])) {
					$mana_max += $equip['boots']['int'];
				}
			}
		}
		$mana_max = $mana_max * 10;

		if ( $player_data['mana_max'] == 0 ) {
			$mana = $mana_max;
		}else{
			$mana = $player_data['mana'];
		}

		$mana_data = array(
               'mana' => $mana,
               'mana_max' => $mana_max
        );

		$ci->db->where('user_id', $uid);
		$ci->db->update('characters', $mana_data);
	}


	function showHealthBar( $health, $health_max )	{

		$bar_size = $health * 100 / $health_max;

		$text  = "</center>";
		$text .= "	<div class=\"resource-wrap\">";
		$text .= "		<div class=\"resource-value health\" style=\"width: ".floor($bar_size)."%;\">";
		$text .= "			<div class=\"resource-text\">";
		$text .= "				<strong>".$health." / ".$health_max."</strong>";
		$text .= "			</div>";
		$text .= "		</div>";
		$text .= "	</div>";
		$text .= "<center>";
		echo $text;
	}

	function showManaBar( $mana, $mana_max )	{

		$bar_size = $mana * 100 / $mana_max;

		$text  = "</center>";
		$text .= "	<div class=\"resource-wrap\">";
		$text .= "		<div class=\"resource-value mana\" style=\"width: ".floor($bar_size)."%;\">";
		$text .= "			<div class=\"resource-text\">";
		$text .= "				<strong>".$mana." / ".$mana_max."</strong>";
		$text .= "			</div>";
		$text .= "		</div>";
		$text .= "	</div>";
		$text .= "<center>";
		echo $text;
	}

	function regenerateResources( $config_id = 1 ) {
		$ci = & get_instance();

		$config_object = $ci->db->select('*')
								->where('config_id', $config_id)
								->get('config');

		$config = $config_object->row_array();

		$current_time = time();
		if(($config['regen_last'] + ($config['regen_every'] * 60)) < $current_time) {

			$players_data = $ci->db->select('user_id, health, health_max, mana, mana_max')
								   ->where('health_max >', '0')
								   ->where('mana_max >', '0')
								   ->where('combat', '0')
								   ->get('characters');
					
			foreach( $players_data->result() as $player ) {

				$player_new['health'] = floor(($player->health_max / 10 ) + $player->health);
				if($player_new['health'] > $player->health_max) {
					$player_new['health'] = $player->health_max;
				}
				$player_new['mana'] = floor(($player->mana_max / 10 ) + $player->mana);
				if($player_new['mana'] > $player->mana_max) {
					$player_new['mana'] = $player->mana_max;
				}

				// UPDATE HEALTH AND MANA
				$ci->db->where('user_id', $player->user_id);
				$ci->db->update('characters', $player_new);
			}

			$time['regen_last'] = time();

			// UPDATE LAST TIME
			$ci->db->where('config_id', $config_id);
			$ci->db->update('config', $time);			
		}
	}


	function DoResourcesCheck( $uid ) {
		$ci =& get_instance();

		$get_data = $ci->db->select('health, health_max, mana, mana_max')
						   ->where('user_id', $uid)
						   ->get('characters');

		$player_data = $get_data->row_array();

		if($player_data['health'] > $player_data['health_max']) {
			$ci->db->set('health', $player_data['health_max'])
				   ->where('user_id', $uid)
				   ->update('characters');					
		}
		if($player_data['mana'] > $player_data['mana_max']) {
			$ci->db->set('mana', $player_data['mana_max'])
				   ->where('user_id', $uid)
				   ->update('characters');					
		}
	}
?>