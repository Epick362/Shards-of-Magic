<?php
function uid()
{
	$ci =& get_instance();
	$ci->load->library('tank_auth');
	
	return logged_in() ? $ci->tank_auth->get_user_id() : FALSE;
}


/*
 * vrati ci je user prihlaseny
 */
function logged_in()
{
	$ci =& get_instance();
	$ci->load->library('tank_auth');
	
	return $ci->tank_auth->is_logged_in();
}


function user($uid = false)
{
	$user_id = $uid ? $uid : uid();
	if (!$user_id) return false;
	
	$ci =& get_instance();
	
	$query = $ci->db->query("
		SELECT u.email, u.id, p.roles AS role, p.avatar_25, p.avatar_75 
		FROM users u
		JOIN user_profiles p ON (u.id = p.user_id)
		WHERE u.id = ?
	", array($user_id));
	
	if ($query->num_rows() > 0) return $query->row();
	return false;
}



/*
 * vrati ci je user administrator
 */
function is_admin()
{
	$ci =& get_instance();
	
	if (!$uid = uid()) return FALSE;
	
	if ((int)$uid === 1) return TRUE; // TURBO MEGA ADMIN
	
	$query = $ci->db->select('roles')
					->where('user_id', $uid)
					->get('user_profiles');

	return ($query->row()->roles === 'admin') ? TRUE : FALSE;	
}
?>