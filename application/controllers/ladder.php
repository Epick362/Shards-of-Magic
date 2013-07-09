<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ladder extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function page()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url()."/ladder/page/";
		$config['total_rows'] = $this->db->get('characters')->num_rows();
		$config['per_page'] = 10;

		$config['full_tag_open'] = '<span class="pagination"><ul>';
		$config['full_tag_close'] = '</ul></span>';

		$this->pagination->initialize($config); 

		if(!$this->uri->segment(3)) {
			$segment = 0;
		}else{
			$segment = $this->uri->segment(3);
		}

		$query_c = $this->db->order_by('level')->get('characters');
		$segment = intval($segment);
		$query_u = $this->db->query("SELECT * FROM users LIMIT ".$segment.", ".$config['per_page']."");

		$tmp = $segment + 1;
		$tmp2= $segment + $config['per_page'];

		$tmp2 = ($tmp2 > $config['total_rows']) ? $config['total_rows'] : $tmp2;

		$this->info = "Showing ".$tmp."-".$tmp2." of ".$config['total_rows']." results";

		$result_c = $query_c->result_array();
		$result_u = $query_u->result_array();

		$this->content = "";

		$result_u = $this->core->groupArray($result_u, 'id');
		$result_c = $this->core->groupArray($result_c, 'user_id');
		foreach( $result_u as $row_u ) {
			$result_c[$row_u['id']]['username'] = $row_u['username'];
			$class_data = $this->core->getClassData($result_c[$row_u['id']]['class']);
			$guild_data = $this->characters->getGuildData($row_u['id']);
			
			$this->content .= "<tr>";

			// USERNAME
			$this->content .= "<td>";
			$this->content .= "<a href=\"".base_url('character/view/id/'.$row_u['id'].'')."\">";
			$this->content .= "<div style=\"color:".$class_data['color']."\">";
			$this->content .= "<b><span style=\"font-size:14px;\">".$result_c[$row_u['id']]['name']."</span></b>";
			$this->content .= "</div>";
			$this->content .= "</a>";
			$this->content .= "</td>";
			// LEVEL
			$this->content .= "<td width=\"40\"><strong><span class=\"epic-font\">".$result_c[$row_u['id']]['level']."</span></strong></td>";
			// CLASS
			$this->content .= "<td width=\"60\" class=\"align-center\">";
			$this->content .= "<div class=\"icon-frame frame32 tip\" title=\"".$class_data['name']."\">";
			$this->content .= "<img src=\"".base_url('assets/images/classes/'.$class_data['image'].'')."\">";
			$this->content .= "</div>";
			$this->content .= "</td>";
			// GUILD
			$this->content .= "<td><a href=\"".base_url('guild/main/view/id/'.$guild_data->id)."\">".$guild_data->name."</a></td>";
			// LOCATION
			$world_data = $this->core->getWorldData( $row_u['id'] );
			$this->content .= "<td>".$world_data->zone->name."</td>";
			// ACTIONS
			if($row_u['id'] != $this->uid) {
				$this->content .= "<td>";
				$this->content .= "<a class=\"ui-button\" href=\"".base_url('combat/attack/id/'.$row_u['id'].'/pvp/1')."\">";
				$this->content .= "<span class=\"red\">Attack</span>";
				$this->content .= "</a>";
				$this->content .= "</td>";
			}else{
				$this->content .= "<td></td>";
			}
		}
		$this->template->set('subtitle',  'Ladder');
		$this->template->ingame('game/ladder/ladder', $this, 'ladder');
	}
	
}