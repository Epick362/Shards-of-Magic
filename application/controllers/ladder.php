<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ladder extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if(!$this->tank_auth->is_logged_in()) {
			redirect('/login');
		}
	}

	function page()
	{
		$uid = $this->tank_auth->get_user_id();
		$this->load->library('pagination');
		$config['base_url'] = base_url()."/ladder/page/";
		$config['total_rows'] = $this->db->get('characters')->num_rows();
		$config['per_page'] = 10;

		$this->pagination->initialize($config); 

		if(!$this->uri->segment(3)) {
			$segment = 0;
		}else{
			$segment = $this->uri->segment(3);
		}

		$uid = $this->tank_auth->get_user_id();

		$query_c = $this->db->order_by('level')->get('characters');
		$segment = intval($segment);
		$query_u = $this->db->query("SELECT * FROM users WHERE authlevel = 0 LIMIT ".$segment.", ".$config['per_page']."");

		$tmp = $segment + 1;
		$tmp2= $segment + $config['per_page'];

		$tmp2 = ($tmp2 > $config['total_rows']) ? $config['total_rows'] : $tmp2;

		$data->info = "Showing ".$tmp."-".$tmp2." of ".$config['total_rows']." results";

		$result_c = $query_c->result_array();
		$result_u = $query_u->result_array();

		$data->content = "";

		$result_u = $this->core->groupArray($result_u, 'id');
		$result_c = $this->core->groupArray($result_c, 'user_id');
		foreach( $result_u as $row_u ) {
			$result_c[$row_u['id']]['username'] = $row_u['username'];
			$class_data = $this->core->getClassData($result_c[$row_u['id']]['class']);
			$guild_data = $this->characters->getGuildData($row_u['id']);
			
			$data->content .= "<tr class=\"row\">";

			// USERNAME
			$data->content .= "<td>";
			$data->content .= "<a href=\"".base_url('character/view/id/'.$row_u['id'].'')."\">";
			$data->content .= "<div style=\"color:".$class_data['color']."\">";
			$data->content .= "<b><span style=\"font-size:14px;\">".$result_c[$row_u['id']]['username']."</span></b>";
			$data->content .= "</div>";
			$data->content .= "</a>";
			$data->content .= "</td>";
			// LEVEL
			$data->content .= "<td width=\"40\"><strong><span class=\"epic-font\">".$result_c[$row_u['id']]['level']."</span></strong></td>";
			// CLASS
			$data->content .= "<td width=\"60\" class=\"align-center\">";
			$data->content .= "<div class=\"icon-frame frame32 tip\" title=\"".$class_data['name']."\">";
			$data->content .= "<img src=\"".base_url('assets/images/classes/'.$class_data['image'].'')."\">";
			$data->content .= "</div>";
			$data->content .= "</td>";
			// GUILD
			$data->content .= "<td><a href=\"".base_url('guild/main/view/id/'.$guild_data->id)."\">".$guild_data->name."</a></td>";
			// LOCATION
			$world_data = $this->core->getWorldData( $row_u['id'] );
			$data->content .= "<td>".$world_data->zone->name."</td>";
			// ACTIONS
			if($row_u['id'] != $uid) {
				$data->content .= "<td>";
		$data->content .= "<a class=\"ui-button\" href=\"".base_url('combat/attack/id/'.$row_u['id'].'/pvp/1')."\">";
				$data->content .= "<span class=\"red\">Attack</span>";
				$data->content .= "</a>";
				$data->content .= "</td>";
			}else{
				$data->content .= "<td></td>";
			}
		}
		$this->template->set('subtitle',  'Ladder');
		$this->template->load('template', 'game/ladder/ladder', $data, 'ladder');
	}
	
}