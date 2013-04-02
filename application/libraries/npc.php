<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * NPC class
 *
 */
class Npc
{
	function __construct()
	{
		$this->ci =& get_instance();
	}

	function getCreaturesInZone( $data ) {
		$query  = $this->ci->db->select('*')
								->where('map', $data->map->id)
								->where('zone', $data->zone->id)
								->get('creature_locations');
		$creatures = array();

		foreach ($query->result() as $row) {
			$data = $this->getCreatureData( $row->id, $row->guid );
			$data->level = $row->level;
			$creatures[$row->guid] = $data;
		}

		return $creatures;
	}

	function getCreatureData( $entry, $guid = 0 ) {
		$query = $this->ci->db->where('id', $entry)->get('creature_template');
		if($guid) {
			$query2= $this->ci->db->select('*')->where('guid', $guid)->get('creature_locations');
			if(!$query2->result()) {
				return FALSE;
			}else{
				$spawned_data = $query2->row();
			}
		}
		if(!$query->result()) {
			return FALSE;
		}
		$data  = $query->row();

		switch($data->class) {
			case 1: // Mage
				$base = array('sta' => 18, 'int' => 23);
				break;
			case 2: // Thief
				$base = array('sta' => 19, 'int' => 12);
				break;
			case 3: // Warrior
				$base = array('sta' => 22, 'int' => 10);
				break;
		}

		if($spawned_data) {
			$data->health = floor(($base['sta'] * pow(1.023, $spawned_data->level) * 2 * ( $spawned_data->level + 1 )) * $data->modHealth );
			$data->mana	= floor(($base['int'] * pow(1.023, $spawned_data->level) * 2 * ( $spawned_data->level + 1 )) * $data->modMana );
			$data->armor_reduction = round(($data->armor / ((95 * $spawned_data->level) + $data->armor + 600)) * 100, 2);
			$data->curhealth = $spawned_data->curhealth;
		}

		return $data;
	}

	function npcXP( $uid, $player, $opponent ) {
		$xp = ($player->level * 5) + 45;

		if( $opponent->level > $player->level ) {
			$xp = floor(( $xp ) * (1 + 0.05 * ( $opponent->level - $player->level )));
		}elseif( $player->level > $opponent->level && ($player->level - $opponent->level) < 4 ){
			$xp = floor(( $xp ) * (1 - ( $player->level - $opponent->level )/10 ));
		}elseif( ($player->level - $opponent->level) >= 4 ) {
			$xp = 0;
		}

		$this->ci->characters->experienceChange( $player, $xp );

		return $xp;
	}

	function HasQuest( $uid, $npc, $level ) {
		$cqr_q = $this->ci->db->where('id', $npc)->get('creature_questrelation');
		$result = array();
		if($cqr_q->num_rows() > 0) {
			$result['hasQuest'] = true;
			$cqr = $cqr_q->result();
			foreach ($cqr as $temp_cqr) {
				$quest_data = $this->ci->quest->getQuestData($temp_cqr->quest);
				if( $quest_data['QuestGroup'] && $quest_data['QuestGroupNumber'] != 1 ) {
					$prev_quest_q = $this->ci->db->select('id')->where('QuestGroup', $quest_data['QuestGroup'])->where('QuestGroupNumber', $quest_data['QuestGroupNumber'] - 1)->get('quest_template');
					$prev_quest = $prev_quest_q->row();
					$chain_check = $this->ci->characters->hasCompletedQuest( $uid, $prev_quest->id );
				}else{
					$chain_check = true;
				}
				if( $quest_data['MinLevel'] <= $level && $chain_check) {
					$cqs_q = $this->ci->db->where('user_id', $uid)->where('quest', $temp_cqr->quest)->get('characters_queststatus');
					if($cqs_q->num_rows() > 0) {
						$cqs = $cqs_q->row();
						$result[$temp_cqr->quest] = $cqs->status;
					}else{
						$result[$temp_cqr->quest] = -1;
					}
				}else{
					$result[$temp_cqr->quest] = -2;
				}
			}
		}else{
			$result['hasQuest'] = false;
		}
		return $result;
	}

	function showVendor( $npc ) {
		$cv_q = $this->ci->db->where('creature', $npc)->get('creature_vendor');
		if($cv_q->num_rows() > 0) {
			$return['isVendor'] = true;
			$cv = $cv_q->result();
			foreach( $cv as $temp_cv ) {
				$return['items'][] = $temp_cv->item;
			}
		}else{
			$return['isVendor'] = false;
		}
		return $return;
	}
}
?>