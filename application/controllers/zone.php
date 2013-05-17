<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Zone extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->template->set('subtitle',  'Zone');

		if ($this->fight->isInCombat($this->uid)) {
			redirect('combat/');
		}

		if(!$this->characters->isTravelling( $this->uid ) ) {
			$this->content = "";
			$this->zone_info = "";
			$sell_inv = $this->characters->getCharacterInventory( $this->uid, $this->player_data->level, $this->player_data->class, 4 );
			$this->zone_info = '<table class="default" width="100%">';
			$this->zone_info .= '<tr>';
			$this->zone_info .= '<th colspan="2">'.$this->world_data->zone->name.'</th>';
			$this->zone_info .= '</tr>';
			$this->zone_info .= '<tr>';
			$this->zone_info .= '<td colspan="2"><img class="rounded-3" src="'.base_url('assets/images/zones/'.$this->world_data->zone->image.'.jpg').'" width="270" /></td>';
			$this->zone_info .= '</tr>';
			$this->zone_info .= '<tr>';
			$this->zone_info .= '<td colspan="2">'.$this->world_data->zone->description.'</td>';
			$this->zone_info .= '</tr>';
			$this->zone_info .= '</table>';

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
				$this->content .= '<ul>';

				if($has_quest['hasQuest'] == true && $creature->faction == 1) {
					unset( $has_quest['hasQuest'] );
					foreach( $has_quest as $quest_id => $quest_status ) {
						if( $quest_status != 2 && $quest_status != -2 ) {
							$quest = $this->quest->getQuestData( $quest_id );
							$this->content .= '<li class="quest-name">';
							if( $quest_status == -1 ) {
								$this->content .= "<img class=\"quest\" src=\"".base_url('assets/images/character/quest.png')."\" />";
							}elseif( $quest_status == 0 ){
								$this->content .= "<img class=\"quest\" src=\"".base_url('assets/images/character/quest-incomplete.png')."\" />";
							}elseif( $quest_status == 1 ){
								$this->content .= "<img class=\"quest\" src=\"".base_url('assets/images/character/quest-complete.png')."\" />";
							}
							$this->content .= "<a data-toggle=\"modal\" href=\"#quest".$quest_id."\">";
							$this->content .= $quest['Title'];
							$this->content .= "</a>";
							$this->content .= '</li>';

							$modal['quest'][$quest_id]['header'] = "<h1>".$quest['Title']."</h1>";
							$modal['quest'][$quest_id]['body'] = "";
							$modal['quest'][$quest_id]['body'] .= "<a class=\"quest-header\">Description</a>";
							if( $quest_status == 1 ) {
								$modal['quest'][$quest_id]['body'] .= "<p>".$quest['OfferRewardText']."</p>";
							}else{
								$modal['quest'][$quest_id]['body'] .= "<p>".$quest['Details']."</p>";
							}
							$modal['quest'][$quest_id]['body'] .= "<a class=\"quest-header\">Required</a>";
							$modal['quest'][$quest_id]['body'] .= "<p>".$quest['Objectives']."</p>";
							$modal['quest'][$quest_id]['body'] .= "<div class=\"quest-status\">";
							if( $quest_status >= 0 ) { 
								for ($i = 1; $i <= 4; $i++) {
									if(array_key_exists('ReqCreatureName'.$i, $this->player_data_quests[$quest_id])) {
										$modal['quest'][$quest_id]['body'] .= '<ul><li><strong>'.$this->player_data_quests[$quest_id]['ReqCreatureName'.$i].' slain</strong> <em>'.$this->player_data_quests[$quest_id]['ReqCreatureDone'.$i].'/'.$quest['ReqCreatureCount'.$i].'</em></li></ul>';
									}
								}
								for ($i = 1; $i <= 4; $i++) {
									if(array_key_exists('ReqItemName'.$i, $this->player_data_quests[$quest_id])) {
										$modal['quest'][$quest_id]['body'] .= '<ul><li><strong>'.$this->player_data_quests[$quest_id]['ReqItemName'.$i].'</strong> <em>'.$this->player_data_quests[$quest_id]['ReqItemDone'.$i].'/'.$quest['ReqItemCount'.$i].'</em></li></ul>';
									}
								}
							}else{
								for ($i = 1; $i <= 4; $i++) {
									if($quest['ReqCreatureId'.$i] != 0) {
										$modal['quest'][$quest_id]['body'] .= '<ul><li><strong>'.$quest['ReqCreatureName'.$i].' slain</strong> <em>0/'.$quest['ReqCreatureCount'.$i].'</em></li></ul>';
									}
								}
								for ($i = 1; $i <= 4; $i++) {
									if(array_key_exists('ReqItemName'.$i, $quest)) {
										$modal['quest'][$quest_id]['body'] .= '<ul><li><strong>'.$quest['ReqItemName'.$i].'</strong> <em>0/'.$quest['ReqItemCount'.$i].'</em></li></ul>';
									}
								}
							}
							$modal['quest'][$quest_id]['body'] .= "</div>";
							$modal['quest'][$quest_id]['body'] .= "<a class=\"quest-header\">Rewards</a>";
							if( $quest['RewItemId1'] != 0 || $quest['RewItemId2'] != 0 || $quest['RewItemId3'] != 0 || $quest['RewItemId4'] != 0 ) {
								$modal['quest'][$quest_id]['body'] .= "You will recieve:";
								$modal['quest'][$quest_id]['body'] .= "<table width=\"90%\" style=\"margin: 10px;\">";
								$position = 1;
								for ($i=1; $i <= 4; $i++) {
									$RewItemIdi = "RewItemId".$i;
									$RewItemDatai = "RewItemData".$i;
									if($quest[$RewItemIdi] != 0) {
										if($position == 1) {
											$modal['quest'][$quest_id]['body'] .= "<tr>";
										}
										$modal['quest'][$quest_id]['body'] .= "<td class=\"default\">";
										$modal['quest'][$quest_id]['body'] .= "<table width=\"100%\">";
										$modal['quest'][$quest_id]['body'] .= "<tr>";
										$modal['quest'][$quest_id]['body'] .= "<td width=\"20%\">";
										$modal['quest'][$quest_id]['body'] .= "<div class=\"cursor-trade\">".$quest[$RewItemDatai]['image']."</div>";
										$modal['quest'][$quest_id]['body'] .= "</td>";
										$modal['quest'][$quest_id]['body'] .= "<td style='padding: 10px;text-align: left;'>";
										$modal['quest'][$quest_id]['body'] .= $quest[$RewItemDatai]['name'];
										$modal['quest'][$quest_id]['body'] .= "</td>";
										$modal['quest'][$quest_id]['body'] .= "</tr>";
										$modal['quest'][$quest_id]['body'] .= "</table>";
										$modal['quest'][$quest_id]['body'] .= "</td>";
										if($position == 2) {
											$modal['quest'][$quest_id]['body'] .= "</tr>";
										}else{
											$position++;
										}
									}
								}
								$modal['quest'][$quest_id]['body'] .= "</table>";
							}
							$modal['quest'][$quest_id]['body'] .= "<p>Experience: ".$this->quest->QuestXP( $quest, $this->player_data ). " points </p>";
							if( $quest['RewMoney'] ) {
								$modal['quest'][$quest_id]['body'] .= "<p>Money: ".$this->core->showMoney( $quest['RewMoney'] )."</p>";
							}
							$modal['quest'][$quest_id]['footer'] = "";
							if( $quest_status == -1 ) {
								$modal['quest'][$quest_id]['footer'] .= "<a href=\"".base_url('quests/take/quest/'.$quest['id'])."\" class=\"btn btn-primary\">Accept</a>";
							}elseif( $quest_status == 0 ){
								$modal['quest'][$quest_id]['footer'] .= "<a class=\"btn btn-success disabled\">Complete</a>";
							}elseif( $quest_status == 1 ){
								$modal['quest'][$quest_id]['footer'] .= "<a href=\"".base_url('quests/complete/quest/'.$quest['id'])."\" class=\"btn btn-success\">Complete</a>";
							}
							$this->content .= $this->core->displayModal($modal['quest'][$quest_id]['header'], $modal['quest'][$quest_id]['body'], $modal['quest'][$quest_id]['footer'], "quest".$quest_id);
						}
					}
				}elseif( $creature->faction == 3 ){
					$this->content .= "<li><a href=\"".base_url('combat/attack/id/'.$creature_guid.'/pvp/0')."\">Attack</a></li>";
				}else{
					$this->content .= "<li><a>Some Action</a></li>";
				}

				$vendor_data = $this->npc->showVendor( $creature->id );
				if( $vendor_data['isVendor'] ) {
					$this->content .= "<li>";
					$this->content .= "<img class=\"quest\" src=\"".base_url('assets/images/character/trade.png')."\" />";
					$this->content .= "<a href=\"#vendor".$creature->id."\" data-toggle=\"modal\">I want to browse your goods.</a>";
					$this->content .= "</li>";

					$modal['creature'][$creature->id]['header'] = "";
					$modal['creature'][$creature->id]['header'] .= "<span class=\"player-money\" style=\"float:right;\">".$this->player_data->money."</span>";
					$modal['creature'][$creature->id]['header'] .= "<h2>".$creature->name."</h2>";
					$modal['creature'][$creature->id]['header'] .= "<small>&lt;".$creature->subname."&gt;</small><br />";
					$modal['creature'][$creature->id]['header'] .= "<small><i>Note: To buy an item, click on the icon.</i></small>";
					$modal['creature'][$creature->id]['body'] = "";
					$modal['creature'][$creature->id]['body'] .= "<table width=\"100%\" class=\"content\" id=\"buy\">";
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
							$modal['creature'][$creature->id]['body'] .= "<tr>";
						}
						$modal['creature'][$creature->id]['body'] .= "<td class=\"default\">";
						$modal['creature'][$creature->id]['body'] .= "<table width=\"100%\">";
						$modal['creature'][$creature->id]['body'] .= "<tr>";
						$modal['creature'][$creature->id]['body'] .= "<td width=\"20%\">";
						$modal['creature'][$creature->id]['body'] .= "<div class=\"cursor-trade\">".$item['image']."</div>";
						$modal['creature'][$creature->id]['body'] .= "</td>";
						$modal['creature'][$creature->id]['body'] .= "<td style='padding: 10px;'>";
						$modal['creature'][$creature->id]['body'] .= $item['name'];
						$modal['creature'][$creature->id]['body'] .= "</td>";
						$modal['creature'][$creature->id]['body'] .= "</tr>";
						$modal['creature'][$creature->id]['body'] .= "<tr>";
						$modal['creature'][$creature->id]['body'] .= "<td colspan=\"2\">";
						$modal['creature'][$creature->id]['body'] .= "<span style=\"float:right;\">".$this->core->showMoney($item['cost'])."</span>";
						$modal['creature'][$creature->id]['body'] .= "</td>";
						$modal['creature'][$creature->id]['body'] .= "</tr>";
						$modal['creature'][$creature->id]['body'] .= "</table>";
						if($position == 2) {
							$modal['creature'][$creature->id]['body'] .= "</tr>";
							$position = 1;
						}else{
							$position++;
						}
					}
					$modal['creature'][$creature->id]['body'] .= "</table>";
					$modal['creature'][$creature->id]['body'] .= "<div class=\"content\" id=\"sell\" style=\"display:none;\">";
					$modal['creature'][$creature->id]['body'] .= $sell_inv;
					$modal['creature'][$creature->id]['body'] .= "</div>";
					$modal['creature'][$creature->id]['body'] .= "<hr />";
					$modal['creature'][$creature->id]['footer'] = "";
					$modal['creature'][$creature->id]['footer'] .= "<a class=\"vendor-type btn\" id=\"buy\"><span>Buy</span></a> ";
					$modal['creature'][$creature->id]['footer'] .= "<a class=\"vendor-type btn\" id=\"sell\"><span>Sell</span></a>";
					$modal['creature'][$creature->id]['footer'] .= "<div id=\"trade-log\"></div>";

					$this->content .= $this->core->displayModal( $modal['creature'][$creature->id]['header'], $modal['creature'][$creature->id]['body'], $modal['creature'][$creature->id]['footer'], "vendor".$creature->id );	
				}

				$this->content .= "</ul><hr />";

			}
			$this->template->ingame('game/zone/zone', $this, 'zone');
		}else{
			redirect('world/');
		}
	}
}
?>