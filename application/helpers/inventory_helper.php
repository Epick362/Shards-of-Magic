<?php
	function EquipItem( $uid, $item_id ) {
		$ci = & get_instance();

		$item_character = $ci->db->select('equip_mainhand, equip_offhand, equip_head, equip_shoulders, equip_cloak,												   equip_chest, equip_pants, equip_boots')
								 ->where('user_id', $uid)
								 ->get('characters', 1);
		
		$item_chr = $item_character->row_array();

		$item_inventory = $ci->db->select('*')
						   ->where('item', $item_id)
						   ->where('user_id', $uid)
						   ->get('characters_inventory', 1);
		
		$item_inv = $item_inventory->row_array();

		$item_info = $ci->db->select('*')
							->where('id', $item_id)
							->get('items');
		
		$item_data = $item_info->row_array();
		/*
			Item_type 0 - 
					  1 - armor
					  2 - consumable

			Equip_slot 1 - Head
					   2 - Cloak
					   3 - Chest
					   4 - Pants
					   5 - Boots
		*/

		switch($item_data['equip_slot']) {
			case 1: 
				$slot = 'equip_mainhand';
				break;
			case 2: 
				$slot = 'equip_offhand';
				break;
			case 3: 
				$slot = 'equip_head';
				break;
			case 4: 
				$slot = 'equip_shoulders';
				break;
			case 5: 
				$slot = 'equip_cloak';
				break;
			case 6: 
				$slot = 'equip_chest';
				break;
			case 7: 
				$slot = 'equip_pants';
				break;
			case 8: 
				$slot = 'equip_boots';
				break;
		}

		if($item_inv && $item_data['item_type'] = 1 && $item_data['item_type'] = 2) {
			if($item_chr[$slot] != 0)
			{
				$eq_item = $ci->db->select('*')
								  ->where('id', $item_chr[$slot])
								  ->get('items');

				$eq_item_inv = array(
								   'user_id' => $uid,
								   //'slot' => 'FREE SLOT', UNDONE 
								   'item' => $item_chr[$slot]
								);
									
				$add_to_inv = $ci->db->insert('characters_inventory', $eq_item_inv);			
			}
			$ci->db->where('item', $item_id)
				   ->where('user_id', $uid)
				   ->where('id', $item_inv['id'])
				   ->delete('characters_inventory');

			$ci->db->set($slot, $item_id)
				   ->where('user_id', $uid)
				   ->update('characters');

			echo 'Item '. $item_data['name'] .' Equipped'; 	
			return TRUE;
		}else{
			echo 'Item '. $item_data['name'] .' was not Equipped'; 
			return FALSE;
		}		
	}

	function unEquipItem( $uid, $item_id ) {
		$ci = & get_instance();

		$item_character = $ci->db->select('*')
								 ->where('equip_mainhand', $item_id)
								 ->or_where('equip_offhand', $item_id)
								 ->or_where('equip_head', $item_id)
								 ->or_where('equip_shoulders', $item_id)
								 ->or_where('equip_cloak', $item_id)
								 ->or_where('equip_chest', $item_id)
								 ->or_where('equip_pants', $item_id)
								 ->or_where('equip_boots', $item_id)
								 ->where('user_id', $uid)
								 ->get('characters', 1);
		
		if($item_character->num_rows() > 0 ) { 

			$item_chr = $item_character->row_array();

			$item_info = $ci->db->select('*')
								->where('id', $item_id)
								->get('items');
			
			$item_data = $item_info->row_array();

			/*
				Item_type 0 - 
						  1 - eqquipable
						  2 - consumable

				Equip_slot 1 - Head
						   2 - Chest
						   3 - Pants
						   4 - Boots
			*/

			switch($item_data['equip_slot']) {
				case 1: 
					$slot = 'equip_mainhand';
					break;
				case 2: 
					$slot = 'equip_offhand';
					break;
				case 3: 
					$slot = 'equip_head';
					break;
				case 4: 
					$slot = 'equip_shoulders';
					break;
				case 5: 
					$slot = 'equip_cloak';
					break;
				case 6: 
					$slot = 'equip_chest';
					break;
				case 7: 
					$slot = 'equip_pants';
					break;
				case 8: 
					$slot = 'equip_boots';
					break;
			}
			
			if($item_chr[$slot] = $item_id) {

				$ci->db->set($slot, 0)
					   ->where('user_id', $uid)
					   ->update('characters');
					   
				$add_item_inv = array(
								   'user_id' => $uid,
								   //'slot' => 'FREE SLOT', UNDONE 
								   'item' => $item_id
								);

				$add_to_inv = $ci->db->insert('characters_inventory', $add_item_inv);				
				echo 'Item '. $item_data['name'] .' unEquipped'; 	
				return TRUE;
			}else{
				echo 'Item '. $item_data['name'] .' was not unEquipped'; 
				return FALSE;
			}
		}
	}
?>