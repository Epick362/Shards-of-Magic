<?php 
	function getItemData( $item_id ) {
		$ci =& get_instance();

		$get_data = $ci->db->select('*')
						   ->where('id', $item_id)
						   ->get('items');	
		
		if($get_data->num_rows() > 0 ) {
			return $get_data->row_array();
		}else{
			return FALSE;	
		}
	}

	function getItemColor( $item ) {
		switch($item['quality']) {
			case 0: 
				$color = "#FFF";
				break;
			case 1: 
				$color = "#FFFF00";
				break;
			case 2: 
				$color = "#FF6600";
				break;
			case 3: 
				$color = "#FF2200";
				break;
		}
		$item['name'] = "<p style=\"color:". $color .";\">". $item['name'] ."</p>";
		return $item['name'];
	}

	function addItemLink ( $item, $mode = 0 ) {
		if($mode=0) {
			$item['name'] = "<a href=\"character/equip/id/".$item['id']."\">". $item['name'] ."</p>";
		}else{
			$item['name'] = "<a href=\"character/unequip/id/".$item['id']."\">". $item['name'] ."</p>";
		}
		return $item['name'];
	}

	function getEquippedItemsData( $uid ) {
		$ci =& get_instance();

		$get_data = $ci->db->select('equip_mainhand, equip_offhand, equip_head, equip_shoulders, equip_cloak, equip_chest, 									 equip_pants, equip_boots')
						   ->where('user_id', $uid)
						   ->get('characters');

		$player_data = $get_data->row_array();


		// Mainhand DATA
			if($player_data['equip_mainhand'] != 0) {
				$mainhand_data = $ci->db->select('*')
									->where('id', $player_data['equip_mainhand'])
									->get('items');
				$equip['mainhand'] = $mainhand_data->row_array();
				$equip['mainhand']['name'] = getItemColor($equip['mainhand']);
				$equip['mainhand']['name'] = addItemLink($equip['mainhand'], 1);
			}else{
				$equip['mainhand']['name'] = 'None';
			}
		// Offhand DATA
			if($player_data['equip_offhand'] != 0) {
				$offhand_data = $ci->db->select('*')
									->where('id', $player_data['equip_offhand'])
									->get('items');
				$equip['offhand'] = $offhand_data->row_array();
				$equip['offhand']['name'] = getItemColor($equip['offhand']);
				$equip['offhand']['name'] = addItemLink($equip['offhand'], 1);
			}else{
				$equip['offhand']['name'] = 'None';
			}

		// HEAD DATA
			if($player_data['equip_head'] != 0) {
				$head_data = $ci->db->select('*')
									->where('id', $player_data['equip_head'])
									->get('items');
				$equip['head'] = $head_data->row_array();
				$equip['head']['name'] = getItemColor($equip['head']);
				$equip['head']['name'] = addItemLink($equip['head'], 1);				
			}else{
				$equip['head']['name'] = 'None';
			}
		// SHOULDERS DATA
			if($player_data['equip_shoulders'] != 0) {
				$shoulders_data = $ci->db->select('*')
									->where('id', $player_data['equip_shoulders'])
									->get('items');
				$equip['shoulders'] = $shoulders_data->row_array();
				$equip['shoulders']['name'] = getItemColor($equip['shoulders']);
				$equip['shoulders']['name'] = addItemLink($equip['shoulders'], 1);				
			}else{
				$equip['shoulders']['name'] = 'None';
			}
		// CLOAK DATA
			if($player_data['equip_cloak'] != 0) {
				$cloak_data = $ci->db->select('*')
									->where('id', $player_data['equip_cloak'])
									->get('items');
				$equip['cloak'] = $cloak_data->row_array();
				$equip['cloak']['name'] = getItemColor($equip['cloak']);
				$equip['cloak']['name'] = addItemLink($equip['cloak'], 1);				
			}else{
				$equip['cloak']['name'] = 'None';
			}
		// CHEST DATA
			if($player_data['equip_chest'] != 0) {
				$chest_data = $ci->db->select('*')
									->where('id', $player_data['equip_chest'])
									->get('items');
				$equip['chest'] = $chest_data->row_array();
				$equip['chest']['name'] = getItemColor($equip['chest']);
				$equip['chest']['name'] = addItemLink($equip['chest'], 1);
			}else{
				$equip['chest']['name'] = 'None';
			}
		// PANTS DATA
			if($player_data['equip_pants'] != 0) {
				$pants_data = $ci->db->select('*')
									->where('id', $player_data['equip_pants'])
									->get('items');
				$equip['pants'] = $pants_data->row_array();
				$equip['pants']['name'] = getItemColor($equip['pants']);
				$equip['pants']['name'] = addItemLink($equip['pants'], 1);
			}else{
				$equip['pants']['name'] = 'None';
			}
		// BOOTS DATA
			if($player_data['equip_boots'] != 0) {
				$boots_data = $ci->db->select('*')
									->where('id', $player_data['equip_boots'])
									->get('items');
				$equip['boots'] = $boots_data->row_array();
				$equip['boots']['name'] = getItemColor($equip['boots']);
				$equip['boots']['name'] = addItemLink($equip['boots'], 1);
			}else{
				$equip['boots']['name'] = 'None';
			}

		if ( $player_data['equip_mainhand'] == 0
			&& $player_data['equip_offhand'] == 0 
			&& $player_data['equip_head'] == 0
			&& $player_data['equip_shoulders'] == 0 
			&& $player_data['equip_cloak'] == 0 
			&& $player_data['equip_chest'] == 0 
			&& $player_data['equip_pants'] == 0 
			&& $player_data['equip_boots'] == 0 ) {
				$equip['mainhand']['name'] = 'None';
				$equip['offhand']['name'] = 'None';
				$equip['head']['name'] = 'None';
				$equip['shoulders']['name'] = 'None';
				$equip['cloak']['name'] = 'None';
				$equip['chest']['name'] = 'None';
				$equip['pants']['name'] = 'None';
				$equip['boots']['name'] = 'None';
			}
		
		return $equip;		
	} 
?>