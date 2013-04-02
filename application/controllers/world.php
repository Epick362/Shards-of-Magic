<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class World extends CI_Controller
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
		$this->template->set('subtitle',  'World');

		$player = $this->characters->getPlayerData( $uid );
		$data->world_data = $this->core->getWorldData( $uid );
		
		if(!$this->characters->isTravelling( $uid ) ) {
			$modals = "";
			foreach( $data->world_data->zones as $cur_map_id => $cur_zone ) {
				$modal = "<table class=\"world-table default\">";
				for($y_z = 0; $y_z < 3; $y_z++) {
					$modal .= "<tr>";
					for($x_z = 0; $x_z < 3; $x_z++) {
						if( $data->world_data->zones[$cur_map_id][$x_z][$y_z]->id == $data->world_data->zone->id )
							$modal .= "<td class='active'>";
						else{
							$modal .= "<td class='land'>";
						}
						$modal .= "<a href=\"".base_url('world/travel/map/'.$cur_map_id.'/zone/'.$data->world_data->zones[$cur_map_id][$x_z][$y_z]->id.'')."\">";
						$modal .= $data->world_data->zones[$cur_map_id][$x_z][$y_z]->name;
						$modal .= "</a></td>";
					}
					$modal .= "</tr>";
				}
				$modal .= "</table>";
				$modals .= $this->core->displayModal( $this->core->getMapName( $cur_map_id ) , $modal, "", "map".$cur_map_id);
			}
			$data->modals = $modals;

			$this->template->load('template', 'game/world/world', $data, 'world');
		}else{
			$travel_data = $this->characters->getTravelData( $uid );

			$data->end_time    = $travel_data->end_time;
			$data->destination = $this->core->getZoneData( $travel_data );

			$this->template->load('template', 'game/world/travelling', $data, 'world');
		}
	}

	function travel() 
	{
		$travel_data = $this->uri->uri_to_assoc();

		$travel_data['map'] = intval($travel_data['map']);
		$travel_data['zone'] = intval($travel_data['zone']);

		$uid = $this->tank_auth->get_user_id();

		if ($this->fight->isInCombat($uid)) {
			redirect('combat/');
		}

		if( !array_key_exists('map', $travel_data)  || 
			!array_key_exists('zone', $travel_data) ||
			$this->characters->isTravelling($uid) ) {
			redirect('world/');
		}

		$world_data = $this->core->getWorldData( $uid );

		$this->characters->addTraveller( $uid, $world_data, $travel_data );
		redirect('world/');
	}
/*
	function index()
	{
		$uid = $this->tank_auth->get_user_id();

		if ($this->fight->isInCombat($uid)) {
			redirect('combat/');
		}

		$data->world_data = $this->core->getWorldData( $uid );
		$data->directions = $this->core->getDirections( $data->world_data );

		$this->template->set('subtitle',  'World');
		if(!$this->characters->isTravelling( $uid ) ) {
			$data->content = "";

			$creatures = $this->npc->getCreaturesInZone( $data->world_data->map->id, $data->world_data->zone->id );

			if( $data->world_data->zone->is_city == 1 ) {
				$data->content .= "This zone is a city.<br />";
				foreach($creatures as $creature_id => $creature) {
					$data->content .= $creature->name.' :: '.$creature->health.'HP :: '.$creature->mana.' MN<br />';
				}
			}else{
				$data->content .= "This zone is NOT a city.";
				foreach($creatures as $creature_id => $creature) {
					$data->content .= "<a href=\"".base_url('combat/attack/id/'.$creature->id.'/pvp/0')."\">";
					$data->content .= $creature->name.' :: '.$creature->health.'HP :: '.$creature->mana.' MN<br />';
					$data->content .= "</a>";
				}
			}
			$this->template->load('template', 'game/world/world', $data, 'world');
		}else{
			$travel_data = $this->characters->getTravelData($this->tank_auth->get_user_id());

			$data->end_time    = $travel_data->end_time;
			$data->destination = $this->core->getZoneData( $travel_data->to );

			$this->template->load('template', 'game/world/travelling', $data, 'world');
		}
	}

	function travel() 
	{
		$travel_data = $this->uri->uri_to_assoc();

		$travel_data['map'] = intval($travel_data['map']);
		$travel_data['zone'] = intval($travel_data['zone']);

		$uid = $this->tank_auth->get_user_id();

		if ($this->fight->isInCombat($uid)) {
			redirect('combat/');
		}

		if( !array_key_exists('map', $travel_data)  || 
			!array_key_exists('zone', $travel_data) ||
			!$this->core->destinationExists($travel_data) ||
			$this->characters->isTravelling($uid) ) {
			redirect('world/');
		}

		$world_data = $this->core->getWorldData( $uid );

		$this->characters->addTraveller( $uid, $world_data, $travel_data );
		redirect('world/');
	}
*/
}
?>