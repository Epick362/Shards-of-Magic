<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Character extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$check = $this->active->DoResourcesCheck( $this->uid );
		if ($this->fight->isInCombat($this->uid)) {
			redirect('combat/');
		}

		$this->template->set('subtitle',  'Character');
		$this->template->ingame('game/character/character', $this, 'character');
	}

	function view() {
		$data = $this->uri->uri_to_assoc(1);
		if(!array_key_exists('id', $data) || !$this->characters->getPlayerData((int)$data['id'])) {
			redirect('error/show/type/profile_not_found');
		}
		$uid = (int)$data['id'];
		unset($data);
		$check = $this->active->DoResourcesCheck( $uid );

		$data 				= $this->characters->getPlayerData( $uid );
		$data->xp_needed 	= $this->characters->experienceNeeded( $data );
		$data->xp_handler 	= $this->characters->experienceHandler( $data );
		$stats_data 		= $this->characters->setClassStats( $uid, $data );

		$data->sta = $stats_data['sta'];
		$data->dex = $stats_data['dex'];
		$data->str = $stats_data['str'];
		$data->int = $stats_data['int'];
		$data->luc = $stats_data['luc'];

		$update_data = $this->characters->setCharacterHealth( $data );
		$update_data = $this->characters->setCharacterMana( $data );
		
		$data->stats 		= $this->characters->getCharacterStats( $data, $data->equip );
		$data->inv   		= $this->characters->getCharacterInventory( $uid, $data->level, $data->class );

		$data->class_data  	= $this->core->getClassData( $data->class );
		$data->gender_name 	= $this->core->getGenderName( $data->gender );
		$data->money       	= $this->core->showMoney( $data->money );

		$this->template->set('subtitle',  'Character');
		$this->template->load('template', 'game/character/character_view', $data, 'character');
	}

	function equip() {
		$data = $this->uri->uri_to_assoc(1);
		if(!array_key_exists('item', $data) || !$this->core->getItemData((int)$data['item'])) {
			redirect('error/show/type/item_not_found');
		}
		$uid = $this->tank_auth->get_user_id();

		$this->characters->EquipItem( $uid, $data['item'] );

		redirect('character/');
	}
	
	function unequip() {
		$data = $this->uri->uri_to_assoc(1);
		if(!array_key_exists('item', $data) || !$this->core->getItemData((int)$data['item'])) {
			redirect('error/show/type/item_not_found');
		}
		$uid = $this->tank_auth->get_user_id();

		if(!$this->characters->unEquipItem( $uid, $data['item'] )) {
			redirect('error/show/type/inventory_full');
		}

		redirect('character/');
	}

	function buy() {
		$data = $this->uri->uri_to_assoc(1);
		if(!array_key_exists('item', $data) || !$this->core->getItemData((int)$data['item'])) {
			redirect('error/show/type/item_not_found');
		}
		$uid = $this->tank_auth->get_user_id();

		$buy_item = $this->characters->buyItem( $uid, $data['item'] );
		if( $buy_item ) {
			$send_data = array();
			$send_data['successful'] = 1;
			$send_data['name'] = $buy_item['name'];
			$send_data['quality'] = $buy_item['quality'];
			$send_data['count'] = 1; // TODO
			$send_data['player_money'] = $this->core->showMoney($buy_item['player_money']);

			echo json_encode($send_data);
		}else{
			$send_data = array();
			$send_data['successful'] = 0;

			echo json_encode($send_data);
		}
	}

	function sell() {
		$data = $this->uri->uri_to_assoc(1);
		if(!array_key_exists('item', $data) || !$this->core->getItemData((int)$data['item'])) {
			redirect('error/show/type/item_not_found');
		}
		$uid = $this->tank_auth->get_user_id();
		$player = $this->characters->getPlayerData( $uid );
		$sell_item = $this->characters->sellItem( $uid, $data['item'] );
		if( $sell_item ) {
			$send_data = array();
			$send_data['successful'] = 1;
			$send_data['name'] = $sell_item['name'];
			$send_data['quality'] = $sell_item['quality'];
			$send_data['count'] = 1; // TODO
			$send_data['player_money'] = $this->core->showMoney($sell_item['player_money']);
			$send_data['inv'] = $this->characters->getCharacterInventory($uid, $player->level, $player->class, 4);
			echo json_encode($send_data);
		}else{
			$send_data = array();
			$send_data['successful'] = 0;

			echo json_encode($send_data);
		}
	}
}
?>