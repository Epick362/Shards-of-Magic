<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller
{
	function index() {
		redirect('error/show/');
	}

	function show() {
		$uri_data = $this->uri->uri_to_assoc();

		if(array_key_exists('type', $uri_data)) {
			switch ($uri_data['type']) {
				case 'not_enough_money':
					$data->text = 'You don\'t have enough money to do that.';
					break;
				case 'cant_create_guild':
					$data->text = 'You can only create guild if you are level 10 or higher.';
					break;
				case 'guild_not_found':
					$data->text = 'Guild could not be found.';
					break;
				case 'profile_not_found':
					$data->text = 'Profile could not be found.';
					break;
				case 'fight_not_found':
					$data->text = 'This fight could not be found.';
					break;
				case 'item_not_found':
					$data->text = 'This item does not exists.';
					break;
				case 'inventory_full':
					$data->text = 'Your Inventory is full.';
					break;
				case 'cant_delete_message':
					$data->text = 'You can\'t delete this message.';
					break;	
				case 'you_or_opponent_in_combat':
					$data->text = 'You or your opponent is in combat already.';
					break;
				case 'cant_abandon_quest':
					$data->text = 'You can\'t abandon this quest.';
					break;	
				case 'cant_take_quest':
					$data->text = 'You can\'t accept this quest.';
					break;	
				case 'cant_complete_quest':
					$data->text = 'You can\'t complete this quest.';
					break;	
				default:
					$data->text = 'Something went wrong.';
					break;				
			}
		}else{
			$data->text = 'Something went wrong.';
		}

		$this->template->set('subtitle',  'Error');
		$this->template->load('template', 'game/error', $data);		
	}
}
?>