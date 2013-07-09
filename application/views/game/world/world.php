<div class="row-fluid">
	<div class="span8">
		<table class="world-table table table-bordered">
			<?php
				for($y = 1; $y <= 8; $y++) {
					echo "<tr>";
					for($x = 1; $x <= 7; $x++) {
						if(array_key_exists( $x, $world_data->maps )) {
							if(array_key_exists( $y, $world_data->maps[$x] )) {
								if( $world_data->maps[$x][$y]->is_city == 1 ) {
									if( $world_data->map->id == $world_data->maps[$x][$y]->id) {
										echo "<td class=\"active\">";
									}else{
										echo "<td class=\"city\">";
									}
								}else{
									if( $world_data->map->id == $world_data->maps[$x][$y]->id) {
										echo "<td class=\"active\">";
									}else{
										echo "<td class=\"land\">";
									}						
								}
								echo "<a href=\"#map".$world_data->maps[$x][$y]->id."\" data-toggle=\"modal\">".$world_data->maps[$x][$y]->name."</a></td>";
							}else{
								echo "<td class=\"sea\"></td>";
							}
						}else{
							echo "<td class=\"sea\"></td>";
						}
					}
					echo "</tr>";
				}
			?>
		</table>
	</div>
	<div class="span4">
		<div class="tabbable">
          <ul class="nav nav-tabs">
            <li><a href="#tab1" data-toggle="tab"><?=$world_data->zone->name ?></a></li>
            <li class="active"><a href="#tab2" data-toggle="tab">In This Zone</a></li>
            <li><a href="#tab3" data-toggle="tab">Quests</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane" id="tab1">
				<?=$zone_info ?>
            </div>
            <div class="tab-pane active" id="tab2">
				<?=$content ?>
            </div>
            <div class="tab-pane" id="tab3">
            	<div class="quests">
				<?php
					if(!empty($quest_data)) {
						foreach($quest_data as $quest) {
							if($quest['status'] == 1) {
								echo '<a class="quest-name">'.$quest['Title'].' (Complete)</a>';
								echo '<a class="red" style="float:right;" href="'.base_url('quests/abandon/quest/'.$quest['id'].'').'"  onclick="return confirm(\'Are you sure you want to abandon this quest?\')">✕</a>';
								echo '<div class="quest-status">';
								echo '<ul class="unstyled"><li>'.$quest['CompletedText'].'</li></ul>';
								echo '</div>';
							}else{
								echo '<a class="quest-name">'.$quest['Title'].'</a>';
								echo '<a class="red" style="float:right;" href="'.base_url('quests/abandon/quest/'.$quest['id'].'').'"  onclick="return confirm(\'Are you sure you want to abandon this quest?\')">✕</a>';
								echo '<div class="quest-status">';
								echo '<ul class="unstyled">';
								for ($i = 1; $i <= 4; $i++) {
									if(array_key_exists('ReqCreatureName'.$i, $quest)) {
										echo '<li>'.$quest['ReqCreatureName'.$i].' slain <em class="pull-right">'.$quest['ReqCreatureDone'.$i].'/'.$quest['ReqCreatureCount'.$i].'</em></li>';
									}
								}
								for ($i = 1; $i <= 4; $i++) {
									if(array_key_exists('ReqItemName'.$i, $quest)) {
										echo '<li>'.$quest['ReqItemName'.$i].' <em class="pull-right">'.$quest['ReqItemDone'.$i].'/'.$quest['ReqItemCount'.$i].'</em></li>';
									}
								}
								echo '</ul>';
								echo '</div>';
							}
						}
					}else{
						echo '<a class="quest-name">Your quest log is empty. You can pick up quests from humans in World</a>';
					}
				?>
				</div>
            </div>
          </div>
        </div>
	</div>
</div>
<?=$modals ?>