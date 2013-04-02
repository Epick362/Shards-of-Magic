<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Quests extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if(!$this->tank_auth->is_logged_in()) {
			redirect('/login');
		}
	}

	function index()
	{
		$uid = $this->tank_auth->get_user_id();
		if ($this->fight->isInCombat($uid)) {
			redirect('combat/');
		}

		$data->quest_data = $this->characters->getCharacterQuestData($uid);

		$this->template->set('subtitle',  'Quests');
		$this->template->load('template', 'game/quests/quests', $data, 'quests');
	}

	function take() {
		$uid = $this->tank_auth->get_user_id();
		$level = $this->core->getCharacterLevel( $uid );
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('quest', $uri_data)) {
			$function = $this->quest->takeQuest( $uid, (int)$uri_data['quest'], $level );
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
		$uid = $this->tank_auth->get_user_id();
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('quest', $uri_data)) {
			$query = $this->db->where('quest', (int)$uri_data['quest'])->get('characters_queststatus');
			if($query->num_rows() > 0) {
				$this->db->where('quest', (int)$uri_data['quest'])->delete('characters_queststatus');
				$this->core->displayMessage(3, "Quest Abandoned.");
				redirect('quests/');
			}
			redirect('error/show/type/cant_abandon_quest');
		}else{
			redirect('error/');
		}
	}

	function complete() {
		$uid = $this->tank_auth->get_user_id();
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('quest', $uri_data)) {
				$check = $this->quest->isComplete( $uri_data['quest'], $uid );
				if($check) {
					$this->quest->RewardCharacter( $uri_data['quest'], $uid );
					$quest_data  = $this->quest->getQuestData( $uri_data['quest'] );
					$player_data = $this->characters->getPlayerData( $uid );
					// AWARD XP CHARACTER
					$this->characters->experienceChange( $player_data, $this->quest->QuestXP( $quest_data, $player_data ) );
					// AWARD XP GUILD
					$this->guilds->experienceChange( $player_data, $this->quest->QuestXP( $quest_data, $player_data ) / 4 );
					$data['status'] = 2;
					$this->db->where('user_id', $uid)->where('quest', $uri_data['quest'])->update('characters_queststatus', $data);
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