<div class="row-fluid">
	<div class="offset1 span10">
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
</div>
<?=$modals ?>