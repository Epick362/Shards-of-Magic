<?php
$withdraw = array(
	'name'	=> 'withdraw',
	'id'	=> 'withdraw',
	'value' => set_value('withdraw'),
	'placeholder' => '0',
	'maxlength'	=> 12,
	'size'	=> 20,
	'class' => 'gold',
);
$deposit = array(
	'name'	=> 'deposit',
	'id'	=> 'deposit',
	'value' => set_value('deposit'),
	'placeholder' => '0',
	'maxlength'	=> 12,
	'size'	=> 20,
	'class' => 'gold',
);
?>
<?php if(validation_errors()) { ?>
<table class="error-table mailbox-table default" width="90%">
	<tr>
		<td class="head">
			Oops! Error(s) occured
		</td>
	</tr>
	<tr class="error">
		<td>
			<?php echo validation_errors(); ?>
		</td>
	</tr>
</table>
<?php } ?>
<table class="guild default" width="90%">
	<tr>
		<th>
			<span class="guild-name"><?=$guild->name?></span><span class="default right" style="margin-top:-15px;margin-right:-15px;"><small>Next Reward:</small><br /><table><tr><td><?=$this->guilds->getRewardIcon($next_reward) ?></td><td class="q4" style="width:150px;"><?=$next_reward['name']?></td></tr></table></span><br />
			Level <span class="epic-font"><?=$guild->level?></span> Guild, <span class="epic-font"><?=count($guildMembers) ?></span> <? echo (count($guildMembers) == 1 ? "member" : "members") ?>. <a href="<?=base_url('guild/main/members')?>">(view members)</a> <a href="<?=base_url('guild/manage')?>">(edit)</a><br />
			<?=$this->characters->showXpBar( $guild->xp, $guild->xp_needed, 570, 22 ); ?>
			Leader: <a href="<?=base_url('character/view/id/'.$guild->leader.'/')?>" style="color:<?=$leader_data['color']?>;"><span class="epic-font"><?=$leader_data['level']?></span> <?=$leader_data['username']?></a><br />
			Money in Guild Bank: <?=$this->core->showMoney($guild->BankMoney)?> 
			<? if($has_access_to_w) { ?>
				<a class="ui-button" data-toggle="modal" href="#w"><span class="small">Withdraw</span></a> 
				<?=$this->core->displayModal( "<h1>Withdraw</h1>", "<center>Total money in Guild Bank: ".$this->core->showMoney($guild->BankMoney)."<br />Your Money: ".$this->core->showMoney($this->core->getCharacterMoney($this->tank_auth->get_user_id()))."<br />".form_open('guild/main/withdraw')."".form_input($withdraw)."".form_submit('send', 'Withdraw')."".form_close()."</center>", "", "w") ?>
			<? }else{ ?>
				<a class="ui-button"><span class="blocked small">Withdraw</span></a> 
			<? } ?>
			<a class="ui-button" data-toggle="modal" href="#d"><span class="small">Deposit</span></a>
			<?=$this->core->displayModal( "<h1>Deposit</h1>", "<center>Total money in Guild Bank: ".$this->core->showMoney($guild->BankMoney)."<br />Your Money: ".$this->core->showMoney($this->core->getCharacterMoney($this->tank_auth->get_user_id()))."<br />".form_open('guild/main/deposit')."".form_input($deposit)."".form_submit('send', 'Deposit')."".form_close()."</center>", "", "d") ?>
			<a class="ui-button" data-toggle="modal" href="#log"><span class="small">View Log</span></a><br />
			<small><?=$guild->motd ?></small>
		</th>
	</tr>
	<tr>
		<td class="description">
			<pre><?=$guild->description ?></pre>
		</td>
	</tr>
	<tr>
		<td class="default">Website: <?=($guild->description ? "<a href=\"".$guild->website."\">".$guild->website."</a>" : "-") ?></td>
	</tr>
</table>
<?=$guild_log ?>