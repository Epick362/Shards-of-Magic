<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Guilds class
 *
 */
class Guilds
{
	function __construct()
	{
		$this->ci =& get_instance();
		$this->maxguildlevel= 10;
	}

	function getGuildData( $guild ) {
		$query = $this->ci->db->where('guildid', $guild)->get('guild');

		if($query->num_rows() > 0) {
			$result = $query->row();
			$result->xp_needed = $this->ci->guilds->experienceNeeded( $result->level );
			$this->ci->guilds->experienceHandler($result);
			return $result;
		}else{
			return false;
		}
	}

	function getGuildMoney( $guild ) {
		$query = $this->ci->db->select('BankMoney')->where('guildid', $guild)->get('guild');
		$data = $query->row();
		$return = $data->BankMoney;
		return $return;
	}

	function getGuildLog( $guild ) {
		$query = $this->ci->db->where('guildid', $guild)->order_by('time', 'desc')->get('guild_logs');
		return $query->result();
	}

    function getGuildMembers( $guild ) {
        $memberList = $this->ci->db->query("
        SELECT
        `characters`.`user_id`,
        `users`.`username`,
        `characters`.`class`,
        `characters`.`gender`,
        `characters`.`level`,
        `guild_member`.`rank`
        FROM `characters` AS `characters`
        LEFT JOIN `users` AS `users` ON `characters`.`user_id`=`users`.`id`
        LEFT JOIN `guild_member` AS `guild_member` ON `guild_member`.`user`=`characters`.`user_id` AND `guild_member`.`guildid`=". $guild ."
        LEFT JOIN `guild` AS `guild` ON `guild`.`guildid`=". $guild ."
        WHERE `guild`.`guildid`=". $guild ." AND `guild_member`.`user`=`characters`.`user_id` 
        ORDER BY `guild_member`.`rank` DESC");
        return $memberList->result();
     }

	function createGuild( $name, $leader, $info) {
		$time = time();
			$data = array('name'=>$name, 'leader'=>$leader, 'description'=>$info, 'createdate'=>$time );
			$money = $this->ci->core->getCharacterMoney($leader);

			if( $money >= GUILD_CREATE_COST ) {
				$new_money = array('money' => $money - GUILD_CREATE_COST);
				$this->ci->db->where('user_id', $leader)->update('characters', $new_money);

			$create = $this->ci->db->insert('guild', $data);
			$query = $this->ci->db->select('guildid')->where('leader', $leader)->get('guild');
			$guild_data = $query->row();
			$data_member = array( 'guildid'=>$guild_data->guildid, 'user'=>$leader, 'rank'=>3, 'note'=>'Leader' );
			$create_member = $this->ci->db->insert('guild_member', $data_member);

		 	return $create;
		}else{
			return false;
		}
	}

	function createLog( $guild, $action, $amount = 0, $user = "" ) {
		if($user) {
			$username = $this->ci->core->getCharacterName( $user );
			$amount /= 10000;
		}
		switch($action) {
			case 1:
				$message = $username.' deposited '.$amount.'<img src="'.base_url('/assets/images/core/gold.png').'" style="vertical-align:middle;" /> to guild bank.';
				break;
			case 2:
				$message = '<a class="red">'.$username.' withdrawed '.$amount.'<img src="'.base_url('/assets/images/core/gold.png').'" style="vertical-align:middle;" /> from guild bank.</a>';
				break;
			case 3:
				$message = '<a class="yellow">Guild reached level '.$amount.'. Congratulations!</a>';
				break;
			default:
				$message = 'UNKNOWN MESSAGE';
		}
		$data = array('guildid' => $guild, 'message' => $message, 'time' => time());
		$this->ci->db->insert('guild_logs', $data);
	}

	function depositToGuild( $user, $guildid, $amount ) {
		$pmoney = $this->ci->core->getCharacterMoney($user);
		$gmoney = $this->ci->guilds->getGuildMoney($guildid);
		if($pmoney >= $amount) {
			$pnew_money = array('money' => $pmoney - $amount);
			$this->ci->db->where('user_id', $user)->update('characters', $pnew_money);  

			$gnew_money = array('BankMoney' => $gmoney + $amount);
			$this->ci->db->where('guildid', $guildid)->update('guild', $gnew_money);

			$this->ci->guilds->createLog( $guildid, 1, $amount, $user );
			return true;
		}else{
			return false;
		}
	}

	function withdrawFromGuild( $user, $guildid, $amount ) {
		$pmoney = $this->ci->core->getCharacterMoney($user);
		$gmoney = $this->ci->guilds->getGuildMoney($guildid);
		if($gmoney >= $amount) {
			$pnew_money = array('money' => $pmoney + $amount);
			$this->ci->db->where('user_id', $user)->update('characters', $pnew_money);  

			$gnew_money = array('BankMoney' => $gmoney - $amount);
			$this->ci->db->where('guildid', $guildid)->update('guild', $gnew_money);

			$this->ci->guilds->createLog( $guildid, 2, $amount, $user );
			return true;
		}else{
			return false;
		}
	}

	function experienceNeeded( $guild_level ) {
		$xp_needed = floor(8000 * pow(1.15, $guild_level) * 2);
		return $xp_needed;
	}

	function experienceHandler ( $guild_data ) {
		if (($guild_data->xp_needed <= $guild_data->xp) && $guild_data->level < $this->maxguildlevel){

			$data = array('level' => $guild_data->level + 1, 'xp' => $guild_data->xp - $guild_data->xp_needed);
			$next_level = $guild_data->level + 1;
			$this->ci->guilds->createLog( $guild_data->guildid, 3, $next_level );
			$this->ci->db->where('guildid', $guild_data->guildid);
			$this->ci->db->update('guild', $data);

			return TRUE;
		}else{
			return FALSE;
		}
	}

	function experienceChange ( $player_data, $value ) {
		$guild_data = $this->ci->guilds->getGuildData( $player_data->guildData->id );
		if($guild_data->level < $this->maxguildlevel) {
			$xp = array('xp'=>$guild_data->xp + (int)$value);

			$this->ci->db->where('guildid', $guild_data->guildid);
			$this->ci->db->update('guild', $xp);

			return TRUE;
		}else{
			return FALSE;
		}
	}

	function getRewardIcon( $data ) {
		$result = "<div class=\"item_tooltip\">";
		$result .= "<div class=\"slot\">";
		$result .= "	<div class=\"slot-inner\">";
		$result .= "		<div class=\"slot-contents\">";
		$result .= "			<a class=\"item\">";
		$result .= "				<img src=\"".base_url('/assets/images/icons/'.$data['image'].'.jpg')."\" class=\"q4\" />";
		$result .= "			<span class=\"frame\"></span></a>";
		$result .= "		</div>";
		$result .= "	</div>";
		$result .= "</div>";
		$result .= "		<div class=\"tooltip\">";
		$result .= "			<h2 class=\"q4\">".$data['name']."</h2>";
		$result .= "			".$data['description']."";
		$result .= "		</div>";
		$result .= "</div>";

		return $result;
	}

	function hasManageRights( $uid, $guild ) {
		$query = $this->ci->db->where('user', $uid)->where('guildid', $guild)->get('guild_member');
		$data = $query->row();

		if($data->rank > 2) {
			return true;
		}else{
			return false;
		}
	}

	function rankToName( $int, $user ) {
		switch($int) {
			case 1: 
				$string = "Novice";
				break;
			case 2:
				$string = "Member";
				break;
			case 3:
				$string = "Officer";
				break;
			default:
				$string = "ERROR";
				log_message('error', 'Unknown Guild Rank for player ID'.$user.'.');
		}
		return $string;
	}
}
?>