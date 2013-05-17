<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->guild = $this->characters->getGuildData($this->uid);

		if(!$this->guilds->hasManageRights($this->uid, $this->guild->id)) {
			redirect('guild/');
		}
	}

	function index() {
		if( $this->guild->id != 0 ) {
			$this->guildMembers = $this->guilds->getGuildMembers($this->guild->id);

			$this->template->set('subtitle',  'Manage Guild');
			$this->template->ingame('game/guild/manage/manage', $this, 'guild');		
		}else{
			redirect('guild/main/create');
		}
	}

	function members() {
		if( $this->guild->id != 0 ) {
			$this->guildMembers = $this->guilds->getGuildMembers($this->guild->id);

			foreach($this->guildMembers as $member) {
				$list[] = $member->user_id;
			}
			$this->online_data = $this->characters->isOnline($list);

			$this->template->set('subtitle',  'View Members');
			$this->template->ingame('game/guild/manage/members', $this, 'guild');				
		}else{
			redirect('error/show/type/guild_not_found');			
		}	
	}

		function kick() {
			$data = $this->uri->uri_to_assoc(2);
			if(!array_key_exists('id', $data) || !$this->core->getCharacterName((int)$data['id']) || (int)$data['id'] == $this->uid) {
				redirect('error/');
			}else{
				if(!$this->guilds->hasManageRights($this->uid, $this->guild->id)) {
					redirect('error/');
				}
			}

			$this->db->where('user', (int)$data['id'])->delete('guild_member');

			redirect('guild/manage/members');
		}
}
?>