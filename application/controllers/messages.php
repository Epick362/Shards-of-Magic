<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		if(!$this->tank_auth->is_logged_in()) {
			redirect('/login');
		}
	}

	function all()
	{
		$uid = $this->tank_auth->get_user_id();
		$username = $this->tank_auth->get_username();

		$this->load->library('pagination');
		$config['base_url'] = base_url()."/messages/all";
		$config['total_rows'] = $this->db->where('to', $username)->where('deleted', 0)->get('messages')->num_rows();
		$config['per_page'] = 10;
		$this->pagination->initialize($config); 

		if(!$this->uri->segment(3)) {
			$segment = 0;
		}else{
			$segment = $this->uri->segment(3);
		}

		$segment = intval($segment);

		$query = $this->db->query("SELECT * FROM messages WHERE `to`= '".$username."' AND `deleted` = 0  ORDER BY `sent` DESC LIMIT ".$segment.",".$config['per_page']."");

		$tmp = $segment + 1;
		$tmp2= $segment + $config['per_page'];

		$tmp2 = ($tmp2 > $config['total_rows']) ? $config['total_rows'] : $tmp2;

		$data->info = "Showing ".$tmp."-".$tmp2." of ".$config['total_rows']." results";

		$result = $query->result_array();

		$data->content = "";

		if($config['total_rows'] > 0) {
			foreach( $result as $row ) {
				$sender_id = $this->core->getCharacterUID($row['from']);
				if($sender_id) {
					$sender_data = $this->characters->getPlayerData( $sender_id );
					$class_data = $this->core->getClassData($sender_data->class);
				}
				$data->content .= "<tr class=\"row\">";
				// DATE
				$data->content .= "<td>";
				$data->content .= date('jS \of M', $row['sent']);
				$data->content .= "</td>";

				// USERNAME
				$data->content .= "<td>";
				if($sender_id) {
					$data->content .= "<a href=\"".base_url('character/view/id/'.$sender_data->user_id.'')."\">";
					$data->content .= "<div style=\"color:".$class_data['color']."\">";
					$data->content .= "<b>".$row['from']."</b>";
					$data->content .= "</div>";
					$data->content .= "</a>";
				}else{
					$data->content .= "<b>".$row['from']."</b>";
				}
				$data->content .= "</td>";
				// TITLE
					$data->content .= "<td>";
				if($row['unread']) {
					$data->content .= "<b>";
					$data->content .= "<a class=\"link\" href=\"".base_url('messages/view/message/'.$row['id'].'')."\">";
					$data->content .= "".$row['subject']."";
					$data->content .= "</a>";
					$data->content .= "</b>";
				}else{
					$data->content .= "<a class=\"link\" href=\"".base_url('messages/view/message/'.$row['id'].'')."\">";
					$data->content .= "".$row['subject']."";
					$data->content .= "</a>";
				}
					$data->content .= "</td>";
				// TRIMMED MESSAGE
				if($row['unread']) {
					$data->content .= "<td><b>";
				}else{
					$data->content .= "<td>";
				}

				$data->content .= "<a class=\"link\" href=\"".base_url('messages/view/message/'.$row['id'].'')."\">";
				$data->content .= "".$this->core->trim_text( $row['message'], 100 )."";
				$data->content .= "</a>";

				if($row['unread']) {
					$data->content .= "</b></td>";
				}else{
					$data->content .= "</td>";
				}
				$data->content .= "<td>";
				$data->content .= "<a class=\"red\" href=\"".base_url('messages/delete/message/'.$row['id'].'')."\"  onclick=\"return confirm('Are you sure you want to delete this?')\">";
				$data->content .= "<div style=\"font-size:26px;\">";
				$data->content .= "✘";
				$data->content .= "</div></a>";
				$data->content .= "</td>";
			}
		}else{
			$data->content .= "<tr class=\"row2\"><td colspan=\"4\">You have no messages</td></tr>";
		}

		$this->template->set('subtitle',  'Mailbox');
		$this->template->load('template', 'game/messages/mailbox', $data, 'messages');
	}

	function view() {
		$uid = $this->tank_auth->get_user_id();
		$username = $this->tank_auth->get_username();
		$content = "";
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('message', $uri_data)) {

			$query = $this->db->where('id', (int)$uri_data['message'])
							->where('to', $username)
							->where('deleted', 0)
							->get('messages');

			if($query->result()) {
				$data->message = $query->row();

				if( $data->message->to = $username && $data->message->unread == 1 ) {
					$query = $this->db->query("UPDATE messages SET unread = '0' WHERE id = '".$data->message->id."'");	
				}

				$sender_id  = $this->core->getCharacterUID($data->message->from);
				if($sender_id) {
					$sender_data= $this->characters->getPlayerData( $sender_id );
					$class_data = $this->core->getClassData( $sender_data->class );
				}
				$data->content .= "<thead>";
				$data->content .= "<tr>";
				$data->content .= "<th class=\"first-child last-child\">";
				$data->content .= "<label for=\"name\">From</label>";
				if($sender_id) {
					$data->content .= "<a href=\"".base_url('character/view/id/'.$this->core->getCharacterUID($data->message->from).'')."\">";
					$data->content .= "<div name=\"name\" style=\"font-size:24px;color:".$class_data['color']."\">";
					$data->content .= "<b><span class=\"epic-font\">".$data->message->from."</span></b>";
					$data->content .= "</div>";
				}else{
					$data->content .= "<b><span class=\"epic-font\">".$data->message->from."</span></b>";
				}
				$data->content .= "</a>";
				$data->content .= "<label for=\"subject\">Subject</label>";
				$data->content .= "<div name=\"subject\">";
				$data->content .= $data->message->subject;
				$data->content .= "</div>";
				$data->content .= "</th>";
				$data->content .= "</tr>";
				$data->content .= "</thead>";
				$data->content .= "<tr class=\"row\">";
				$data->content .= "<td>";
				$data->content .= "".$data->message->message."";
				$data->content .= "</td>";
			}else{
				redirect('error/type/message_not_found');
			}
		}else{
			redirect('error/type/message_not_found');
		}

		$this->template->set('subtitle',  'View Message | Mailbox');
		$this->template->load('template', 'game/messages/view', $data, 'messages');
	}

	function write() {
		$uid = $this->tank_auth->get_user_id();
		$username = $this->tank_auth->get_username();

		$this->load->helper(array('form', 'url', 'auth'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$time = time();

		$this->form_validation->set_rules('recipient', 'Recipient', 'trim|required|xss_clean|callback_recipient_check');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean|max_length[64]');
		$this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean|max_length[512]');

		if ($this->form_validation->run()) {
			$send = $this->core->SendMessage( $username, $this->form_validation->set_value('recipient'), $this->form_validation->set_value('subject'), $this->form_validation->set_value('message'), $time);
			if ($send) {
				redirect('messages/');
			}
		}

		$this->template->set('subtitle',  'Send Message | Mailbox');
		$this->template->load('template', 'game/messages/write', '', 'messages');		
	}

	public function recipient_check( $str )
	{
		$uid = $this->tank_auth->get_user_id();
		$query = $this->db->where('username', $str)->get('users');

		if($query->result()) {
			$query = $query->row();
			if($query->id != $uid) {
				return TRUE;
			}
			$this->form_validation->set_message('recipient_check', 'You can\'t send messages to yourself.');
			return FALSE;
		}
		else
		{
			$this->form_validation->set_message('recipient_check', 'The recipient '.$str.' doesn\'t exist.');
			return FALSE;
		}
	}

	function delete() {
		$uid = $this->tank_auth->get_user_id();
		$username = $this->tank_auth->get_username();
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('message', $uri_data)) {
			$query = $this->db->where('id', (int)$uri_data['message'])
							->where('to', $username)
							->get('messages');

			if($query->result()) {
				$this->db->where('id', (int)$uri_data['message'])
						->update('messages', array('deleted' => 1));
				redirect('messages/');
			}
			redirect('error/show/type/cant_delete_message');
		}else{
			redirect('error/');
		}
	}
}
?>