<?
class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();

		if(!$this->ion_auth->logged_in()) {
			redirect('auth/login');
		}
		$user = $this->ion_auth->user()->row();
		$this->uid = $user->user_id; 
		$this->player_data = $this->characters->getPlayerData($user->user_id);
	}
}
?>