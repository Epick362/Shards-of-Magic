<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
		var $template_data = array();

		function set($name, $value)
		{
				$this->template_data[$name] = $value;
		}
	
		function load($template = '', $view = '' , $view_data = '', $css = '' , $return = FALSE)
		{               
			$this->CI =& get_instance();
			
			$this->CI->load->helper('core_helper');

			$view_data->css_file = $css;
			if($this->CI->tank_auth->get_user_id()) {
				$uid = $this->CI->tank_auth->get_user_id();
				$view_data->player_data = $this->CI->characters->getPlayerData( $uid, 1 );
				$view_data->guild_data = $this->CI->guilds->getGuildData( $view_data->player_data->guildData->id );
				if ($this->CI->characters->isTravelling( $uid )) {
					$update_data = $this->CI->active->TravellingCheck( $uid );
				}
				$stats_data = $this->CI->characters->setClassStats( $uid, $view_data->player_data );
				$bonus_stats = $this->CI->characters->getCharacterStats( $view_data->player_data );

				$view_data->player_data->sta = $stats_data['sta'] + $bonus_stats['sta'];
				$view_data->player_data->dex = $stats_data['dex'] + $bonus_stats['dex'];
				$view_data->player_data->str = $stats_data['str'] + $bonus_stats['str'];
				$view_data->player_data->int = $stats_data['int'] + $bonus_stats['int'];
				$view_data->player_data->luc = $stats_data['luc'] + $bonus_stats['luc'];
				
				$health_data = $this->CI->characters->setCharacterHealth( $view_data->player_data );
				$mana_data = $this->CI->characters->setCharacterMana( $view_data->player_data );

				$view_data->player_data->health_max = $health_data['health_max'];
				$view_data->player_data->health = $health_data['health'];
				$view_data->player_data->mana_max = $mana_data['mana_max'];
				$view_data->player_data->mana = $mana_data['mana'];

				$update_data = $this->CI->active->regenerateResources( $view_data->player_data );
				$view_data->world_data   = $this->CI->core->getWorldData( $uid );
				$view_data->chat_data = $this->CI->core->getChatFriendData( $uid );
				$view_data->flash_data = $this->CI->session->flashdata('message');
			}
			$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
			return $this->CI->load->view($template, $this->template_data, $return);
		}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */