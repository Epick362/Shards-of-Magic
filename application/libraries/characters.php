<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Characters class
 *
 */
class characters
{
	function __construct()
	{
		$this->ci =& get_instance();
		$this->maxlevel    = 40;
		$this->maxslots    = 36;
		$this->equip_slots=array('mainhand' => 1,
								'offhand' => 2,
								'head' => 3,
								'shoulders' => 4,
								'cloak' => 5,
								'chest' => 6,
								'hands' => 7,
								'waist' => 8,
								'pants' => 9,
								'boots' => 10,
								'amulet' => 11);
		$this->stats =array('stamina' => 'sta',
							'intellect' => 'int',
							'strenght' => 'str',
							'dexterity' => 'dex',
							'luck' => 'luc');
		$this->resources = array('health' => array('stat' => 'sta', 'stat_factor' => 10, 'level_factor' => 60), 
								 'mana' => array('stat' => 'int', 'stat_factor' => 10, 'level_factor' => 70));
	}

	function getCharacterData ( $uid, $cid, $own_character = 0) {
		$data = $this->ci->db->query("
        SELECT *  FROM `characters`
        LEFT JOIN `users` ON `characters`.`user_id`=`users`.`id`
        WHERE `characters`.`cid`=". $cid .";")->row();

        $this->updateSpells( $cid );

        $data->xp_needed   = $this->ci->characters->experienceNeeded( $data );
		$data->equip 	   = $this->ci->characters->getEquippedItemsData( $cid, $own_character );
		$data->bonus_stats = $this->ci->characters->getCharacterStats( $data );
		foreach($this->stats as $key => $short) {
			$data->base_stats[$short] = $data->$short;
			$data->$short = $data->bonus_stats[$short];
		} 

		$this->ci->characters->experienceHandler( $data );
		$data->inv   		= $this->ci->characters->getCharacterInventory( $cid, $data->level, $data->class );

		$data->classData    = $this->ci->core->getClassData( $data->class );
		$data->gender_name  = $this->ci->core->getGenderName( $data->gender );
		$data->money        = $this->ci->core->showMoney( $data->money );

		$data->guildData    = $this->ci->characters->getGuildData( $data->cid );

		$this->getCharacterResources($data->cid, $data);

		return $data;
	}

	function updateSpells($cid) {
		return $this->ci->db->where('cid', $cid)->where('lastsUntil <', time())->delete('characters_spells');
	}

	function isOnline( $uid ) {
		if(is_array($uid)) {
			$data = $this->ci->db->select("user_id, last_update")->where_in('user_id', $uid)->get('characters');
			foreach($data->result() as $row) {
				$time = time();
				if($row->last_update + 60 > $time) {
					$result[$row->user_id]['online'] = 1;
				}else{
					$result[$row->user_id]['online'] = 0;
				}
				$result[$row->user_id]['ago'] = $this->ci->core->time_since($row->last_update);
			}
			return $result;
		}else{
			$data = $this->ci->db->select("last_update")->where('user_id', $uid);
			$row = $data->row();

			$time = time();
			if($row->last_update + 15 > $time) {
				$result['online'] = 1;
			}else{
				$result['online'] = 0;
			}
			$result['ago'] = $this->ci->core->time_ago($row->last_update);
			return $result;
		}
	}

	function setClassStats( $cid, $player_data ) {	
		switch($player_data->class) {
			case 1: // Mage
				$default_stats = array('sta' => 18, 'int' => 23, 'str' => 18, 'dex' => 20, 'luc' => 20);
				$main_stat     = 'int';
				break;
			case 2: // Thief
				$default_stats = array('sta' => 19, 'int' => 18, 'str' => 19, 'dex' => 23, 'luc' => 20);
				$main_stat     = 'dex';
				break;
			case 3: // Crusader
				$default_stats = array('sta' => 22, 'int' => 18, 'str' => 22, 'dex' => 17, 'luc' => 20);
				$main_stat     = 'str';
				break;
			case 4: // Priest
				$default_stats = array('sta' => 20, 'int' => 23, 'str' => 17, 'dex' => 19, 'luc' => 20);
				$main_stat     = 'int';
				break;
			case 5: // Scout
				$default_stats = array('sta' => 21, 'int' => 19, 'str' => 16, 'dex' => 23, 'luc' => 20);
				$main_stat     = 'dex';
				break;
			case 6: // Berserker
				$default_stats = array('sta' => 23, 'int' => 17, 'str' => 23, 'dex' => 16, 'luc' => 20);
				$main_stat     = 'str';
				break;
		// Sum of default stats = 99
		}

		if( $player_data->level > 1 ) {
			foreach( $default_stats as $stat => $value ) {
				if( $stat == $main_stat || $stat == 'sta' ) {
					$default_stats[$stat] = floor($value * pow(1.03, $player_data->level));
				}else{
					$default_stats[$stat] = floor($value * pow(1.02, $player_data->level));
				}
			}
		}

		if( $player_data->sta !=  $default_stats['sta'] ||
			$player_data->int !=  $default_stats['int'] ||
			$player_data->str !=  $default_stats['str'] ||
			$player_data->dex !=  $default_stats['dex'] ||
			$player_data->luc !=  $default_stats['luc'] ) {
		
			$sql = "UPDATE `characters` SET `sta`   = ". $default_stats['sta'] .", 
											`int`   = ". $default_stats['int'] .",
											`str`   = ". $default_stats['str'] .",
											`dex`   = ". $default_stats['dex'] .",
											`luc`	= ". $default_stats['luc'] ."
											WHERE cid = ". $cid .";";
			$query = $this->ci->db->query($sql);
		}

		return $default_stats;
 	}

	function getCharacterInventory ( $cid, $level = 1, $class, $mode = 1 ) {

		$query_a = $this->ci->db->where('cid', $cid)->get('characters_inventory')->result_array();
		$inv = $this->ci->core->groupArray( $query_a, 'slot' );

		$i = 1;
		if( $mode == 4 ) {
			$inventory = "<table width=\"100%\" id=\"inventory\">";
		}else{
			$inventory = "<table width=\"100%\">";
		}
		$position = 1;

		$class_data = $this->ci->core->getClassData( $class );

		while( $i <= $this->maxslots ) {
			if($position == 1){$inventory .= "<tr>";}
			
			if (array_key_exists($i, $inv)) {
				$item_data		   = $this->ci->core->getItemData($inv[$i]['item']);
				$item_data['name'] = $this->ci->item->getItemColor($item_data);
				$canEquip = in_array( $item_data['subclass'], $class_data['can_equip'] );
				$item_data['image'] = $this->ci->item->addItemTooltip($item_data, $mode, $level, $canEquip);

				$inventory .= "<td>". $item_data['image'] ."</td>";	
			}else{
				$inventory .= "<td>".$this->ci->item->addItemTooltip()."</td>";
			}
			
			if($position == 9){$inventory .= "</tr> "; $position = 1;}else{ $position++; }
			$i++;
		}

		if($position != 1){
			for($z=(9-$position); $z>0 ; $z--){
				$inventory .= "<td></td>";
			}
				$inventory .= "</tr>";
		}
		$inventory .= "</table>";

		return $inventory;
	}

	function getCharacterMoney( $cid ) {
		return $this->ci->db->select('money')->where('cid', $cid)->get('characters')->row()->money;
	}

	function getEquippedItemsData( $cid, $own_character = 0 ) {
		$get_data = $this->ci->db->select('level, class, equip_mainhand, equip_offhand, equip_head, equip_shoulders, equip_cloak, equip_chest, equip_hands, equip_waist, equip_pants, equip_boots, equip_amulet')
								->where('cid', $cid)
								->get('characters');

		$player_data = $get_data->row_array();
		$class_data = $this->ci->core->getClassData( $player_data['class'] );

		foreach( $this->equip_slots as $slot => $slot_id ) {
			$items[$slot_id] = $player_data['equip_'.$slot];
		}

		$items_data = $this->ci->core->getItemData( $items, 1 );

		foreach( $this->equip_slots as $slot => $slot_id ) {
			if(array_key_exists($slot_id, $items_data)) {
				$canEquip = in_array( $items_data[$slot_id]['subclass'], $class_data['can_equip'] );
				if ($own_character) {
					$items_data[$slot_id]['image'] = $this->ci->item->addItemTooltip($items_data[$slot_id], 2, $player_data['level'], $canEquip);
				}else{
					$items_data[$slot_id]['image'] = $this->ci->item->addItemTooltip($items_data[$slot_id], 0, $player_data['level'], $canEquip);
				}
			}else{
				$items_data[$slot_id]['id'] = 0;
				$items_data[$slot_id]['name'] = "Empty slot";
				$items_data[$slot_id]['image'] = $this->ci->item->addItemTooltip('', 0, 1, 1,$slot_id);
			}
		}
		
		return $items_data;
	}

	function getItemCountInInventory( $item, $cid ) {
		return $this->ci->db->where('cid', $cid)->where('item', $item)->get('characters_inventory')->num_rows();
	}

	function EquipItem( $cid, $item_id ) {
		$item_character = $this->ci->db->where('cid', $cid)->get('characters', 1);
		
		$item_chr = $item_character->row_array();

		$item_inventory = $this->ci->db->where('item', $item_id)->where('cid', $cid)->get('characters_inventory', 1);
		
		$item_inv = $item_inventory->row_array();

		$item_info = $this->ci->db->select('*')
							->where('id', $item_id)
							->get('item_template');
		
		$item_data = $item_info->row_array();

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
				$slot = 'equip_hands';
				break;
			case 8: 
				$slot = 'equip_waist';
				break;
			case 9: 
				$slot = 'equip_pants';
				break;
			case 10: 
				$slot = 'equip_boots';
				break;
			case 11: 
				$slot = 'equip_amulet';
				break;
		}

		$class_data = $this->ci->core->getClassData( $item_chr['class'] );
		if($item_data['class'] == 1) {
			$canEquip = in_array( $item_data['subclass'], $class_data['can_equip'] );
		}else{
			$canEquip = TRUE;
		}

		if(($item_data['RequiredLevel'] <= $item_chr['level']) && $canEquip && $item_inv && ($item_data['class'] == 1 || $item_data['class'] == 2)) {
			if($item_chr[$slot] != 0)
			{
				$free_slot = $this->ci->core->getFirstFreeInventorySlot( $cid );
				$eq_item = $this->ci->db->select('*')
										->where('id', $item_chr[$slot])
										->get('item_template');

				$eq_item_inv = array( 'cid' => $cid, 'slot' => $free_slot, 'item' => $item_chr[$slot]);
									
				$add_to_inv = $this->ci->db->insert('characters_inventory', $eq_item_inv);			
			}
			$this->ci->db->where('item', $item_id)
				   ->where('cid', $cid)
				   ->where('id', $item_inv['id'])
				   ->delete('characters_inventory');

			$this->ci->db->set($slot, $item_id)
				   ->where('cid', $cid)
				   ->update('characters');

			return TRUE;
		}else{
			$this->ci->core->displayMessage( 1, 'You can\'t equip that item' );
			return FALSE;
		}		
	}

