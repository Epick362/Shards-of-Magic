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

	function equip() {
		$data = $this->uri->uri_to_assoc(1);
		if(!array_key_exists('item', $data) || !$this->core->getItemData((int)$data['item'])) {
			redirect('error/show/type/item_not_found');
		}

		$this->characters->EquipItem( $this->uid, $data['item'] );

		redirect('character/');
	}
	
	function unequip() {
		$data = $this->uri->uri_to_assoc(1);
		if(!array_key_exists('item', $data) || !$this->core->getItemData((int)$data['item'])) {
			redirect('error/show/type/item_not_found');
		}

		if(!$this->characters->unEquipItem( $this->uid, $data['item'] )) {
			redirect('error/show/type/inventory_full');
		}

		redirect('character/');
	}

	function buy() {
		$data = $this->uri->uri_to_assoc(1);
		if(!array_key_exists('item', $data) || !$this->core->getItemData((int)$data['item'])) {
			redirect('error/show/type/item_not_found');
		}

		$buy_item = $this->characters->buyItem( $this->uid, $data['item'] );
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
		$player = $this->characters->getPlayerData( $this->uid );
		$sell_item = $this->characters->sellItem( $this->uid, $data['item'] );
		if( $sell_item ) {
			$send_data = array();
			$send_data['successful'] = 1;
			$send_data['name'] = $sell_item['name'];
			$send_data['quality'] = $sell_item['quality'];
			$send_data['count'] = 1; // TODO
			$send_data['player_money'] = $this->core->showMoney($sell_item['player_money']);
			$send_data['inv'] = $this->characters->getCharacterInventory($this->uid, $player->level, $player->class, 4);
			echo json_encode($send_data);
		}else{
			$send_data = array();
			$send_data['successful'] = 0;

			echo json_encode($send_data);
		}
	}
}
?>