<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Spell class
 *
 */
class Spell
{
	function __construct()
	{
		$this->ci =& get_instance();
	}

	// Check if Character $cid can cast spell $spellID 
	function canCast( $cid, $spellID ) {
		//$spellRow = $this->ci->db->where('cid', $cid)->where('spellID', $spellID)->get('characters_spelldata')->row();
		$spellRow = $this->ci->db->query('SELECT * FROM `characters`
			INNER JOIN `characters_spelldata` ON `characters`.`cid` = `characters_spelldata`.`cid` 
			INNER JOIN `spell_template` ON `characters_spelldata`.`spellID` = `spell_template`.`id` 
			WHERE `characters`.`cid` = '.$cid.' AND `characters_spelldata`.`spellID` = '.$spellID)->row();

		if($spellRow && !$this->onCooldown($cid, $spellID) && $spellRow->mana >= $spellRow->spellCost) {
			return TRUE;
		}else{
			return FALSE;
		}
	}

	function getSpellData( $cid, $spellID ) {
		$spellRow = $this->ci->db->query('SELECT * FROM `spell_template` 
			INNER JOIN `characters_spelldata` ON `spell_template`.`id` = `characters_spelldata`.`spellID` 
			WHERE `spell_template`.`id` = '.$spellID.' AND `characters_spelldata`.`cid` = '.$cid)->row();

		if($spellRow) {
			return $spellRow;
		}else{
			return FALSE;
		}
	}

	// Check if Character's spell $spellID is on cooldown
	function onCooldown( $spellRow ) {
		if($spellRow) {
			if($spellRow->lastCast + $spellRow->spellCooldown > time()) {
				return TRUE;
			}
		}
		return FALSE;
	}

	function getCooldown( $spellRow ) {
		if($spellRow) {
			$timeLeft = ($spellRow->lastCast + $spellRow->spellCooldown) - time();
			if($timeLeft < 0) {
				return FALSE;
			}else{
				return $timeLeft;
			}
		}else{
			return FALSE;
		}
	}

	function Spell( $cid, $spellID ) {
		$spellRow = $this->getSpellData($cid, $spellID);
		if(!$spellRow) return FALSE;

		$name =  '<div class="spell" style="margin: 0 auto;">';

		$name .= '<div class="spellContainer">';
					$name .= '<a class="spell">';
		if( file_exists('assets/images/icons/spells/'.$spellRow->spellIcon) ) {
			$path = '/assets/images/icons/spells/'.$spellRow->spellIcon;
		}else{
			$path = '/assets/images/icons/no-image2.jpg';
		}

		$spellData = unserialize($spellRow->spellData);

		$name .= '<img src="'.base_url($path).'" class="q5" />';
		$name .= '<span class="frame"></span></a>'; // a.item
		$name .= '</div>'; // .slot

		$schools = array("Physical", "Fire", "Frost", "Earth", "Void");
		$elements = array("%physical%", "%fire%", "%frost%", "%earth%", "%void%");

		foreach($elements as $key => $e_replace) {
			if(isset($spellData['damage'][$key])) {	
				$replace_str = '<span class="epic-font '.$schools[$key].'-damage">'.$spellData['damage'][$key].' '.$schools[$key].'</span> damage';
				$spellRow->spellDescription = str_replace($e_replace, $replace_str, $spellRow->spellDescription);
			}
		}
		if(isset($spellData['heal'])) {
			$spellRow->spellDescription = str_replace('%healing%', '<span class="epic-font light-healing">'.$spellData['heal'].'</span> hitpoints', $spellRow->spellDescription);
		}
		
		$name .= '<div class="tooltip">';
		$name .= '<div class="row-fluid"><div class="span12"><h3 class="q5">'.$spellRow->spellName.'</h3></div></div>';
		$name .= '<div class="row-fluid"><div class="span6">'.$spellRow->spellCost.' Mana</div><div class="span6 text-right">'.$spellRow->spellCooldown.'s cooldown</div></div>';
		$name .= '<div class="row-fluid"><div class="span12">'.$spellRow->spellDescription.'</div></div>';
		$name .= '</div>'; // .tooltip
		$name .= '</div>'; // .item

		return $name;
	}

	function Cast( $cid, $spellID, $data ) {
		$uid = $this->ci->db->where('cid', $cid)->get('characters')->row()->uid;
		if(!$uid) {
			return FALSE;
		}
		
		$character = $this->characters->getCharacterData( $uid, $cid, 1);
		$spellRow = $this->getSpellData($cid, $spellID);

		if($this->canCast($cid, $spellID) && !$this->onCooldown($spellRow)) {
			$spellRow->spellData = unserialize($spellRow->spellData);
			foreach($data->targets as $_target) {

			}

			// Set cooldown
			$this->ci->db->where('cid', $cid)->where('spellID', $spellID)->update('characters_spelldata', array('lastCast' => time()));
			// Subtract mana
			$this->ci->db->where('cid', $cid)->update('characters', array('mana_max' => $character->mana_max - $spellRow->spellCost));

			return $result;
		}else{
			return FALSE;
		}
	}
}