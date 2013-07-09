<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class World extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->template->set('subtitle',  'Map');
		
		if(!$this->characters->isTravelling( $this->uid ) ) {
			$modals = '';
			foreach( $this->world_data->zones as $cur_map_id => $cur_zone ) {
				$modal['world'][$cur_map_id] = '<table class="world-table default">';
				for($y_z = 0; $y_z < 3; $y_z++) {
					$modal['world'][$cur_map_id] .= '<tr>';
					for($x_z = 0; $x_z < 3; $x_z++) {
						if( $this->world_data->zones[$cur_map_id][$x_z][$y_z]->id == $this->world_data->zone->id )
							$modal['world'][$cur_map_id] .= '<td class="active">';
						else{
							$modal['world'][$cur_map_id] .= '<td class="land">';
						}
						$modal['world'][$cur_map_id] .= '<a href="'.base_url('world/travel/map/'.$cur_map_id.'/zone/'.$this->world_data->zones[$cur_map_id][$x_z][$y_z]->id.'').'">';
						$modal['world'][$cur_map_id] .= $this->world_data->zones[$cur_map_id][$x_z][$y_z]->name;
						$modal['world'][$cur_map_id] .= '</a></td>';
					}
					$modal['world'][$cur_map_id] .= '</tr>';
				}
				$modal['world'][$cur_map_id] .= '</table>';
				$modals .= $this->core->displayModal( $this->core->getMapName( $cur_map_id ) , $modal['world'][$cur_map_id], "", 'map'.$cur_map_id);
			}
			$this->modals = $modals;

			/// OLD ZONE 

			$this->content = '';
			$this->zone_info = '';
			$sell_inv = $this->characters->getCharacterInventory( $this->uid, $this->player_data->level, $this->player_data->class, 4 );
			$this->zone_info = '<img class="img-rounded" src="'.base_url('assets/images/zones/'.$this->world_data->zone->image.'.jpg').'" />';
			$this->zone_info .= '<div class="well" style="text-align:justify">'.$this->world_data->zone->description.'</div>';

			$faction_classes = array( 1 => 'friendly', 2 => 'neutral', 3 => 'hostile' );

			$this->player_data_quests = $this->characters->getCharacterQuestData( $this->uid );
			$creatures = $this->npc->getCreaturesInZone( $this->world_data );
			foreach($creatures as $creature_guid => $creature) {
				$has_quest = $this->npc->HasQuest( $this->uid, $creature->id, $this->core->getCharacterLevel($this->uid) );
				$this->content .= '<a class="npc-name '.$faction_classes[$creature->faction].'"><span class="numbers">'.$creature->level.'</span> '.$creature->name.'</a><img class="arrow" src="'.base_url('assets/images/arrow.png').'" /><br />';
				if( $creature->subname ) {
					$this->content .= '<a class="npc-subname '.$faction_classes[$creature->faction].'">&lt;'.$creature->subname.'&gt;</a><br />';					
				}
				$this->content .= $this->characters->showResourceBar(1, $creature->curhealth, $creature->health );
				$this->content .= $this->characters->showResourceBar(2, $creature->curmana, $creature->mana );
				$this->content .= '<ul class="unstyled">';

				if($has_quest['hasQuest'] == true && $creature->faction == 1) {
					unset( $has_quest['hasQuest'] );
					foreach( $has_quest as $quest_id => $quest_status ) {
						if( $quest_status != 2 && $quest_status != -2 ) {
							$quest = $this->quest->getQuestData( $quest_id );
							$this->content .= '<li class="quest-name">';
							if( $quest_status == -1 ) {
								$this->content .= '<img class="quest" src="'.base_url('assets/images/character/quest.png').'" />';
							}elseif( $quest_status == 0 ){
								$this->content .= '<img class="quest" src="'.base_url('assets/images/character/quest-incomplete.png').'" />';
							}elseif( $quest_status == 1 ){
								$this->content .= '<img class="quest" src="'.base_url('assets/images/character/quest-complete.png').'" />';
							}
							$this->content .= '<a data-toggle="modal" href="#quest'.$quest_id.'">';
							$this->content .= $quest['Title'];
							$this->content .= '</a>';
							$this->content .= '</li>';

							$modal['quest'][$quest_id]['header'] = '<h1>'.$quest['Title'].'</h1>';
							$modal['quest'][$quest_id]['body'] = '';
							$modal['quest'][$quest_id]['body'] .= '<a class="quest-header">Description</a>';
							if( $quest_status == 1 ) {
								$modal['quest'][$quest_id]['body'] .= '<p>'.$quest['OfferRewardText'].'</p>';
							}else{
								$modal['quest'][$quest_id]['body'] .= '<p>'.$quest['Details'].'</p>';
							}
							$modal['quest'][$quest_id]['body'] .= '<a class="quest-header">Required</a>';
							$modal['quest'][$quest_id]['body'] .= '<p>'.$quest['Objectives'].'</p>';
							$modal['quest'][$quest_id]['body'] .= '<div class="quest-status">';
							$modal['quest'][$quest_id]['body'] .= '<ul>';
							if( $quest_status >= 0 ) { 
								for ($i = 1; $i <= 4; $i++) {
									if(array_key_exists('ReqCreatureName'.$i, $this->player_data_quests[$quest_id])) {
										$modal['quest'][$quest_id]['body'] .= '<li>'.$this->player_data_quests[$quest_id]['ReqCreatureName'.$i].' slain <em class="pull-right">'.$this->player_data_quests[$quest_id]['ReqCreatureDone'.$i].'/'.$quest['ReqCreatureCount'.$i].'</em></li>';
									}
								}
								for ($i = 1; $i <= 4; $i++) {
									if(array_key_exists('ReqItemName'.$i, $this->player_data_quests[$quest_id])) {
										$modal['quest'][$quest_id]['body'] .= '<li>'.$this->player_data_quests[$quest_id]['ReqItemName'.$i].' <em class="pull-right">'.$this->player_data_quests[$quest_id]['ReqItemDone'.$i].'/'.$quest['ReqItemCount'.$i].'</em></li>';
									}
								}
							}else{
								for ($i = 1; $i <= 4; $i++) {
									if($quest['ReqCreatureId'.$i] != 0) {
										$modal['quest'][$quest_id]['body'] .= '<li>'.$quest['ReqCreatureName'.$i].' slain <em class="pull-right">0/'.$quest['ReqCreatureCount'.$i].'</em></li>';
									}
								}
								for ($i = 1; $i <= 4; $i++) {
									if(array_key_exists('ReqItemName'.$i, $quest)) {
										$modal['quest'][$quest_id]['body'] .= '<li>'.$quest['ReqItemName'.$i].' <em class="pull-right">0/'.$quest['ReqItemCount'.$i].'</em></li>';
									}
								}
							}
							$modal['quest'][$quest_id]['body'] .= '</ul>';
							$modal['quest'][$quest_id]['body'] .= '</div>';
							$modal['quest'][$quest_id]['body'] .= '<a class="quest-header">Rewards</a>';
							if( $quest['RewItemId1'] != 0 || $quest['RewItemId2'] != 0 || $quest['RewItemId3'] != 0 || $quest['RewItemId4'] != 0 ) {
								$modal['quest'][$quest_id]['body'] .= 'You will recieve:';
								$modal['quest'][$quest_id]['body'] .= '<table width="90%" style="margin: 10px;">';
								$position = 1;
								for ($i=1; $i <= 4; $i++) {
									$RewItemIdi = 'RewItemId'.$i;
									$RewItemDatai = 'RewItemData'.$i;
									if($quest[$RewItemIdi] != 0) {
										if($position == 1) {
											$modal['quest'][$quest_id]['body'] .= '<tr>';
										}
										$modal['quest'][$quest_id]['body'] .= '<td class="default">';
										$modal['quest'][$quest_id]['body'] .= '<table width="100%">';
										$modal['quest'][$quest_id]['body'] .= '<tr>';
										$modal['quest'][$quest_id]['body'] .= '<td width="20%">';
										$modal['quest'][$quest_id]['body'] .= '<div class="cursor-trade">'.$quest[$RewItemDatai]['image'].'</div>';
										$modal['quest'][$quest_id]['body'] .= '</td>';
										$modal['quest'][$quest_id]['body'] .= '<td style="padding: 10px;text-align: left;">';
										$modal['quest'][$quest_id]['body'] .= $quest[$RewItemDatai]['name'];
										$modal['quest'][$quest_id]['body'] .= '</td>';
										$modal['quest'][$quest_id]['body'] .= '</tr>';
										$modal['quest'][$quest_id]['body'] .= '</table>';
										$modal['quest'][$quest_id]['body'] .= '</td>';
										if($position == 2) {
											$modal['quest'][$quest_id]['body'] .= '</tr>';
										}else{
											$position++;
										}
									}
								}
								$modal['quest'][$quest_id]['body'] .= '</table>';
							}
							$modal['quest'][$quest_id]['body'] .= '<p>Experience: '.$this->quest->QuestXP( $quest, $this->player_data ).' points </p>';
							if( $quest['RewMoney'] ) {
								$modal['quest'][$quest_id]['body'] .= '<p>Money: '.$this->core->showMoney( $quest['RewMoney'] ).'</p>';
							}
							$modal['quest'][$quest_id]['footer'] = '';
							if( $quest_status == -1 ) {
								$modal['quest'][$quest_id]['footer'] .= '<a href="'.base_url('quests/take/quest/'.$quest['id']).'" class="btn btn-primary">Accept</a>';
							}elseif( $quest_status == 0 ){
								$modal['quest'][$quest_id]['footer'] .= '<a class="btn btn-success disabled">Complete</a>';
							}elseif( $quest_status == 1 ){
								$modal['quest'][$quest_id]['footer'] .= '<a href="'.base_url('quests/complete/quest/'.$quest['id']).'" class="btn btn-success">Complete</a>';
							}
							$this->content .= $this->core->displayModal($modal['quest'][$quest_id]['header'], $modal['quest'][$quest_id]['body'], $modal['quest'][$quest_id]['footer'], 'quest'.$quest_id);
						}
					}
				}elseif( $creature->faction == 3 ){
					$this->content .= '<li><a href="'.base_url('combat/attack/id/'.$creature_guid.'/pvp/0').'">Attack</a></li>';
				}

				$vendor_data = $this->npc->showVendor( $creature->id );
				if( $vendor_data['isVendor'] ) {
					$this->content .= '<li>';
					$this->content .= '<img class="quest" src="'.base_url('assets/images/character/trade.png').'" />';
					$this->content .= '<a href="#vendor'.$creature->id.'" data-toggle="modal">I want to browse your goods.</a>';
					$this->content .= '</li>';

					$modal['creature'][$creature->id]['header'] = '';
					$modal['creature'][$creature->id]['header'] .= '<span class="player-money" style="float:right;">'.$this->player_data->money.'</span>';
					$modal['creature'][$creature->id]['header'] .= '<h2>'.$creature->name.'</h2>';
					$modal['creature'][$creature->id]['header'] .= '<small>&lt;'.$creature->subname.'&gt;</small><br />';
					$modal['creature'][$creature->id]['header'] .= '<small><i>Note: To buy an item, click on the icon.</i></small>';
					$modal['creature'][$creature->id]['body'] = '';
					$modal['creature'][$creature->id]['body'] .= '<table width="100%" class="content" id="buy">';
					$position = 1;
					$items_data = $this->core->getItemData( $vendor_data['items'] );
					foreach($items_data as $item) {
						$canEquip = in_array( $item['subclass'], $this->player_data->classData['can_equip'] );
						$item['image'] = $this->item->addItemTooltip( $item, 3, $this->player_data->level, $canEquip );
						if($this->player_data->guildData->level >= 4) {
							$item['cost'] /= 1.05;
							if($this->player_data->guildData->level >= 9) {
								$item['cost'] /= 1.10;
							}
						}
						if($position == 1) {
							$modal['creature'][$creature->id]['body'] .= '<tr>';
						}
						$modal['creature'][$creature->id]['body'] .= '<td>';
						$modal['creature'][$creature->id]['body'] .= '<table width="100%">';
						$modal['creature'][$creature->id]['body'] .= '<tr>';
						$modal['creature'][$creature->id]['body'] .= '<td width="20%">';
						$modal['creature'][$creature->id]['body'] .= '<div class="cursor-trade">'.$item['image'].'</div>';
						$modal['creature'][$creature->id]['body'] .= '</td>';
						$modal['creature'][$creature->id]['body'] .= '<td style="padding: 10px;">';
						$modal['creature'][$creature->id]['body'] .= $item['name'];
						$modal['creature'][$creature->id]['body'] .= '</td>';
						$modal['creature'][$creature->id]['body'] .= '</tr>';
						$modal['creature'][$creature->id]['body'] .= '<tr>';
						$modal['creature'][$creature->id]['body'] .= '<td colspan="2">';
						$modal['creature'][$creature->id]['body'] .= '<span class="pull-right">'.$this->core->showMoney($item['cost']).'</span>';
						$modal['creature'][$creature->id]['body'] .= '</td>';
						$modal['creature'][$creature->id]['body'] .= '</tr>';
						$modal['creature'][$creature->id]['body'] .= '</table>';
						if($position == 2) {
							$modal['creature'][$creature->id]['body'] .= '</tr>';
							$position = 1;
						}else{
							$position++;
						}
					}
					$modal['creature'][$creature->id]['body'] .= '</table>';
					$modal['creature'][$creature->id]['body'] .= '<div class="content" id="sell" style="display:none;">';
					$modal['creature'][$creature->id]['body'] .= $sell_inv;
					$modal['creature'][$creature->id]['body'] .= '</div>';
					$modal['creature'][$creature->id]['body'] .= '<hr />';
					$modal['creature'][$creature->id]['footer'] = '';
					$modal['creature'][$creature->id]['footer'] .= '<a class="vendor-type btn" id="buy"><span>Buy</span></a> ';
					$modal['creature'][$creature->id]['footer'] .= '<a class="vendor-type btn" id="sell"><span>Sell</span></a>';
					$modal['creature'][$creature->id]['footer'] .= '<div id="trade-log"></div>';

					$this->content .= $this->core->displayModal( $modal['creature'][$creature->id]['header'], $modal['creature'][$creature->id]['body'], $modal['creature'][$creature->id]['footer'], 'vendor'.$creature->id );	
				}

				$this->content .= '</ul><hr />';

			}

			// OLD ZONE END
			// OLD QUESTS

			$this->quest_data = $this->characters->getCharacterQuestData($this->uid);

			// OLD QUESTS END

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