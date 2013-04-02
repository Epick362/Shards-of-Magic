<?php

	function getClassName( $class_id='' ) {
		$ci =& get_instance();

		if (!$class_id) {
			$uid = $ci->tank_auth->get_user_id();
			$query = $ci->db->select('class')
							->where('user_id', $uid)
							->get('characters');
			$class_array = $query->row_array();	
			$class_id = $class_array['class'];	
		}

		switch( $class_id ) {
			case 1:
				$class_name = "Magician";
				break;
			case 2:
				$class_name = "Thief";
				break;
			case 3:
				$class_name = "Crusader";
				break;
			case 4:
				$class_name = "Priest";
				break;
			case 5:
				$class_name = "Scout";
				break;
		}
		return $class_name;
	}

	function getPlayerData ( $uid ) {
		$ci =& get_instance();

		$query = $ci->db->select('*')
						->where('user_id', $uid)
						->get('characters');

		$query_users = $ci->db->select('*')
							  ->where('id', $uid)
							  ->get('users');
		
		if( $query->num_rows() <= 0 || $query_users->num_rows() <= 0 ) {
			return FALSE;
		}

		$query = $query->row();
		$query_users = $query_users->row();

		$query->username = $query_users->username;
		return $query;
	}

	function getGenderName( $gender_id ) {
		$gender_id == 0 ? $gender_name = "Male" : $gender_name = "Female";

		return $gender_name;
	}

	function setDefaultClassStats( $uid, $class_id ) {
		$ci =& get_instance();

		switch($class_id) {
			case 1: // Magician
				$default_stats = array("sta" => 18, "int" => 22, "str" => 18, "dex" => 20, "luc" => 21);
				break;
			case 2: // Thief
				$default_stats = array("sta" => 19, "int" => 18, "str" => 19, "dex" => 22, "luc" => 21);
				break;
			case 3: // Crusader
				$default_stats = array("sta" => 22, "int" => 18, "str" => 22, "dex" => 17, "luc" => 20);
				break;
			case 4: // Priest
				$default_stats = array("sta" => 20, "int" => 23, "str" => 17, "dex" => 19, "luc" => 20);
				break;
			case 5: // Scout
				$default_stats = array("sta" => 21, "int" => 19, "str" => 16, "dex" => 23, "luc" => 20);
				break;
		// Sum of default stats = 99
		}
		
		$sql = "UPDATE `characters` SET `sta`   = ". $default_stats['sta'] .", 
										`int`  = ". $default_stats['int'] .",
										`str`  = ". $default_stats['str'] .",
										`dex` = ". $default_stats['dex'] .",
										`luc`      = ". $default_stats['luc'] ."
										WHERE user_id = ". $uid .";";
		$query = $ci->db->query($sql);

		return $query;
 	}

 	function getCharacterCombatStats ($uid, $equip, $attack = FALSE) {  
		$ci =& get_instance();

		$get_data = $ci->db->select('*')
						->where('user_id', $uid)
						->get('characters'); 	
		$player_data = $get_data->row_array();

		$combat['min_damage'] = 0;
		$combat['max_damage'] = 0;

		if (array_key_exists('mainhand', $equip)) {
			if (array_key_exists('id', $equip['mainhand'])) {
				$combat['min_damage'] += $equip['mainhand']['min_damage'];
				$combat['max_damage'] += $equip['mainhand']['max_damage'];
			}else{
				$combat['min_damage'] += 0;
				$combat['max_damage'] += 0;
			}
		}

		if (array_key_exists('offhand', $equip)) {
			if (array_key_exists('id', $equip['offhand'])) {
				$combat['min_damage'] += $equip['offhand']['min_damage'];
				$combat['max_damage'] += $equip['offhand']['max_damage'];
			}else{
				$combat['min_damage'] += 0;
				$combat['max_damage'] += 0;
			}
		}

		$combat['armor'] = 0;

		if (array_key_exists('mainhand', $equip)) {
			if (array_key_exists('id', $equip['mainhand'])) {
				$combat['armor'] += $equip['mainhand']['armor'];
			}
		}
		if (array_key_exists('offhand', $equip)) {
			if (array_key_exists('id', $equip['offhand'])) {
				$combat['armor'] += $equip['offhand']['armor'];
			}
		}
		if (array_key_exists('head', $equip)) {
			if (array_key_exists('id', $equip['head'])) {
				$combat['armor'] += $equip['head']['armor'];
			}
		}
		if (array_key_exists('shoulders', $equip)) {
			if (array_key_exists('id', $equip['shoulders'])) {
				$combat['armor'] += $equip['shoulders']['armor'];
			}
		}
		if (array_key_exists('cloak', $equip)) {
			if (array_key_exists('id', $equip['cloak'])) {
				$combat['armor'] += $equip['cloak']['armor'];
			}
		}
		if (array_key_exists('chest', $equip)) {
			if (array_key_exists('id', $equip['chest'])) {
				$combat['armor'] += $equip['chest']['armor'];
			}
		}
		if (array_key_exists('pants', $equip)) {
			if (array_key_exists('id', $equip['pants'])) {
				$combat['armor'] += $equip['pants']['armor'];
			}
		}
		if (array_key_exists('boots', $equip)) {
			if (array_key_exists('id', $equip['boots'])) {
				$combat['armor'] += $equip['boots']['armor'];
			}
		}

		if($attack) {
			switch($player_data['class']) {
				case 1:
					$combat['attack'] = $combat['mainhand']['damage'] + $combat['offhand']['damage'];
					$combat['attack'] = floor($combat['attack'] * (1 + $player_data['int'] / 10 ));
					break;
				case 2:
					$combat['attack'] = $combat['mainhand']['damage'] + $combat['offhand']['damage'];
					$combat['attack'] = floor($combat['attack'] * (1 + $player_data['str'] / 10 ));
					break;
				case 3:
					$combat['attack'] = $combat['mainhand']['damage'] + $combat['offhand']['damage'];
					$combat['attack'] = floor($combat['attack'] * (1 + $player_data['dex'] / 10 ));
					break;
				case 4:
					$combat['attack'] = $combat['mainhand']['damage'] + $combat['offhand']['damage'];
					$combat['attack'] = floor($combat['attack'] * (1 + ($player_data['int'] / 10 )));
					break;
				case 5:
					$combat['attack'] = $combat['mainhand']['damage'] + $combat['offhand']['damage'];
					$combat['attack'] = floor($combat['attack'] * (1 + $player_data['dex'] / 10 ));
					break;
			}
		}
		return $combat;
 	}
?>
