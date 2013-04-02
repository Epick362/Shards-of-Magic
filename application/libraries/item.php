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
		$item['name'] = "<p class=\"q". $item['quality'] ."\" >". $item['name'] ."</p>";
		return $item['name'];
	}

	function addItemTooltip( $item = 0, $mode = 0, $level = 1, $canEquip = FALSE, $equip_slot = 0 ) {
		if ($item) {
			$name = "<div class=\"item_tooltip\">";
			if( $mode == 3 ) {
				$name .= "<div class=\"slot\" id=\"".$item['id']."\" action=\"3\">";
			}elseif( $mode == 4 ){
				$name .= "<div class=\"slot\" id=\"".$item['id']."\" action=\"4\">";
			}else{
				$name .= "<div class=\"slot\">";
			}
			$name .= "	<div class=\"slot-inner\">";
			$name .= "		<div class=\"slot-contents\">";
			if($mode == 1 && ($item['class'] == 1 || $item['class'] == 2)) {
				$name .= "		<a href=\"".base_url('character/equip/item/'.$item['id'].'/')."\" class=\"item\">";
			}elseif($mode == 2 && ($item['class'] == 1 || $item['class'] == 2)){
				$name .= "		<a href=\"".base_url('character/unequip/item/'.$item['id'].'/')."\" class=\"item\">";
			}elseif($mode == 3 || $mode == 4){
				$name .= "		<a class=\"item cursor-trade\">";				
			}else{
				$name .= "		<a class=\"item\">";
			}
			if( file_exists('assets/images/icons/'.$item['image_path']) ) {
				$name .= "			<img src=\"".base_url('/assets/images/icons/'.$item['image_path'].'')."\" class=\"q".$item['quality']."\">";
			}else{
				$name .= "			<img src=\"".base_url('/assets/images/icons/no-image2.jpg')."\" class=\"q".$item['quality']."\">";
			}
			$name .= "			<span class=\"frame\"></span></a>";
			$name .= "		</div>";
			$name .= "	</div>";
			$name .= "</div>";
			$name .= "		<div class=\"tooltip\">";
			$name .= "			<table style=\"width: 300px; \">";
			$name .= "				<tr>";
			$name .= "					<td>";
			$name .= "						<h2 class=\"q".$item['quality']."\">".$item['name']."</h2>";
			if( $item['class'] == 3 ) {
				$name .= "						<i>Quest Item</i><br />";
			}
			$name .= "						<small><span class=\"q".$item['quality']."\">";
			$name .= "							".$this->quality_names[$item['quality']]."";
			$name .= "						</span></small><br />";
			if ( $item['class'] == 1 ) {
				$name .= "						". $this->equip_names[$item['equip_slot']];
				if( $item['equip_slot'] != 11 || $item['equip_slot'] != 5 ) {
					if( $canEquip ) {
						$name .= "						<div style=\"float: right;\">". $this->subclasses[$item['subclass']] ."</div><br />";
					}else{
						$name .= "						<div style=\"float: right; color: red;\">". $this->subclasses[$item['subclass']] ."</div><br />";						
					}
				}
			}
			if ( $item['class'] == 2 ) {
				$name .= "						".$item['weapon_type_wield']."";
				$name .= "						<div style=\"float: right;\">".$item['weapon_type_name']."</div><br />";
				$name .= "						".$item['min_damage']." - ".$item['max_damage']." Damage<br />";
				$name .= "						(".$item['dps']." damage per second)<br>";
			}
			if ( $item['armor'] != 0 ) {
				$name .= "						".$item['armor']." Armor";	
			}	
			$name .= "						<ul>";	
			foreach( $this->item_stats as $stat=>$stat_name ) {
				if($item[$stat] != 0) {
					$name .= "						<li>+".$item[$stat]." ".$stat_name."</li>";
				}
			}
			$name .= "						</ul>";
			if( $item['class'] != 3 ) {
				if ( $level >= $item['RequiredLevel'] ) {
					$name .= "						Requires Level ".$item['RequiredLevel']."<br />";
				}else{
					$name .= "						<span style=\"color: red;\">Requires Level ".$item['RequiredLevel']."</span><br />";
				}
				$name .= "						<small><i>Sell price: ". $this->ci->core->showMoney($item['cost']) ."</i></small>";
			}
			$name .= "					</td>";
			$name .= "				</tr>";
			$name .= "			</table>";
			$name .= "		</div>";
			$name .= "</div>";
		}else{  // NO ITEM
			$name = "<div class=\"item_tooltip\">";
			$name .= "<div class=\"slot\">";
			$name .= "	<div class=\"slot-inner\">";
			$name .= "		<div class=\"slot-contents\">";
			$name .= "			<a class=\"item\">";
			$name .= "				<img src=\"".base_url('/assets/images/icons/no-image.jpg')."\" style=\"border:2px solid #333333;\">";
			$name .= "			<span class=\"frame\"></span></a>";
			$name .= "		</div>";
			$name .= "	</div>";
			$name .= "</div>";
			$name .= "		<div class=\"tooltip\">";
			$name .= "			Empty Slot";
			if($equip_slot != 0) {
				$name .= "<br />".$this->equip_names[$equip_slot];
			}
			$name .= "		</div>";
			$name .= "</div>";			
		}
		return $name;
	}
}
?>