	function unEquipItem( $cid, $item_id ) {

		$item_character = $this->ci->db->where('equip_mainhand', $item_id)
								 ->or_where('equip_offhand', $item_id)
								 ->or_where('equip_head', $item_id)
								 ->or_where('equip_shoulders', $item_id)
								 ->or_where('equip_cloak', $item_id)
								 ->or_where('equip_chest', $item_id)
								 ->or_where('equip_hands', $item_id)
								 ->or_where('equip_waist', $item_id)
								 ->or_where('equip_pants', $item_id)
								 ->or_where('equip_boots', $item_id)
								 ->or_where('equip_amulet', $item_id)
								 ->where('cid', $cid)
								 ->get('characters', 1);
		
		if($item_character->num_rows() > 0 ) { 

			$item_chr = $item_character->row_array();

			$item_info = $this->ci->db->select('*')
								->where('id', $item_id)
								->get('item_template');
			
			$item_data = $item_info->row_array();

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
					$slot = 'equip_hands';
					break;
				case 8: 
					$slot = 'equip_waist';
					break;
				case 9: 
					$slot = 'equip_pants';
					break;
				case 10: 
					$slot = 'equip_boots';
					break;
				case 11: 
					$slot = 'equip_amulet';
					break;
			}
			
			if($item_chr[$slot] = $item_id) {				

				$free_slot = $this->ci->core->getFirstFreeInventorySlot( $cid );

				if($free_slot) {
					$this->ci->db->set($slot, 0)
								->where('cid', $cid)
								->update('characters');

					$add_item_inv = array(
									   'cid' => $cid,
									   'slot' => $free_slot,
									   'item' => $item_id
									);
					$add_to_inv = $this->ci->db->insert('characters_inventory', $add_item_inv);		
				}else{
					return FALSE;
				}

				return TRUE;
			}else{
				return FALSE;
			}
		}
	}

	function buyItem( $cid, $item ) {
		$player_money = $this->ci->characters->getCharacterMoney( $cid );
		$player_data->guildData = $this->ci->characters->getGuildData( $cid );
		$item_data = $this->ci->core->getItemData( $item );

		if($player_data->guildData->level >= 4) {
			$item_data['cost'] /= 1.05;
			if($player_data->guildData->level >= 9) {
				$item_data['cost'] /= 1.10;
			}
		}

		$cvq = $this->ci->db->where('item', $item)->get('creature_vendor');
		$cv = $cvq->result();
		foreach($cv as $vendor) {
			$clq = $this->ci->db->where('id', $vendor->creature)->get('creature_locations');
			$cl = $clq->result();
			$check = FALSE;
			$player = $this->getCharacterLocation($uid);
			foreach($cl as $spawn) {
				if($spawn->map == $player->map && $spawn->zone == $player->zone ) {
					$check = TRUE;
				}
			}
		}
		if(!$check) {
			return FALSE;
		}

		if( $player_money >= $item_data['cost'] ) {
			$player_money -= $item_data['cost'];
			$this->ci->db->where('cid', $cid)->update('characters', array( 'money' => $player_money ) );
			$func = $this->ci->characters->giveItem( $item, $cid );

			$data = $item_data;
			$data['player_money'] = $player_money;
			return $data;		
		}else{
			return FALSE;
		}
	}

	function sellItem( $cid, $item ) {
		$player_money = $this->ci->characters->getCharacterMoney( $cid );
		$item_data = $this->ci->core->getItemData( $item );

		$item_inv = $this->ci->db->where('item', $item)->where('cid', $cid)->get('characters_inventory');
		if($item_inv->num_rows() > 0) {
			$player_money += $item_data['cost'] / 2;
			$this->ci->db->where('cid', $cid)->update('characters', array( 'money' => $player_money ) );
			$func = $this->ci->characters->removeItem( $item, $cid );

			$data = $item_data;
			$data['player_money'] = $player_money;
			return $data;
		}else{
			return false;
		}
	}

	function giveItem( $item, $cid ) {
		if(is_array($item)) {
			foreach ($item as $key) {
				$free_slot = $this->ci->core->getFirstFreeInventorySlot( $cid );
				$this->ci->quest->updateQuest( $cid, 2, $key );
				if($free_slot) {
					$add_item_inv = array('cid' => $cid, 'slot' => $free_slot, 'item' => $key);
					$add_to_inv = $this->ci->db->insert('characters_inventory', $add_item_inv);						
				}else{ // NO SPACE FOR ITEM --- TODO

				}
			}
		}else{
			$free_slot = $this->ci->core->getFirstFreeInventorySlot( $cid );
			$this->ci->quest->updateQuest( $cid, 2, $item );
			if($free_slot) {
				$add_item_inv = array('cid' => $cid, 'slot' => $free_slot, 'item' => $item);
				$add_to_inv = $this->ci->db->insert('characters_inventory', $add_item_inv);						
			}else{ // NO SPACE FOR ITEM --- TODO

			}
		}
	}

	function removeItem( $item, $cid ) {
		if(is_array($item)) {
			foreach ($item as $key) {
				$this->ci->quest->updateQuest( $cid, 2, $key );
				$this->ci->db->where('item', $key)->where('cid', $cid)->limit(1)->delete('characters_inventory');
			}
		}else{
			$this->ci->quest->updateQuest( $cid, 2, $item );
			$this->ci->db->where('item', $item)->where('cid', $cid)->limit(1)->delete('characters_inventory');
		}		
	}

	function getCharacterResources( $cid, $player_data = NULL ) {
		if($cid && !$player_data) {
			$player_data = $this->getCharacterData( NULL, $cid );
		}elseif(!$cid && !$player_data){
			return FALSE;
		}

		foreach($this->resources as $resource => $res_data) {
			$current = $player_data->$res_data['stat'];
			if($player_data->equip) {
				foreach( $this->equip_slots as $slot ) {
					if (array_key_exists($slot, $player_data->equip)) {
						if ($player_data->equip[$slot]['id'] != 0) {
							$current += $player_data->equip[$slot][$res_data['stat']];
						}
					}
				}
			}
			$var_max = $resource.'_max';

			$resource_data[$var_max] = ( $current * $res_data['stat_factor'] ) + ( $player_data->level * $res_data['level_factor'] );
			if($player_data->$var_max == 0) {
				$resource_data[$resource] = $resource_data[$var_max];
			}else{
				$resource_data[$resource] = $player_data->$resource;
			}
		}

		$this->ci->db->where('cid', $player_data->cid)->update('characters', $resource_data);
		return $resource_data;
	}

	function updateCharacterResource( $resource, $cid, $amount, $current = NULL ) {
		if($cid) {
			$character = $this->ci->db->where('cid', $cid)->get('characters')->row();
			if(!$character) 
				return FALSE;

			if($amount > $character->{$resource}) {
				$data[$resource] = 0;
			}else{
				$data[$resource] = $character->{$resource} + $amount;
			}

			$this->ci->db->where('cid', $cid)->update('characters', $data);
			return $data[$resource];
		}elseif(!$cid && $current) {
			if($amount > $current) {
				$data[$resource] = 0;
			}else{
				$data[$resource] = $current + $amount;
			}
			$this->ci->db->where('cid', $cid)->update('characters', $data);
			return $data[$resource];
		}else{
			return FALSE;
		}
	}

	function showResourceBar( $resource, $value, $valueMax, $ownCharacter = FALSE ) {
		if($value > $valueMax) $value = $valueMax;
		$value = floor($value);
		if ($valueMax == 0) $valueMax = 1;
		$barSize = $value * 100 / $valueMax;
		if ($barSize > 100) {
			$barSize = 100;
		}

		switch($resource) {
			case 1: $attr = 'bar-success'; if($ownCharacter) {$class_o = 'character-health';}else{$class_o = '';}  break;
			case 2: $attr = ''; if($ownCharacter) {$class_o = 'character-mana';}else{$class_o = '';} break;
			case 3: $attr = 'bar-warning'; $class_o = ''; break;
			default: $attr = ''; $class_o = '';
		}

		if($resource == 1) {
			$class = 'progress-big';
		}else{
			$class = '';
		}

		$text  = '<div class="progress '.$class.' '.$class_o.'">';
		$text .= '<div class="bar '.$attr.'" style="width: '.floor($barSize).'%;"></div>';
		$text .= '<span>'.$value.' / '.$valueMax.'</span>';
		$text .= '</div>';

		return $text;
	}

	function getCharacterStats( $player ) {

		$stat['sta'] = 0;
		$stat['int'] = 0;
		$stat['str'] = 0;
		$stat['dex'] = 0;
		$stat['luc'] = 0;

		if($player->equip) {
			foreach( $this->equip_slots as $slot => $slot_id ) {
				if (array_key_exists($slot_id, $player->equip)) {
					if ($player->equip[$slot_id]['id'] != 0) {
						$stat['sta'] += $player->equip[$slot_id]['sta'];
						$stat['int'] += $player->equip[$slot_id]['int'];
						$stat['str'] += $player->equip[$slot_id]['str'];
						$stat['dex'] += $player->equip[$slot_id]['dex'];
						$stat['luc'] += $player->equip[$slot_id]['luc'];
					}
				}
			}
		}
		return $stat;
	}

	function getArmorReduction( $player, $enemy_lvl = 0 ) {
		if(!$enemy_lvl) {
			$enemy_lvl = $player->level;
		}

		$reduction = round(($player->combat['armor'] / ((70 * $enemy_lvl) + $player->combat['armor'] + 200)) * 100, 2);

		return $reduction;
	}

	function experienceNeeded ( $player_data ) {
		$exp_needed = floor(500 * pow(1.115, $player_data->level) * 1.5);

		return $exp_needed;
	}

	function experienceHandler ( $player_data ) {
		if (($player_data->xp_needed <= $player_data->xp) && $player_data->level < $this->maxlevel){

			$data = array('level' => $player_data->level + 1, 'xp' => $player_data->xp - $player_data->xp_needed);
			$next_level = $player_data->level + 1;

			$this->ci->db->where('cid', $player_data->cid);
			$this->ci->db->update('characters', $data);

			return TRUE;
		}else{
			return FALSE;
		}
	}

	function experienceChange ( $player_data, $value ) {
		$value = intval($value);
		if($player_data->level < $this->maxlevel) {
			$xp = array('xp'=>$player_data->xp + $value);

			$this->ci->db->where('cid', $player_data->cid);
			$this->ci->db->update('characters', $xp);

			return TRUE;
		}else{
			return FALSE;
		}
	}

	function isTravelling ( $cid ) {
		$query = $this->ci->db->where('cid', $cid)
								->get('travel');

		if($query->num_rows() > 0) {
			return true;
		}else{
			return false;
		}
	}

	function addTraveller ( $cid, $world_data, $travel_data ) {
		$player_data->guildData = $this->ci->characters->getGuildData($cid);
		$query_m = $this->ci->db->where('id', $travel_data['map'])
								->get('maps');
		$map = $query_m->row();

		$query_z = $this->ci->db->where('mapid', $travel_data['map'])
									->where('id', $travel_data['zone'])
									->get('zones');
		$zone = $query_z->row();

		$travel_data['time'] = 0;

		$start_real_x = $world_data->zone->x + $world_data->map->x;
		$start_real_y = $world_data->zone->y + $world_data->map->y;
		$end_real_x = $zone->x + $map->x;
		$end_real_y = $zone->y + $map->y;

		$distance = abs($start_real_x - $end_real_x) + abs($start_real_y - $end_real_y);
		$current_time = time();
		if($world_data->map->is_city == 1 && $map->is_city == 1) {
			$travel_data['time'] = $distance * 60;
		}else{
			$travel_data['time'] = $distance * 180;
		}

		if($player_data->guildData->level >= 3) {
			$travel_data['time'] /= 1.1;
			if($player_data->guildData->level >= 8) {
				$travel_data['time'] /= 1.2;
			}
		}
		
		$arrival_time = $current_time + ($travel_data['time']);

		$data = array(  'cid'    => $cid,
						'start_time' => $current_time,
						'end_time'   => $arrival_time,
						'start_map'  => $world_data->zone->mapid,
						'start_zone' => $world_data->zone->id,
						'end_map'    => $zone->mapid,
						'end_zone'   => $zone->id);

		$insert = $this->ci->db->insert('travel', $data);
	}

	function getTravelData( $cid ) {
		return $this->ci->db->where('cid', $cid)->get('travel')->row();
	}

	function updateLocation( $data ) {
		$update = array('map' => $data->end_map, 'zone' => $data->end_zone);

		$this->ci->db->where('cid', $data->cid);
		$this->ci->db->update('characters', $update);

		redirect('world/');
	}

	function hasCompletedQuest( $cid, $quest ) {
		$query = $this->ci->db->where('status', 2)->where('cid', $cid)->where('quest', $quest)->get('characters_queststatus');
		if( $query->num_rows() > 0 ) {
			return true;
		}else{
			return 0;
		}
	}

	function getCharacterQueststatusData( $cid, $quest ) {
		$query = $this->ci->db->where('cid', $cid)->where('quest', $quest)->get('characters_queststatus');
		return $query->row();
	}

	function getCharacterQuestData( $cid ) {
		$queststatus_query = $this->ci->db->where('cid', $cid)->get('characters_queststatus');
		$quest_data = array();

		if($queststatus_query->num_rows() > 0) {
			foreach($queststatus_query->result() as $queststatus) {			
				$quest = $this->ci->quest->getQuestData( $queststatus->quest );
				$quest['status'] = $queststatus->status;
				if($queststatus->status < 2) {
					for ($i = 1; $i <= 4; $i++) {
						if($quest['ReqCreatureId'.$i] != 0) {
							$creature_q = $this->ci->db->select('name')->where('id', $quest['ReqCreatureId'.$i])->get('creature_template');
							$creature = $creature_q->row_array();
							$quest['ReqCreatureName'.$i] = $creature['name'];
							$mobcounti = 'mobcount'.$i;
							$quest['ReqCreatureDone'.$i] = $queststatus->$mobcounti;
						}
					}
					for ($i = 1; $i <= 4; $i++) {
						if($quest['ReqItemId'.$i] != 0) {
							$item_q = $this->ci->db->select('name')->where('id', $quest['ReqItemId'.$i])->get('item_template');
							$item = $item_q->row_array();
							$quest['ReqItemName'.$i] = $item['name'];
							$itemcounti = 'itemcount'.$i;
							$quest['ReqItemDone'.$i] = $this->ci->characters->getItemCountInInventory( $quest['ReqItemId'.$i], $cid );
						}
					}
					$quest_data[$queststatus->quest] = $quest;
				}
			}
		}

		return $quest_data;
	}

	function getGuildData( $cid ) {
		$query = $this->ci->db->query("
		SELECT 
		`guild`.`name`,
		`guild`.`level`,
		`guild`.`guildid`,
		`guild`.`leader`
		FROM `guild` AS `guild`
		LEFT JOIN `guild_member` AS `guild_member` ON `guild`.`guildid`=`guild_member`.`guildid`
		WHERE `guild_member`.`cid` = ".$cid." ");

		if( $query->num_rows() > 0 ) {
			$query = $query->row();
			$result->id = $query->guildid;
			$result->name = $query->name;
			$result->level = $query->level;
			$result->leader = $query->leader;
			return $result;			
		}else{
			$result->id = 0;
			$result->name = "";
			$result->level = 0;
			$result->leader = 0;
			return $result;			
		}
	}

	function getCharacterLocation( $cid ) {
		$query = $this->ci->db->where('cid', $cid)->get('characters');
		$result = $query->row();

		$return->map = $result->map;
		$return->zone= $result->zone;

		return $return;
	}
}
?>