<table class="ui-table default" width="70%">
	<thead>
		<tr>
			<th colspan="7">Members of <?=$guild->name ?><span class="right">Show Online only <input type="checkbox" id="checkbox" /></span></th>
		</tr>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Class</th>
			<th>Level</th>
			<th>Rank</th>
			<th width="20%">Last Online</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
	<? 
		$i = 1;
		$uid = $this->tank_auth->get_user_id();
		foreach($guildMembers as $member) {
			$class_data = $this->core->getClassData($member->class);
			if($online_data[$member->user_id]['online'] == 1) {
				echo "<tr class=\"row\" data-online=\"1\">";
			}else{
				echo "<tr class=\"row\">";
			}
				echo "<td>".$i."</td>";
				echo "<td>";
				echo "<a href=\"".base_url('character/view/id/'.$member->user_id.'')."\">";
				echo "<div style=\"color:".$class_data['color']."\">";
				echo "<b><span style=\"font-size:14px;\">".$member->username."</span></b>";
				echo "</div>";
				echo "</a>";
				echo "</td>";
				echo "<td><div class=\"icon-frame frame32 tip\" title=\"".$class_data['name']."\"><img src=\"".base_url('assets/images/classes/'.$class_data['image'].'')."\" style=\"vertical-align:top;\"/></div></td>";
				echo "<td><strong><span class=\"epic-font\">".$member->level."</span></strong></td>";
				if($member->user_id == $guild->leader) {
					echo "<td>Leader</td>";
				}else{
					echo "<td>".$this->guilds->rankToName($member->rank, $member->user_id)."</td>";
				}
				if($online_data[$member->user_id]['online'] == 1) {
					echo "<td><span style=\"color:#00FF00;\">Online</span></td>";
				}else{
					echo "<td><span style=\"color:#FF4400;\">".$online_data[$member->user_id]['ago']." ago</span></td>";
				}
				echo "<td>";
					if($member->user_id != $uid) {
						echo "<a href=\"".base_url('messages/write/to/'.$member->username.'')."\"><img src=\"".base_url('assets/images/pm.png')."\" /></a> ";
						echo "<a href=\"".base_url('guild/manage/kick/id/'.$member->user_id.'')."\" onclick=\"return confirm('Are you sure you want to kick this member?')\"><img src=\"".base_url('assets/images/kick.png')."\" /></a> ";
					}
				echo "</td>";
			echo "</tr>";
			$i++;
		}
	?>
	</tbody>
</table>