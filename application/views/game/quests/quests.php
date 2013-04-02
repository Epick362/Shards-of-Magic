<div class="quests default">
	<img src="<?php echo base_url('assets/images/questionmark.png'); ?>" style="vertical-align:top;"/>
	<div class="quest-container">
<?php
	if(!empty($quest_data)) {
		foreach($quest_data as $quest) {
			if($quest['status'] == 1) {
				echo '<a class="quest-name">'.$quest['Title'].' (Complete)</a>';
				echo '<a class="red" style="float:right;" href="'.base_url('quests/abandon/quest/'.$quest['id'].'').'"  onclick="return confirm(\'Are you sure you want to abandon this quest?\')">X</a><br />';
				echo '<div class="quest-status">';
				echo '<ul><li>'.$quest['CompletedText'].'</li></ul>';
				echo '</div>';
			}else{
				echo '<a class="quest-name">'.$quest['Title'].'</a>';
				echo '<a class="red" style="float:right;" href="'.base_url('quests/abandon/quest/'.$quest['id'].'').'"  onclick="return confirm(\'Are you sure you want to abandon this quest?\')">X</a><br />';
				echo '<div class="quest-status">';
				for ($i = 1; $i <= 4; $i++) {
					if(array_key_exists('ReqCreatureName'.$i, $quest)) {
						echo '<ul><li><strong>'.$quest['ReqCreatureName'.$i].' slain</strong> <em>'.$quest['ReqCreatureDone'.$i].'/'.$quest['ReqCreatureCount'.$i].'</em></li></ul>';
					}
				}
				for ($i = 1; $i <= 4; $i++) {
					if(array_key_exists('ReqItemName'.$i, $quest)) {
						echo '<ul><li><strong>'.$quest['ReqItemName'.$i].'</strong> <em>'.$quest['ReqItemDone'.$i].'/'.$quest['ReqItemCount'.$i].'</em></li></ul>';
					}
				}
				echo '</div>';
			}
		}
	}else{
		echo '<a class="quest-name">Your quest log is empty. You can pick up quests from humans in World</a>';
	}
?>
	</div>
	<div style="clear:both;">
</div>