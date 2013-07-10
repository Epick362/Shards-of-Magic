<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Quest class
 *
 *
 *
 * Quest Status
 * 0 - Not Completed, Showing in Quest Log
 * 1 - Completed but not Rewarded
 * 2 - Completed and Rewarded, Not Showing in Quest
 *
 *
 *
 */
class Quest
{
	function __construct()
	{
		$this->ci =& get_instance();
	}

	function takeQuest( $cid, $quest, $level ) {
		$quest_data = $this->getQuestData( $quest );

		$queststatus_query = $this->ci->db->where('quest', $quest)->where('cid', $cid)->get('characters_queststatus');
		if($quest_data && $queststatus_query->num_rows() == 0 && $quest_data->MinLevel <= $level ) {
			$data = array('cid' => $cid, 'quest' => $quest);
			$this->ci->db->insert('characters_queststatus', $data);
			return true;
		}else{
			// Error message : quest does not exist
			return false;
		}
	}

	function getQuestData( $quest ) {
		$quest_query = $this->ci->db->where('id', $quest)->get('quest_template');
		if($quest_query->num_rows() > 0) {
			$quest_data = $quest_query->row_array();

			$quest_rewards = array( "RewItemId1" => "RewItemData1", "RewItemId2" => "RewItemData2", "RewItemId3" => "RewItemData3", "RewItemId4" => "RewItemData4" );

			for ($i=1; $i <= 4; $i++) { 
				$RewItemIdi = "RewItemId".$i;
				$RewItemDatai = "RewItemData".$i;

				if($quest_data['RewItemId'.$i] != 0) {
					$quest_data['RewItemData'.$i] = $this->ci->core->getItemData( $quest_data['RewItemId'.$i] );
					$quest_data['RewItemData'.$i]['image'] = $this->ci->item->addItemTooltip( $quest_data['RewItemData'.$i] );
				}else{
					$quest_data['RewItemData'.$i] = array();
				}

				if($quest_data['RewChoiceItemId'.$i] != 0) {
					$quest_data['RewChoiceItemData'.$i] = $this->ci->core->getItemData( $quest_data['RewChoiceItemId'.$i] );
					$quest_data['RewChoiceItemData'.$i]['image'] = $this->ci->item->addItemTooltip( $quest_data['RewChoiceItemData'.$i] );
				}else{
					$quest_data['RewChoiceItemData'.$i] = array();
				}

				if($quest_data['ReqCreatureId'.$i] != 0) {
					$creature_q = $this->ci->db->select('name')->where('id', $quest_data['ReqCreatureId'.$i])->get('creature_template');
					$creature = $creature_q->row_array();
					$quest_data['ReqCreatureName'.$i] = $creature['name'];
				}

				if($quest_data['ReqItemId'.$i] != 0) {
					$item_q = $this->ci->db->select('name')->where('id', $quest_data['ReqItemId'.$i])->get('item_template');
					$item = $item_q->row_array();
					$quest_data['ReqItemName'.$i] = $item['name'];
				}			
			}
			return $quest_data;
		}else{
			return false;
		}
	}

	function hasQuest( $quest, $cid ) {
		$queststatus_query = $this->ci->db->where('quest', $quest)->where('cid', $cid)->get('characters_queststatus');
		if($queststatus_query->num_rows() > 0) {
			return $queststatus_quest->row_array();
		}else{
			return array();
		}
	}

	function updateQuest( $cid, $type, $entity_id, $quest = FALSE ) {
		$message = array();
		$quest = array();

		if(!$quest) {
			$quests = $this->ci->db->or_where('ReqCreatureId1', $entity_id)->or_where('ReqCreatureId2', $entity_id)->or_where('ReqCreatureId3', $entity_id)->or_where('ReqCreatureId4', $entity_id)->or_where('ReqItemId1', $entity_id)->or_where('ReqItemId2', $entity_id)->or_where('ReqItemId3', $entity_id)->or_where('ReqItemId4', $entity_id)->get('quest_template');
			foreach($quests->result() as $quest_temp) {
				$has_quest_query = $this->ci->db->where('quest', $quest_temp->id)->where('cid', $cid)->get('characters_queststatus');
				if($has_quest_query->num_rows() > 0) {
					$quest[$quest_temp->id] = $quest_temp;
				}
			}
		}
		foreach($quest as $quest => $quest_data) {
			$queststatus_query = $this->ci->db->where('quest', $quest)->where('cid', $cid)->get('characters_queststatus');
			$queststatus_data = $queststatus_query->row_array();

			if($type == 1) { // MOB
				$type_name = 'mob';
				for($i = 1; $i <= 4; $i++) {
					$ReqCreatureIdi = 'ReqCreatureId'.$i;
					$ReqCreatureCounti = 'ReqCreatureCount'.$i;
					if($entity_id == $quest_data->$ReqCreatureIdi && ($queststatus_data[$type_name.'count'.$i] < $quest_data->$ReqCreatureCounti)) {
						$data = array( $type_name.'count'.$i => $queststatus_data[$type_name.'count'.$i] + 1);
						$this->ci->db->where('quest', $quest)->where('cid', $cid)->update('characters_queststatus', $data);
						$creature_data = $this->ci->db->select('name')->where('id', $quest_data->$ReqCreatureIdi)->get('creature_template');
						$creature = $creature_data->row();
						$message[] = '<p style="color:#eaba28;">'.$creature->name.' slain '.$data[$type_name.'count'.$i].'/'.$quest_data->$ReqCreatureCounti.'</p>';
					}
				}
			}else{
				for($i = 1; $i <= 4; $i++) {
					$ReqItemIdi = 'ReqItemId'.$i;
					$ReqItemCounti = 'ReqItemCount'.$i;
					if($entity_id == $quest_data->$ReqItemIdi) {
						$item_data = $this->ci->db->select('name')->where('id', $quest_data->$ReqItemIdi)->get('item_template');
						$item = $item_data->row();
						$message[] = $item->name.' acquired.';
					}
				}
			}

			$complete = $this->ci->quest->isComplete( $quest, $cid );
			if( $complete ) {			
				$message[] = '<p style="color:#eaba28;">Objective Complete</p>';
			}
		}
		return $message;
	}

