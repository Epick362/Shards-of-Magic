<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Item class
 *
 */
class Item
{
	function __construct()
	{
		$this->ci =& get_instance();

		$this->item_stats = array('sta'=>'Stamina', 'int'=>'Intellect', 'str'=>'Strength', 'dex'=>'Dexterity', 'luc'=>'Luck');
		$this->equip_names = array(1 => 'Main Hand', 2 => 'Off hand', 3 => 'Head', 4 => 'Shoulders', 5 => 'Back', 6 => 'Chest', 7 => 'Hands', 8 => 'Waist', 9 => 'Legs', 10 => 'Boots', 11 => 'Amulet' );
		$this->quality_names = array(0=>'Trash', 1=>'Common', 2=>'Magic', 3=>'Rare', 4=>'Epic', 5=>'Ancient');
		$this->subclasses = array(0 => '', 1 => 'Cloth', 2 => 'Leather', 3 => 'Plate');
	}

	function getItemColor( $item ) {
		$item['name'] = '<p class="q'. $item['quality'] .'" >'. $item['name'] .'</p>';
		return $item['name'];
	}

	function addItemTooltip( $item = 0, $mode = 0, $level = 1, $canEquip = FALSE, $equip_slot = 0 ) {
		if($item) {
			$name =  '<div class="item">';

			$name .= '<div class="slot" id="'.$item['id'].'" '.($mode > 2 ? 'action="'.$mode.'"' : "").'>';
			if($mode == 1 && ($item['class'] == 1 || $item['class'] == 2)) {
				$name .= '<a href="'.base_url('character/equip/item/'.$item['id'].'/').'" class="item">';
			}elseif($mode == 2 && ($item['class'] == 1 || $item['class'] == 2)) {
				$name .= '<a href="'.base_url('character/unequip/item/'.$item['id'].'/').'" class="item">';
			}elseif($mode == 3 || $mode == 4) {
				$name .= '<a class="item cursor-trade">';				
			}else{
				$name .= '<a class="item">';
			}
			if( file_exists('assets/images/icons/'.$item['image_path']) ) {
				$path = '/assets/images/icons/'.$item['image_path'];
			}else{
				$path = '/assets/images/icons/no-image2.jpg';
			}
			$name .= '<img src="'.base_url($path).'" class="q'.$item['quality'].'" />';
			$name .= '<span class="frame"></span></a>'; // a.item
			$name .= '</div>'; // .slot

			$name .= '<div class="tooltip">';
			$name .= '<div class="row-fluid"><div class="span12"><h3 class="q'.$item['quality'].'">'.$item['name'].'</h3></div></div>';
			if( $item['class'] == 3 ) {
				$name .= '<div class="row-fluid"><div class="span12"><i>Quest Item</i></div></div>';
			}
			$name .= '<div class="row-fluid"><div class="span12"><small class="q'.$item['quality'].'">'.$this->quality_names[$item['quality']].'</small></div></div>';
			if ( $item['class'] == 1 ) {
				$name .= '<div class="row-fluid">';
				$name .= '<div class="span6">'. $this->equip_names[$item['equip_slot']].'</div>';
				if( $item['equip_slot'] != 11 || $item['equip_slot'] != 5 ) {
					$name .= '<div class="span6 text-right '.(!$canEquip ? "text-error" : "").'">'. $this->subclasses[$item['subclass']] .'</div>';
				}
				$name .= '</div>';
			}elseif ( $item['class'] == 2 ) {
				$name .= '<div class="row-fluid"><div class="span6">'.$item['weapon_type_wield'].'</div><div class="span6 text-right">'.$item['weapon_type_name'].'</div></div>';
				$name .= '<div class="row-fluid"><div class="span12">'.$item['min_damage'].' - '.$item['max_damage'].' Damage</div></div>';
			}
			if ( $item['armor'] != 0 ) {
				$name .= '<div class="row-fluid"><div class="span12">'.$item['armor'].' Armor</div></div>';	
			}
			$name .= '<ul class="unstyled">';	
			foreach( $this->item_stats as $stat=>$stat_name ) {
				if($item[$stat] != 0) {
					$name .= '<li>+'.$item[$stat].' '.$stat_name.'</li>';
				}
			}
			$name .= '</ul>';
			if( $item['class'] != 3 ) {
				if ( $level >= $item['RequiredLevel'] ) {
					$name .= 'Requires Level '.$item['RequiredLevel'].'<br />';
				}else{
					$name .= '<span style="color: red;">Requires Level '.$item['RequiredLevel'].'</span><br />';
				}
				$name .= '<small><i>Sell price: '. $this->ci->core->showMoney($item['cost']) .'</i></small>';
			}
			$name .= '</div>'; // .tooltip
			$name .= '</div>'; // .item
		}else{
			$name =  '<div class="item">';
			$name .= '<div class="slot">';
			$name .= '<div class="slot-inner">';
			$name .= '<div class="slot-contents">';
			$name .= '<a class="item">';
			$name .= '<img src="'.base_url('/assets/images/icons/no-image.jpg').'" style="border:2px solid #333333;">';
			$name .= '<span class="frame"></span></a>';
			$name .= '</div>';
			$name .= '</div>';
			$name .= '</div>';
			$name .= '<div class="tooltip">';
			$name .= 'Empty Slot';
			if($equip_slot != 0) {
				$name .= '<br />'.$this->equip_names[$equip_slot];
			}
			$name .= '</div>';
			$name .= '</div>';			
		}
		return $name;
	}
}
?>