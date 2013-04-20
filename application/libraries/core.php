<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Core class
 *
 */
class Core
{
	function __construct()
	{
		$this->ci =& get_instance();
	}

	function groupArray(array $arrayOfArrays, $key) {
		$result = array();
		
		foreach($arrayOfArrays as $array)
			if( ! empty($array[$key]) )
				 $result[ $array[$key] ] = $array;

		return $result;
	}

	function time_since($original) {
	    // array of time period chunks
	    $chunks = array(
	        array(60 * 60 * 24 * 365 , 'year'),
	        array(60 * 60 * 24 * 30 , 'month'),
	        array(60 * 60 * 24 * 7, 'week'),
	        array(60 * 60 * 24 , 'day'),
	        array(60 * 60 , 'hour'),
	        array(60 , 'minute'),
	    );
	   
	    $today = time(); /* Current unix time  */
	    $since = $today - $original;
	   
	    // $j saves performing the count function each time around the loop
	    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
	       
	        $seconds = $chunks[$i][0];
	        $name = $chunks[$i][1];
	       
	        // finding the biggest chunk (if the chunk fits, break)
	        if (($count = floor($since / $seconds)) != 0) {
	            // DEBUG print "<!-- It's $name -->\n";
	            break;
	        }
	    }
	   
	    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
	   
