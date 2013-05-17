<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function all()
	{
		$this->load->library('pagination');
		$config['base_url'] = base_url()."/messages/all";
		$config['total_rows'] = $this->db->where('to', $this->player_data->name)->where('deleted', 0)->get('messages')->num_rows();
		$config['per_page'] = 10;
		$this->pagination->initialize($config); 

		if(!$this->uri->segment(3)) {
			$segment = 0;
		}else{
			$segment = $this->uri->segment(3);
		}

		$segment = intval($segment);

		$query = $this->db->query("SELECT * FROM messages WHERE `to`= '".$this->player_data->name."' AND `deleted` = 0  ORDER BY `sent` DESC LIMIT ".$segment.",".$config['per_page']."");

		$tmp = $segment + 1;
		$tmp2= $segment + $config['per_page'];

		$tmp2 = ($tmp2 > $config['total_rows']) ? $config['total_rows'] : $tmp2;

		$this->info = "Showing ".$tmp."-".$tmp2." of ".$config['total_rows']." results";

		$result = $query->result_array();

		$this->content = "";

		if($config['total_rows'] > 0) {
			foreach( $result as $row ) {
				$sender_id = $this->core->getCharacterUID($row['from']);
				if($sender_id) {
					$sender_data = $this->characters->getPlayerData( $sender_id );
					$class_data = $this->core->getClassData($sender_data->class);
				}
				$this->content .= "<tr>";
				// DATE
				$this->content .= "<td>";
				$this->content .= date('jS \of M', $row['sent']);
				$this->content .= "</td>";

				// USERNAME
				$this->content .= "<td>";
				if($sender_id) {
					$this->content .= "<a href=\"".base_url('character/view/id/'.$sender_data->user_id.'')."\">";
					$this->content .= "<div style=\"color:".$class_data['color']."\">";
					$this->content .= "<b>".$row['from']."</b>";
					$this->content .= "</div>";
					$this->content .= "</a>";
				}else{
					$this->content .= "<b>".$row['from']."</b>";
				}
				$this->content .= "</td>";
				// TITLE
					$this->content .= "<td>";
				if($row['unread']) {
					$this->content .= "<b>";
					$this->content .= "<a class=\"link\" href=\"".base_url('messages/view/message/'.$row['id'].'')."\">";
					$this->content .= "".$row['subject']."";
					$this->content .= "</a>";
					$this->content .= "</b>";
				}else{
					$this->content .= "<a class=\"link\" href=\"".base_url('messages/view/message/'.$row['id'].'')."\">";
					$this->content .= "".$row['subject']."";
					$this->content .= "</a>";
				}
					$this->content .= "</td>";
				// TRIMMED MESSAGE
				if($row['unread']) {
					$this->content .= "<td><b>";
				}else{
					$this->content .= "<td>";
				}

				$this->content .= "<a class=\"link\" href=\"".base_url('messages/view/message/'.$row['id'].'')."\">";
				$this->content .= "".$this->core->trim_text( $row['message'], 100 )."";
				$this->content .= "</a>";

				if($row['unread']) {
					$this->content .= "</b></td>";
				}else{
					$this->content .= "</td>";
				}
				$this->content .= "<td>";
				$this->content .= "<a class=\"red\" href=\"".base_url('messages/delete/message/'.$row['id'].'')."\"  onclick=\"return confirm('Are you sure you want to delete this?')\">";
				$this->content .= "<div style=\"font-size:26px;\">";
				$this->content .= "✘";
				$this->content .= "</div></a>";
				$this->content .= "</td>";
			}
		}else{
			$this->content .= "<tr class=\"row2\"><td colspan=\"4\">You have no messages</td></tr>";
		}

		$this->template->set('subtitle',  'Mailbox');
		$this->template->ingame('game/messages/mailbox', $this, 'messages');
	}

	function view() {
		$content = "";
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('message', $uri_data)) {

			$query = $this->db->where('id', (int)$uri_data['message'])
							->where('to', $this->player_data->name)
							->where('deleted', 0)
							->get('messages');

			if($query->result()) {
				$this->message = $query->row();

				if( $this->message->to = $this->player_data->name && $this->message->unread == 1 ) {
					$query = $this->db->query("UPDATE messages SET unread = '0' WHERE id = '".$this->message->id."'");	
				}

				$sender_id  = $this->core->getCharacterUID($this->message->from);
				if($sender_id) {
					$sender_data= $this->characters->getPlayerData( $sender_id );
					$class_data = $this->core->getClassData( $sender_data->class );
				}
				$this->content .= "<thead>";
				$this->content .= "<tr>";
				$this->content .= "<th class=\"first-child last-child\">";
				$this->content .= "<label for=\"name\">From</label>";
				if($sender_id) {
					$this->content .= "<a href=\"".base_url('character/view/id/'.$this->core->getCharacterUID($this->message->from).'')."\">";
					$this->content .= "<div name=\"name\" style=\"font-size:24px;color:".$class_data['color']."\">";
					$this->content .= "<b><span class=\"epic-font\">".$this->message->from."</span></b>";
					$this->content .= "</div>";
				}else{
					$this->content .= "<b><span class=\"epic-font\">".$this->message->from."</span></b>";
				}
				$this->content .= "</a>";
				$this->content .= "<label for=\"subject\">Subject</label>";
				$this->content .= "<div name=\"subject\">";
				$this->content .= $this->message->subject;
				$this->content .= "</div>";
				$this->content .= "</th>";
				$this->content .= "</tr>";
				$this->content .= "</thead>";
				$this->content .= "<tr>";
				$this->content .= "<td>";
				$this->content .= "".$this->message->message."";
				$this->content .= "</td>";
			}else{
				redirect('error/type/message_not_found');
			}
		}else{
			redirect('error/type/message_not_found');
		}

		$this->player_data = $this->player_data;
		$this->template->set('subtitle',  'View Message | Mailbox');
		$this->template->ingame('game/messages/view', $this, 'messages');
	}

	function write() {
		$this->load->helper(array('form', 'url', 'auth'));
		$this->load->library('form_validation');
		$this->load->library('security');
		$time = time();

		$this->form_validation->set_rules('recipient', 'Recipient', 'trim|required|xss_clean|callback_recipient_check');
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|xss_clean|max_length[64]');
		$this->form_validation->set_rules('message', 'Message', 'trim|required|xss_clean|max_length[512]');

		if ($this->form_validation->run()) {
			$send = $this->core->SendMessage( $this->player_data->name, $this->form_validation->set_value('recipient'), $this->form_validation->set_value('subject'), $this->form_validation->set_value('message'), $time);
			if ($send) {
				redirect('messages/');
			}
		}

		$this->template->set('subtitle',  'Send Message | Mailbox');
		$this->template->ingame('game/messages/write', $this, 'messages');		
	}

	public function recipient_check( $str )
	{
		$query = $this->db->where('username', $str)->get('users');

		if($query->result()) {
			$query = $query->row();
			if($query->id != $this->uid) {
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
		$uri_data = $this->uri->uri_to_assoc(3);
		if(array_key_exists('message', $uri_data)) {
			$query = $this->db->where('id', (int)$uri_data['message'])
							->where('to', $this->player_data->name)
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