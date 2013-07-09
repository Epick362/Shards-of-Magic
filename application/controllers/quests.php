<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Quests extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function take() {
		$level = $this->player_data->level;
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('quest', $uri_data)) {
			$function = $this->quest->takeQuest( $this->uid, (int)$uri_data['quest'], $level );
			if($function) {
				$this->core->displayMessage(2, "Quest Accepted.");
				redirect('world/');
			}else{
				redirect('error/show/type/cant_take_quest');
			}
		}else{
			redirect('error/');
		}
	}
	
	function abandon() {
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('quest', $uri_data)) {
			$query = $this->db->where('quest', (int)$uri_data['quest'])->where('user_id', $this->uid)->get('characters_queststatus');
			if($query->num_rows() > 0) {
				$this->db->where('quest', (int)$uri_data['quest'])->where('user_id', $this->uid)->delete('characters_queststatus');
				$this->core->displayMessage(3, "Quest Abandoned.");
				redirect('quests/');
			}
			redirect('error/show/type/cant_abandon_quest');
		}else{
			redirect('error/');
		}
	}

	function complete() {
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('quest', $uri_data)) {
				$check = $this->quest->isComplete( $uri_data['quest'], $this->uid );
				if($check) {
					$this->quest->RewardCharacter( $uri_data['quest'], $this->uid );
					$quest_data  = $this->quest->getQuestData( $uri_data['quest'] );
					// AWARD XP CHARACTER
					$this->characters->experienceChange( $this->player_data, $this->quest->QuestXP( $quest_data, $this->player_data ) );
					// AWARD XP GUILD
					$this->guilds->experienceChange( $this->player_data, $this->quest->QuestXP( $quest_data, $this->player_data ) / 4 );
					$data['status'] = 2;
					$this->db->where('user_id', $this->uid)->where('quest', $uri_data['quest'])->update('characters_queststatus', $data);
					$this->core->displayMessage(2, "Quest Completed.");
					redirect('quests/');
				}else{
					redirect('error/show/type/cant_complete_quest');
				}
		}else{
			redirect('error/');
		}		
	}
}
?>