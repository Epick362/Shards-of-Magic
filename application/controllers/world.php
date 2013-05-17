<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class World extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		if ($this->fight->isInCombat($this->uid)) {
			redirect('combat/');
		}
		$this->template->set('subtitle',  'World');
		
		if(!$this->characters->isTravelling( $this->uid ) ) {
			$modals = "";
			foreach( $this->world_data->zones as $cur_map_id => $cur_zone ) {
				$modal = "<table class=\"world-table default\">";
				for($y_z = 0; $y_z < 3; $y_z++) {
					$modal .= "<tr>";
					for($x_z = 0; $x_z < 3; $x_z++) {
						if( $this->world_data->zones[$cur_map_id][$x_z][$y_z]->id == $this->world_data->zone->id )
							$modal .= "<td class='active'>";
						else{
							$modal .= "<td class='land'>";
						}
						$modal .= "<a href=\"".base_url('world/travel/map/'.$cur_map_id.'/zone/'.$this->world_data->zones[$cur_map_id][$x_z][$y_z]->id.'')."\">";
						$modal .= $this->world_data->zones[$cur_map_id][$x_z][$y_z]->name;
						$modal .= "</a></td>";
					}
					$modal .= "</tr>";
				}
				$modal .= "</table>";
				$modals .= $this->core->displayModal( $this->core->getMapName( $cur_map_id ) , $modal, "", "map".$cur_map_id);
			}
			$this->modals = $modals;

			$this->template->ingame('game/world/world', $this, 'world');
		}else{
			$travel_data = $this->characters->getTravelData( $this->uid );

			$this->end_time    = $travel_data->end_time;
			$this->destination = $this->core->getZoneData( $travel_data );

			$this->template->ingame('game/world/travelling', $this, 'world');
		}
	}

	function travel() 
	{
		$travel_data = $this->uri->uri_to_assoc();

		$travel_data['map'] = intval($travel_data['map']);
		$travel_data['zone'] = intval($travel_data['zone']);

		if ($this->fight->isInCombat($this->uid)) {
			redirect('combat/');
		}

		if( !array_key_exists('map', $travel_data)  || 
			!array_key_exists('zone', $travel_data) ||
			$this->characters->isTravelling($this->uid) ) {
			redirect('world/');
		}

		$world_data = $this->core->getWorldData( $this->uid );

		$this->characters->addTraveller( $this->uid, $world_data, $travel_data );
		redirect('world/');
	}
}
?>