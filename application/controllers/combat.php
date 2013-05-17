<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Combat extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index() {
		$finished = FALSE;
		$rounds = 0;
		$result = array('start' => '', 'attacker' => '', 'opponent' => '', 'end' => '', 'awards' => '');

		if (!$this->fight->isInCombat($uid)) {
			redirect('character/');
		}
		$query = $this->db->select('id')->where('attacker', $uid)->or_where('opponent', $uid)->get('combat');
		$query = $query->row();

		$cid   = $query->id;

		// Collect Data
		$combat_data = $this->fight->getCombatData( $cid );
		
		$player_data = $this->characters->getPlayerData( $uid );

		if(!$combat_data['pvp']) {
			$opponent_data = $this->npc->getCreatureData($combat_data['opponent']);
		}else{
			$opponent_data = $this->characters->getPlayerData($combat_data['opponent']);
			$opponent_data->name = $opponent_data->username;		
		}
		$player_data->armor_reduction = $this->characters->getArmorReduction( $player_data, $opponent_data->level );

		$result['start'] = 'Battle starts<br />';

		while( $player_data->health > 0 || $opponent_data->health > 0 || $rounds > MAX_COMBAT_ROUNDS ) {
			if( $player_data->health <= 0 ) {
				break;
			}
					// ENEMY
			if($combat_data['pvp']) {
				$opponent['mainhand'] = $this->fight->getCharacterDamage($opponent_data, 1);
				$opponent['offhand'] = $this->fight->getCharacterDamage($opponent_data, 2);
				$opponent_damage = $opponent['mainhand'] + $opponent['offhand'];
			}else{
				$opponent_damage = mt_rand( $opponent_data->min_damage, $opponent_data->max_damage );
			}
			$opponent_damage -= floor( $opponent_damage * ( $player_data->armor_reduction / 100 ) );

					// PLAYER
			$player['mainhand'] = $this->fight->getCharacterDamage($player_data, 1);
			$player['offhand'] = $this->fight->getCharacterDamage($player_data, 2);
			$player_damage = $player['mainhand'] + $player['offhand'];
			$player_damage -= floor( $player_damage * ( $opponent_data->armor_reduction / 100 ) );

			$opponent_data->health -= $player_damage;
			$result['opponent'] .= $opponent_data->name.' took '.$player_damage.' damage.';
			$result['opponent'] .= 'Has '.$opponent_data->health.' Health left.<br />';

			if( $opponent_data->health <= 0 ) {
				break;
			}
			$player_data->health -= $opponent_damage;
			$result['attacker'] .= $player_data->username.' took '.$opponent_damage.' damage.';
			$result['attacker'] .= 'Has '.$player_data->health.' Health left.<br />';
		} // Endwhile

		$message_text = $player_data->username.' attacked you.';
		if( $player_data->health <= 0 ) {
			$player_data->health = 0;
			$result['end'] .= $opponent_data->name.' won the battle.';
			$message_text .= 'You won.';
			$finished = TRUE;
		}elseif( $opponent_data->health <= 0 ) {
			if($combat_data['pvp']) {
				$opponent_data->health = floor($opponent_data->health_max * 0.2);
			}
			$result['end'] .= $player_data->username.' won the battle.<br />';
			$message_text .= 'You lost.';
			$finished = TRUE;

		}

		if($finished) {
			$after_battle['a'] = array('health' => $player_data->health, 'mana' => $player_data->mana);

			if($combat_data['pvp']) {
				$after_battle['o'] = array('health' => $opponent_data->health, 'mana' => $opponent_data->mana);		
				$this->db->where('user_id', $opponent_data->user_id)->update('characters', $after_battle['o']);
				$this->core->SendMessage( COMBAT_MESSAGE_NAME, $opponent_data->name, COMBAT_MESSAGE_SUBJECT, $message_text);
			}else{
				// Gain Slain for Quest
				$slain_message = $this->quest->updateQuest( $uid, 1, $opponent_data->id );
				foreach($slain_message as $i_temp => $msg_temp) {
					$result['awards'] .= $msg_temp;
				}
				$xp_gained = $this->npc->npcXP( $uid, $player_data, $opponent_data );
				$result['awards'] .= $xp_gained.' XP awarded.<br />';
			}
			$this->db->where('user_id', $player_data->user_id)->update('characters', $after_battle['a']);

			$this->db->where('id', $cid)->delete('combat');
		}
		$data->result = $result;

		// Display template
		$this->template->set('subtitle',  'Combat');
		$this->template->ingame('game/combat/combat', $data, 'combat');
	}

	function attack() {
		$data = $this->uri->uri_to_assoc();
		$combat['opponent'] = (int)$data['id'];
		$combat['pvp'] = (int)$data['pvp'];

		$combat['attacker'] = $this->uid;

		if(!$this->fight->isInCombat($this->uid)) {
			$this->db->insert('combat', $combat);
		}else{
			redirect('error/show/type/you_or_opponent_in_combat');
		}

		redirect('combat/');
	}
}

?>