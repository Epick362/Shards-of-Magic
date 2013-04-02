<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if(!$this->tank_auth->is_logged_in()) {
			redirect('/login');
		}else{
			$this->uid = $this->tank_auth->get_user_id();
			$this->player_data->level = $this->core->getCharacterLevel($this->tank_auth->get_user_id());
			$this->guild_data = $this->characters->getGuildData($this->uid);

			if(!$this->guilds->hasManageRights($this->uid, $this->guild_data->id)) {
				redirect('guild/');
			}
		}
	}

	function index() {
		if( $this->guild_data->id != 0 ) {
			$data->guild = $this->guilds->getGuildData($this->guild_data->id);
			$data->guildMembers = $this->guilds->getGuildMembers($this->guild_data->id);

			$this->template->set('subtitle',  'Manage Guild');
			$this->template->load('template', 'game/guild/manage/manage', $data, 'guild');		
		}else{
			redirect('guild/main/create');
		}
	}

	function members() {
		if( $this->guild_data->id != 0 ) {
			$data->guildMembers = $this->guilds->getGuildMembers($this->guild_data->id);
			$data->guild        = $this->guilds->getGuildData($this->guild_data->id);

			foreach($data->guildMembers as $member) {
				$list[] = $member->user_id;
			}
			$data->online_data = $this->characters->isOnline($list);

			$this->template->set('subtitle',  'View Members');
			$this->template->load('template', 'game/guild/manage/members', $data, 'guild');				
		}else{
			redirect('error/show/type/guild_not_found');			
		}	
	}

		function kick() {
			$data = $this->uri->uri_to_assoc(2);
			if(!array_key_exists('id', $data) || !$this->core->getCharacterName((int)$data['id']) || (int)$data['id'] == $this->uid) {
				redirect('error/');
			}else{
				$c_gdata = $this->characters->getGuildData((int)$data['id']);
				if(!$c_gdata->id) {
					redirect('error/');
				}else{
					if(!$this->guilds->hasManageRights($this->uid, $c_gdata->id)) {
						redirect('error/');
					}
				}
			}

			$this->db->where('user', (int)$data['id'])->delete('guild_member');

			redirect('guild/manage/members');
		}
}
?>