	function isComplete( $quest, $cid ) {
		$queststatus_query = $this->ci->db->where('quest', $quest)->where('cid', $cid)->get('characters_queststatus');
		if( $queststatus_query->num_rows() > 0 ) {
			$queststatus_data = $queststatus_query->row_array();
			$quest_query = $this->ci->db->where('id', $quest)->get('quest_template');
			$quest_data = $quest_query->row_array();
			$check = true;
			for ($i = 1; $i <= 4; $i++) {
				$ReqCreatureIdi = 'ReqCreatureId'.$i;
				$ReqCreatureCounti = 'ReqCreatureCount'.$i;
				if( $quest_data[$ReqCreatureIdi] != 0 ) {
					if( $queststatus_data['mobcount'.$i] < $quest_data[$ReqCreatureCounti] ) {
						$check = false;
						break;
					}
				}
			}
			for ($i = 1; $i <= 4; $i++) {
				$ReqItemIdi = 'ReqItemId'.$i;
				$ReqItemCounti = 'ReqItemCount'.$i;
				if ( $quest_data[$ReqItemIdi] != 0 ) {
					if( $this->ci->characters->getItemCountInInventory( $quest_data[$ReqItemIdi], $uid ) < $quest_data[$ReqItemCounti] ) {
						$check = false;
						break;
					}
				}
			}

			if( $check ) {
				$data['status'] = 1;
				$this->ci->db->where('cid', $cid)->where('quest', $quest)->update('characters_queststatus', $data);
			}else{
				$data['status'] = 0;
				$this->ci->db->where('cid', $cid)->where('quest', $quest)->update('characters_queststatus', $data);
			}
			return $check;
		}else{
			return false;
		}
	}

	function RewardCharacter( $quest, $cid ) {
		$rewards = array('RewItemId1','RewItemId2','RewItemId3','RewItemId4');
		$quest_query = $this->ci->db->where('id', $quest)->get('quest_template');
		$quest_data = $quest_query->row_array();
		$items = array();
		// TAKE QUEST ITEMS
		for ($i = 1; $i <= 4; $i++) {
			$ReqItemIdi = 'ReqItemId'.$i;
			$ReqItemCounti = 'ReqItemCount'.$i;
			if( $quest_data[$ReqItemIdi] != 0 ) {
				for ($i = 1; $i <= $quest_data[$ReqItemCounti]; $i++) { 
					$this->ci->characters->removeItem( $quest_data[$ReqItemIdi], $uid );
				}
			}
		}

		// GIVE MONEY 
		$player_money = $this->ci->characters->getCharacterMoney( $cid );
		$player_money += $quest_data['RewMoney'];
		$this->ci->db->where('cid', $cid)->update('characters', array( 'money' => $player_money ) );		

		// GIVE ITEMS
		foreach($rewards as $item => $name) {
			if($quest_data[$name] != 0) {
				$items[] = $quest_data[$name];
			}
		}
		$this->ci->characters->giveItem( $items, $cid );
	}

	function questXP( $quest, $character ) {
		switch($quest['Type']) {
			case 1:
				$xp = ($quest['QuestLevel'] * 150) + 45;
				break;
			default:
				$xp = ($quest['QuestLevel'] * 75) + 45;
		}
		if($character->guildData->level >= 2) {
			$xp *= 1.05;
			if($character->guildData->level >= 6) {
				$xp *= 1.05;
			}
		}
		return round($xp);
	}
}
?>