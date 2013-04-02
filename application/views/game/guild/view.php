<table class="guild default" width="70%">
	<tr>
		<th>
			<span class="guild-name"><?=$guild->name?></span><br />
			Level <span class="epic-font">7</span> Guild, <span class="epic-font"><?=count($guildMembers) ?></span> member(s).<br />
			Leader: <a href="<?=base_url('character/view/id/'.$guild->leader.'/')?>" style="color:<?=$leader_data['color']?>;"><span class="epic-font"><?=$leader_data['level']?></span> <?=$leader_data['username']?><br />
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