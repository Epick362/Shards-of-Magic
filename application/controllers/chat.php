<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->username = $this->session->userdata('username');
		$this->openChatBoxes = $this->session->userdata('openChatBoxes');
		$this->chatHistory = $this->session->userdata('chatHistory');
		$this->tsChatBoxes = $this->session->userdata('tsChatBoxes');
		if(!$this->tsChatBoxes) {
			$this->tsChatBoxes = array();
		}
	}

	function chatHeartbeat() {
		
		$query = $this->db->query("select * from chat where (chat.to = '".$this->username."' AND recd = 0) order by id ASC");
		$items = '';

		foreach ($query->result_array() as $chat ) {

			if(!$this->chatHistory[$chat['from']]) {
				$this->chatHistory[$chat['from']] = '';
			}
			if(!$this->openChatBoxes[$chat['from']]) {
				$this->openChatBoxes[$chat['from']] = '';
			}

			if (!$this->openChatBoxes[$chat['from']] && $this->chatHistory[$chat['from']])
			{
				$items = $this->chatHistory['from'];
			}

			$chat['message'] = $this->sanitize($chat['message']);

			$items .= <<<EOD
						   {
				"s": "0",
				"f": "{$chat['from']}",
				"m": "{$chat['message']}"
		   },
EOD;

			$this->chatHistory[$chat['from']] .= <<<EOD
							   {
				"s": "0",
				"f": "{$chat['from']}",
				"m": "{$chat['message']}"
		   },
EOD;

			unset($this->tsChatBoxes[$chat['from']]);
			$this->openChatBoxes[$chat['from']]  = $chat['sent'];
		} // END FOREACH

	if ($this->openChatBoxes) {
	foreach ($this->openChatBoxes as $chatbox => $time) {
		if (!array_key_exists($chatbox, $this->tsChatBoxes)) {
			$now = time()-strtotime($time);
			$time = date('g:iA M dS', strtotime($time));

			$message = "Sent at $time";
			if ($now > 180) {
				$items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;

			if(!$this->chatHistory[$chatbox]) {
				$this->chatHistory[$chatbox] = '';
			}

				$this->chatHistory[$chatbox] .= <<<EOD
							   {
				"s": "2",
				"f": "$chatbox",
				"m": "{$message}"
		   },
EOD;

			$this->session->set_userdata(array('tsChatBoxes' => array($chatbox => 1)));
		}
		}
	}
}

		if($this->chatHistory) {
			$this->session->set_userdata(array('chatHistory' => $this->chatHistory));
		}
		if($this->openChatBoxes) {
			$this->session->set_userdata(array('openChatBoxes' => $this->openChatBoxes));
		}

		$query = $this->db->query("update chat set recd = 1 where chat.to = '".$this->username."' and recd = 0");

		if ($items != '') {
			$items = substr($items, 0, -1);
		}
	header('Content-type: application/json');
	?>
	{
			"items": [
				<?php echo $items;?>
	        ]
	}

	<?php
				exit(0);
	}

	function chatBoxSession($chatbox) {
		
		$items = '';
		if ($this->chatHistory[$chatbox]) {
			$items = $this->chatHistory[$chatbox];
		}

		return $items;
	}

	function startChatSession() {
		$items = '';
		if ($this->openChatBoxes) {
			foreach ($this->openChatBoxes as $chatbox => $void) {
				$items .= $this->chatBoxSession($chatbox);
			}
		}

		if ($items != '') {
			$items = substr($items, 0, -1);
		}

		header('Content-type: application/json');
	?>
		{
				"username": "<?php echo $this->username;?>",
				"items": [
					<?php echo $items;?>
		        ]
		}
	<?php

		exit(0);
	}

	function sendChat() {
		$from = $this->username;
		$to = $_POST['to'];
		$message = $_POST['message'];

		$this->session->set_userdata(array('openChatBoxes' => array($_POST['to'] => date('Y-m-d H:i:s', time()))));
		
		$messagesan = $this->sanitize($message);

		$this->session->set_userdata(array('chatHistory' => array($_POST['to'] => <<<EOD
						   {
				"s": "1",
				"f": "{$to}",
				"m": "{$messagesan}"
		   },
EOD
)));

		unset($this->tsChatBoxes[$_POST['to']]);
		$this->session->set_userdata(array('tsChatBoxes' => $this->tsChatBoxes));

		$query = $this->db->query("insert into chat (chat.from,chat.to,message,sent) values ('".mysql_real_escape_string($from)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."',NOW())");
		echo "1";
		exit(0);
	}

	function closeChat() {
		unset($this->openChatBoxes[$_POST['chatbox']]);
		unset($this->chatHistory[$_POST['chatbox']]);
		unset($this->tsChatBoxes[$_POST['chatbox']]);
		$this->session->set_userdata(array('openChatBoxes' => $this->openChatBoxes));
		$this->session->set_userdata(array('chatHistory' => $this->chatHistory));
		$this->session->set_userdata(array('tsChatBoxes' => $this->tsChatBoxes));
		echo "1";
		exit(0);
	}

	function sanitize($text) {
		$text = htmlspecialchars($text, ENT_QUOTES);
		$text = str_replace("\n\r","\n",$text);
		$text = str_replace("\r\n","\n",$text);
		$text = str_replace("\n","<br>",$text);
		return $text;
	}
}