	    if ($i + 1 < $j) {
	        // now getting the second item
	        $seconds2 = $chunks[$i + 1][0];
	        $name2 = $chunks[$i + 1][1];
	       
	        // add second item if it's greater than 0
	        if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
	            $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
	        }
	    }
	    return $print;
	}

	function getCharacterName( $uid ) {
		$get_data = $this->ci->db->select('username')
								->where('id', $uid)
								->get('users');
		$data = $get_data->row();
		
		if($get_data->num_rows() == 1) {
			$data = $get_data->row();	
			return $data->username;
		}else{
			return FALSE;
		}
	}

	function getCharacterLevel( $uid ) {
		$get_data = $this->ci->db->select('level')
								->where('user_id', $uid)
								->get('characters');
		$data = $get_data->row();
		
		return $data->level;
	}

	function getCharacterMoney( $uid ) {
		$get_data = $this->ci->db->select('money')
								->where('user_id', $uid)
								->get('characters');
		$data = $get_data->row();
		
		return $data->money;
	}

	function getCharacterUID( $username ) {
		$get_data = $this->ci->db->select('id')
								->where('username', $username)
								->get('users');
		
		if($get_data->num_rows() == 1) {
			$data = $get_data->row();	
			return $data->id;
		}else{
			return FALSE;
		}
	}

	function displayMessage( $type, $message ) {
		switch($type) {
			case 1: 
				$mes_type = 'error';
				break;
			case 2: 
				$mes_type = 'success';
				break;
			case 3: 
				$mes_type = 'note';
				break;
		}
		$final_message = "";
		if(is_array($message)) {
			foreach($message as $i => $msg) {
				$final_message .= '<p class="message '.$mes_type.'">'.$msg.'</p>';
			}
		}else{
			$final_message = '<p class="message '.$mes_type.'">'.$message.'</p>';
		}
		$this->ci->session->set_flashdata('message', $final_message);
	}

	function displayModal( $header_text, $body_text, $footer_text, $unique_id ) {
		$content = "<div class=\"modal hide fade\" id=\"".$unique_id."\">";
		$content .= "<div class=\"modal-header\">";
		$content .= $header_text;
		$content .= "</div>";
		$content .= "<div class=\"modal-body\">";
		$content .= $body_text;
		$content .= "<hr />";
		$content .= "</div>";		
		$content .= "<div class=\"modal-footer\">";
		$content .= $footer_text;
		$content .= "<a class=\"btn btn-danger\" data-dismiss=\"modal\" style=\"float:right;\"><span class=\"red\">Cancel</span></a>";	
		$content .= "</div>";
		$content .= "</div>";	

		return $content;			
	}

	function trim_text($input, $length, $ellipses = true, $strip_html = true) {
		//strip tags, if desired
		if ($strip_html) {
			$input = strip_tags($input);
		}
	
		//no need to trim, already shorter than trim length
		if (strlen($input) <= $length) {
			return $input;
		}
	
		//find last space within length
		$last_space = strrpos(substr($input, 0, $length), ' ');
		$trimmed_text = substr($input, 0, $last_space);
	
		//add ellipses (...)
		if ($ellipses) {
			$trimmed_text .= '...';
		}
	
		return $trimmed_text;
	}

	function getItemData( $item_id, $equipped = 0 ) {
		if( is_array($item_id) ) {
			$get_data = $this->ci->db->select('*')->where_in('id', $item_id)->get('item_template');	
			$items_data = array();
			foreach( $get_data->result() as $item ) {
				$item = get_object_vars($item);

				if( $item['class'] == 2 ) {

					$item['dps'] = ($item['min_damage'] + $item['max_damage']) / 2;

					switch( $item['subclass'] ) {
						case 1:
							$item['weapon_type_name'] = "Axe";
							break;
						case 2:
							$item['weapon_type_name'] = "Mace";
							break;
						case 3:
							$item['weapon_type_name'] = "Sword";
							break;
						case 4:
							$item['weapon_type_name'] = "Staff";
							break;
						case 5:
							$item['weapon_type_name'] = "Dagger";
							break;
						case 6:
							$item['weapon_type_name'] = "Bow";
							break;
						default:
							$item['weapon_type_name'] = "Error";
					}
					switch( $item['equip_slot'] ) {
						case 1:
							$item['weapon_type_wield']= "Main Hand";
							break;
						case 2:
							$item['weapon_type_wield']= "Off Hand";
							break;
						default:
							$item['weapon_type_wield']= "Error";
					}
				}
				if( $equipped ) {
					$items_data[$item['equip_slot']] = $item;
				}else{
					$items_data[] = $item;
				}
			}

			return $items_data;
		}else{
			$get_data = $this->ci->db->select('*')->where('id', $item_id)->get('item_template');	
			
			if($get_data->num_rows() > 0 ) {

				$item = $get_data->row_array();

				if( $item['class'] == 2 ) {

					$item['dps'] = ($item['min_damage'] + $item['max_damage']) / 2;

					switch( $item['subclass'] ) {
						case 1:
							$item['weapon_type_name'] = "Axe";
							break;
						case 2:
							$item['weapon_type_name'] = "Mace";
							break;
						case 3:
							$item['weapon_type_name'] = "Sword";
							break;
						case 4:
							$item['weapon_type_name'] = "Staff";
							break;
						case 5:
							$item['weapon_type_name'] = "Dagger";
							break;
						case 6:
							$item['weapon_type_name'] = "Bow";
							break;
						default:
							$item['weapon_type_name'] = "Error";
					}
					switch( $item['equip_slot'] ) {
						case 1:
							$item['weapon_type_wield']= "Main Hand";
							break;
						case 2:
							$item['weapon_type_wield']= "Off Hand";
							break;
						default:
							$item['weapon_type_wield']= "Error";
					}
				}
				return $item;
			}else{
				return FALSE;	
			}
		}
	}

	function getChatFriendData( $uid ) {
		$query = $this->ci->db->where('user_id !=', $uid)->get('characters');
		# TODO
		$characters = $query->result();

		foreach($characters as $character) {
			$class_data = $this->getClassData($character->class);
			$result[] = "<div class=\"chatboxfriend\">".$this->getClassIcon($character->class, 16)." <span class=\"numbers\">".$character->level."</span> <a href=\"javascript:void(0)\" class=\"epic-font\" style=\"color:".$class_data['color']."\" onclick=\"javascript:chatWith('".$this->getCharacterName($character->user_id)."')\">".$this->getCharacterName($character->user_id)."</a></div>";
		}

		return $result;
	}

	function getFirstFreeInventorySlot( $uid ) {
		$query = $this->ci->db->select('*')
								->where('user_id', $uid)
								->get('characters_inventory');
		$query_a = $query->result_array();
		$inv = $this->ci->core->groupArray( $query_a, 'slot' );
		$i = 1;
		
		while($i <= 36) {
			if (!array_key_exists($i, $inv)) {
				$result = $i;
				break;
			}
			$i++;
		}

		if($result) {
			return $result;
		}else{
			return FALSE;
		}
	}

	function countFreeSlotsInInventory( $uid ) {
		$query = $this->ci->db->select('*')
								->where('user_id', $uid)
								->get('characters_inventory');
		$query_a = $query->result_array();
		$inv = $this->ci->core->groupArray( $query_a, 'slot' );
		
		$i = 1;
		$count = 0;
		
		while($i <= 36) {
			if (!array_key_exists($i, $inv)) {
				$count ++;
			}
			$i++;
		}

		if($count != 0) {
			return $count;
		}else{
			return FALSE;
		}
	}

	function getClassData( $class_id ) {

		switch( $class_id ) {
			case 1:
				$class_data['name'] = "Mage";
				$class_data['color']= "#68CCEF";
				$class_data['image']= "magician.png";
				$class_data['desc'] = "DEVELOPMENT";
				$class_data['can_equip'] = array( 0, 1 );
				break;
			case 2:
				$class_data['name'] = "Thief";
				$class_data['color']= "#1C6916";
				$class_data['image']= "thief.png";
				$class_data['desc'] = "Masters of close combat.";
				$class_data['can_equip'] = array( 0, 1, 2 );
				break;
			case 3:
				$class_data['name'] = "Crusader";
				$class_data['color']= "#FFF468";
				$class_data['image']= "crusader.png";
				$class_data['desc'] = "Holy power courses through their veins.";
				$class_data['can_equip'] = array( 0, 1, 2, 3 );
				break;
			case 4:
				$class_data['name'] = "Priest";
				$class_data['color']= "#F0EBE0";
				$class_data['image']= "priest.png";
				$class_data['desc'] = "DEVELOPMENT";
				$class_data['can_equip'] = array( 0, 1 );
				break;
			case 5:
				$class_data['name'] = "Scout";
				$class_data['color']= "#AAD372";
				$class_data['image']= "scout.png";
				$class_data['desc'] = "DEVELOPMENT";
				$class_data['can_equip'] = array( 0, 1, 2 );
				break;
			case 6:
				$class_data['name'] = "Berserker";
				$class_data['color']= "#AD1515";
				$class_data['image']= "berserker.png";
				$class_data['desc'] = "DEVELOPMENT";
				$class_data['can_equip'] = array( 0, 1, 2, 3 );
				break;
		}
		return $class_data;
	}

	function getClassIcon($class, $size = 32) {
		$class_data = $this->getClassData($class);

		return "<div class=\"icon-frame frame".$size."\"><img src=\"". base_url('assets/images/classes/'.$class_data['image'].'') ."\" style=\"vertical-align:top;width:".$size."px;height:".$size."px;\"/></div>";
	}

	function getGenderName( $gender_id ) {
		$gender_id == 0 ? $gender_name = "Male" : $gender_name = "Female";

		return $gender_name;
	}

	function sendMessage( $sender, $recipient, $subject, $message, $time ) {
		$add_message = array('from' => $sender,
							'to' => $recipient,
							'subject' => $subject,
							'message' => $message,
							'sent' => $time);
		$add_message_q = $this->ci->db->insert('messages', $add_message);

		return TRUE;
	}
	
	function showMoney( $money ) {
		$money = intval( $money );

		$finalString = '';

		$specs = array(1 => 'copper', 100 => 'silver', 10000 => 'gold');
		krsort($specs);

		foreach($specs as $value => $name) {
			$number = floor($money / $value);

			if ( $number != 0 ) {
				$finalString .= "<span class=\"numbers\">" . $number ."</span><img src=\"".base_url('/assets/images/core/'.$name.'.png')."\" class=\"money-icon\" />";
			}

			$money -= $number * $value;
		}

		if ( !$finalString ) {
			$finalString = "<span class=\"numbers\">0</span><img src=\"".base_url('/assets/images/core/copper.png')."\" class=\"money-icon\" />";
		}

		return ltrim($finalString);
	}

	function getWorldData( $uid ) {
		$query = $this->ci->db->select('map, zone')
								->where('user_id', $uid)
								->get('characters');
		$character = $query->row();

		$query_m = $this->ci->db->select('*')->get('maps');
		$query_m = $query_m->result();

		$query_map = $this->ci->db->select('*')
								->where('id', $character->map)
								->get('maps');
		$query_map = $query_map->row();

		$query_z = $this->ci->db->select('*')->get('zones');
		$query_z = $query_z->result();

		$query_zone = $this->ci->db->select('*')
									->where('mapid', $character->map)
									->where('id', $character->zone)
									->get('zones');
		$query_zone = $query_zone->row();

		$maps = array();
		$zones = array();
		$data->zone = $query_zone;

		foreach($query_m as $map) {
			$maps[$map->x][$map->y] = $map;
			foreach($query_z as $zone) {
				$zones[$zone->mapid][$zone->x][$zone->y] = $zone;
			}
		}
		$data->map = $query_map;
		$data->maps= $maps;

		$data->zones = $zones;

		return $data;
	}

	function getZoneData( $dest_data ) {
		$query_m = $this->ci->db->select('x, y, name')
							->where('id', $dest_data->end_map)
							->get('maps');
		$map = $query_m->row();

		$query_z = $this->ci->db->select('*')
							->where('mapid', $dest_data->end_map)
							->where('id', $dest_data->end_zone)
							->get('zones');

		$zone = $query_z->row();
		$zone->real_x = $map->x + $zone->x;
		$zone->real_y = $map->y + $zone->y;

		$data->zone = $zone;
		$data->map->name = $map->name;

		return $data;
	}

	function getMapName( $map_id ) {
		$query_m = $this->ci->db->select('name')->where('id', $map_id)->get('maps');
		$map = $query_m->row();	

		return $map->name;	
	}

	function countNewMessages( $uid ) {		
		$query = $this->ci->db->select('id')
							->where('to', $this->getCharacterName( $uid ))
							->where('unread', 1)
							->get('messages');
		return $query->num_rows();		
	}
}
?>
