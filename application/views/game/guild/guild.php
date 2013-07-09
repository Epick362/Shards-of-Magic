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
$submit_w = array(
	'name'  => 'send',
	'value' => 'Withdraw',
	'class' => 'btn btn-primary'
);
$submit_d = array(
	'name'  => 'send',
	'value' => 'Deposit',
	'class' => 'btn btn-primary'
);
?>
<?php if(validation_errors()) { ?>
<div class="row-fluid">
	<div class="offset1 span10">
		<div class="alert alert-error">
			<strong>Oops! There were some problems:</strong> <?php echo validation_errors(); ?>
		</div>
	</div>
</div>
<?php } ?>
<table class="guild table">
	<tr>
		<th>
			<span class="guild-name"><?=$guild->name?></span><br />
			Level <span class="epic-font"><?=$guild->level?></span> Guild, <span class="epic-font"><?=count($guildMembers) ?></span> <? echo (count($guildMembers) == 1 ? "member" : "members") ?>. <a href="<?=base_url('guild/main/members')?>" class="btn btn-mini">View members</a> <a href="<?=base_url('guild/manage')?>" class="btn btn-mini">Edit</a><br />
			<?=$this->characters->showResourceBar(3, $guild->xp, $guild->xp_needed ); ?>
			Leader: <a href="<?=base_url('character/view/id/'.$guild->leader.'/')?>" style="color:<?=$leader_data['color']?>;"><span class="epic-font"><?=$leader_data['level']?></span> <?=$leader_data['username']?></a><br />
			Money in Guild Bank: <?=$this->core->showMoney($guild->BankMoney)?> 
			<? if($has_access_to_w) { ?>
				<a class="btn" data-toggle="modal" href="#w"><span class="small">Withdraw</span></a> 
				<?=$this->core->displayModal( "<h1>Withdraw</h1>", "<center>Total money in Guild Bank: ".$this->core->showMoney($guild->BankMoney)."<br />Your Money: ".$player_data->money."<br />".form_open('guild/main/withdraw')."<div class=\"input-append\">".form_input($withdraw)."".form_submit($submit_w)."</div>".form_close()."</center>", "", "w") ?>
			<? }else{ ?>
				<a class="btn"><span class="blocked small">Withdraw</span></a> 
			<? } ?>
			<a class="btn" data-toggle="modal" href="#d"><span class="small">Deposit</span></a>
			<?=$this->core->displayModal( "<h1>Deposit</h1>", "<center>Total money in Guild Bank: ".$this->core->showMoney($guild->BankMoney)."<br />Your Money: ".$player_data->money."<br />".form_open('guild/main/deposit')."<div class=\"input-append\">".form_input($deposit)."".form_submit($submit_d)."</div>".form_close()."</center>", "", "d") ?>
			<a class="btn" data-toggle="modal" href="#log"><span class="small">View Log</span></a><br />
			Message of the Day: <small><?=$guild->motd ?></small>
		</th>
	</tr>
	<tr>
		<td class="description">
			<div class="well"><?=nl2br($guild->description) ?></div>
		</td>
	</tr>
	<tr>
		<td>Website: <?=($guild->description ? "<a href=\"".$guild->website."\">".$guild->website."</a>" : "-") ?></td>
	</tr>
</table>
<?=$guild_log ?>