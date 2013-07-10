<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->guild_data = $this->player_data->guildData;
		$this->load->library('form_validation');

		$this->gPerks = array(
			2  => array('name' => 'XP_REWARD', 'image' => 'misc_experience', 'description' => 'Experience gained from killing monsters and completing quests increased by 5%.'),
			3  => array('name' => 'GB_REWARD', 'image' => 'misc_experience', 'description' => 'Each time you loot money from an enemy, an extra 5% money is generated and deposited directly into your guild bank.'),
			4  => array('name' => 'TRAVEL_REWARD', 'image' => 'misc_experience', 'description' => 'FASTER_TRAVEL'),
			5  => array('name' => 'COST_REWARD', 'image' => 'misc_experience', 'description' => 'Reduces the price of items from all vendors by 5%.'),
			6  => array('name' => 'BACK_REWARD', 'image' => 'misc_experience', 'description' => 'REWARD_BACK'),
			7  => array('name' => 'XP_REWARD2', 'image' => 'misc_experience', 'description' => 'Experience gained from killing monsters and completing quests increased by 10%.'),
			8  => array('name' => 'GB_REWARD2', 'image' => 'misc_experience', 'description' => 'Each time you loot money from an enemy, an extra 10% money is generated and deposited directly into your guild bank.'),
			9  => array('name' => 'TRAVEL_REWARD2', 'image' => 'misc_experience', 'description' => 'FASTER_TRAVEL2'),
			10 => array('name' => 'COST_REWARD2', 'image' => 'misc_experience', 'description' => 'Reduces the price of items from all vendors by 10%.'),
		);
	}

	function index() {
		if($this->guild_data->id) {
			$this->guild = $this->guilds->getGuildData($this->guild_data->id);
			$this->guildMembers = $this->guilds->getGuildMembers($this->guild_data->id);
			$this->guildLog = $this->guilds->getGuildLog($this->guild_data->id);

			foreach($this->guildMembers as $member) {
				if($member->user_id == $this->guild->leader) {
					$this->leader_data = $this->core->getClassData($member->class);
					$this->leader_data['name'] = $member->name;
					$this->leader_data['level'] = $member->level;						
				}

				if($member->cid == $this->cid) {
					$this->has_access_to_w = ($member->rank > 2 ? true : false);
				}
			}

			$banklog['header'] = "<h1>Guild Bank Log</h1>";
			$banklog['body'] = "";
			foreach($this->guildLog as $row) {
				$banklog['body'] .= "<span class=\"article-footer\">".date('H:i', $row->time)."</span> ".$row->message."<br />";
			}
			$this->guild_log = $this->core->displayModal( $banklog['header'], $banklog['body'], "", "log");

			$this->next_reward = $this->gPerks[$this->guild->level+1];

			$this->template->set('subtitle',  'Guild');
			$this->template->ingame('game/guild/guild', $this, 'guild');		
		}else{
			if($this->player_data->level >= 10 ) {
				redirect('guild/main/create');
			}else{
				redirect('error/show/type/cant_create_guild');
			}
		}
	}

		function withdraw() {
			$this->form_validation->set_rules('withdraw', 'Withdraw', 'trim|required|xss_clean|numeric|max_length[12]');
			if($this->form_validation->run()) {
				$w = $this->guilds->withdrawFromGuild( $this->cid, $this->guild_data->id, $this->form_validation->set_value('withdraw') * 10000 );
				if($w) {
					redirect('guild/');
				}else{
					redirect('error/show/type/not_enough_money');
				}
			}else{
				redirect('error/');
			}
		}

		function deposit() {
			$this->form_validation->set_rules('deposit', 'Deposit', 'trim|required|xss_clean|numeric|max_length[12]');
			if($this->form_validation->run()) {
				$d = $this->guilds->depositToGuild( $this->cid, $this->guild_data->id, $this->form_validation->set_value('deposit') * 10000 );
				if($d) {
					redirect('guild/');
				}else{
					redirect('error/show/type/not_enough_money');
				}
			}else{
				redirect('error/');
			}
		}

	function members() {
		if( $this->guild_data->id != 0 ) {
			$this->guildMembers = $this->guilds->getGuildMembers($this->guild_data->id);
			$this->guild        = $this->guild_data;

			foreach($this->guildMembers as $member) {
				$list[] = $member->cid;
			}
			$this->online_data = $this->characters->isOnline($list);

			$this->template->set('subtitle',  'View Members');
			$this->template->ingame('game/guild/members', $this, 'guild');				
		}else{
			redirect('error/show/type/guild_not_found');			
		}
	}

	function view() {
		$data = $this->uri->uri_to_assoc(2);
		if(!array_key_exists('id', $data) || !$this->guilds->getGuildData((int)$data['id'])) {
			redirect('error/show/type/guild_not_found');
		}
		$guild = $data['id'];
		unset($data);

		$this->guild = $this->guilds->getGuildData($guild);
		$this->guildMembers = $this->guilds->getGuildMembers($guild);
		
		foreach($this->guildMembers as $member) {
			if($member->cid == $this->guild->leader) {
				$this->leader_data = $this->core->getClassData($member->class);
				$this->leader_data['username'] = $member->username;
				$this->leader_data['level'] = $member->level;		
			}
		}

		$this->template->set('subtitle',  'Guild');
		$this->template->ingame('game/guild/view', $this, 'guild');
	}

	function create() {
		if( $this->player_data->level  < 10 ) {
			redirect('error/show/type/cant_create_guild');
		}elseif( $this->guild_data->id != 0 ){
			redirect('guild/');
		}else{
			$this->load->helper(array('form', 'url', 'auth'));

			$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean|max_length[16]|callback_guild_name_check');
			$this->form_validation->set_rules('info', 'Information', 'trim|required|xss_clean|max_length[512]');

			if ($this->form_validation->run()) {
				$create = $this->guilds->createGuild( $this->form_validation->set_value('name'), $this->cid, $this->form_validation->set_value('info'));
				if ($create) {
					redirect('guild/');
				}else{
					redirect('error/');
				}
			}

			$this->template->set('subtitle',  'Guild');
			$this->template->ingame('game/guild/create-guild', $this, 'guild');			
		}
	}

	public function guild_name_check( $str )
	{
		$query = $this->db->where('name', $str)->get('guild');

		if($query->num_rows() > 0) {
			$this->form_validation->set_message('guild_name_check', 'This guild name is already taken.');
			return FALSE;
		}else{
			return TRUE;
		}
	}
}
?>