<?php 

	function GetCharacterStats( $player, $equip ) {

		$stat['sta']  = $player->sta;
		$stat['int']  = $player->int;
		$stat['str']  = $player->str;
		$stat['dex']  = $player->dex;
		$stat['luc']  = $player->luc;

		if ($equip) {
			if (array_key_exists('mainhand', $equip)) {
				if (array_key_exists('id', $equip['mainhand'])) {
					$stat['sta'] += $equip['mainhand']['sta'];
					$stat['int'] += $equip['mainhand']['int'];
					$stat['str'] += $equip['mainhand']['str'];
					$stat['dex'] += $equip['mainhand']['dex'];
					$stat['luc'] += $equip['mainhand']['luc'];
				}
			}
			if (array_key_exists('offhand', $equip)) {
				if (array_key_exists('id', $equip['offhand'])) {
					$stat['sta'] += $equip['offhand']['sta'];
					$stat['int'] += $equip['offhand']['int'];
					$stat['str'] += $equip['offhand']['str'];
					$stat['dex'] += $equip['offhand']['dex'];
					$stat['luc'] += $equip['offhand']['luc'];
				}
			}
			if (array_key_exists('head', $equip)) {
				if (array_key_exists('id', $equip['head'])) {
					$stat['sta'] += $equip['head']['sta'];
					$stat['int'] += $equip['head']['int'];
					$stat['str'] += $equip['head']['str'];
					$stat['dex'] += $equip['head']['dex'];
					$stat['luc'] += $equip['head']['luc'];
				}
			}
			if (array_key_exists('shoulders', $equip)) {
				if (array_key_exists('id', $equip['shoulders'])) {
					$stat['sta'] += $equip['shoulders']['sta'];
					$stat['int'] += $equip['shoulders']['int'];
					$stat['str'] += $equip['shoulders']['str'];
					$stat['dex'] += $equip['shoulders']['dex'];
					$stat['luc'] += $equip['shoulders']['luc'];
				}
			}
			if (array_key_exists('cloak', $equip)) {
				if (array_key_exists('id', $equip['cloak'])) {
					$stat['sta'] += $equip['cloak']['sta'];
					$stat['int'] += $equip['cloak']['int'];
					$stat['str'] += $equip['cloak']['str'];
					$stat['dex'] += $equip['cloak']['dex'];
					$stat['luc'] += $equip['cloak']['luc'];
				}
			}
			if (array_key_exists('chest', $equip)) {
				if (array_key_exists('id', $equip['chest'])) {
					$stat['sta'] += $equip['chest']['sta'];
					$stat['int'] += $equip['chest']['int'];
					$stat['str'] += $equip['chest']['str'];
					$stat['dex'] += $equip['chest']['dex'];
					$stat['luc'] += $equip['chest']['luc'];
				}
			}
			if (array_key_exists('pants', $equip)) {
				if (array_key_exists('id', $equip['pants'])) {
					$stat['sta'] += $equip['pants']['sta'];
					$stat['int'] += $equip['pants']['int'];
					$stat['str'] += $equip['pants']['str'];
					$stat['dex'] += $equip['pants']['dex'];
					$stat['luc'] += $equip['pants']['luc'];
				}
			}
			if (array_key_exists('boots', $equip)) {
				if (array_key_exists('id', $equip['boots'])) {
					$stat['sta'] += $equip['boots']['sta'];
					$stat['int'] += $equip['boots']['int'];
					$stat['str'] += $equip['boots']['str'];
					$stat['dex'] += $equip['boots']['dex'];
					$stat['luc'] += $equip['boots']['luc'];
				}
			}
		}
		return $stat;
	}

?>