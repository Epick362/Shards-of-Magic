<?
class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();

		if(!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		$user = $this->ion_auth->user()->row();
		$this->uid = $user->user_id;
		$this->cid = $this->session->userdata('character');
		if(!$this->cid) {
			redirect('welcome');
		}
		$this->active->TravellingCheck($this->cid);
		$this->player_data = $this->characters->getCharacterData($user->user_id, $this->cid, 1);
		$this->world_data = $this->core->getWorldData($this->cid);

		//echo '<pre>'; print_r($this->session->all_userdata()); echo '</pre>';
	}
}
?>