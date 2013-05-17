<div class="row-fluid">
	<div class="offset1 span10">
		<table class="table">
			<tr>
				<td>
					<div class="summary-inventory default">
						<div class="char-preview"></div>
						<div class="slot mainhand">
							<?=$player_data->equip[1]['image'] ?>
						</div>
						<div class="slot offhand">
							<?=$player_data->equip[2]['image'] ?>
						</div>
						<div class="slot head">
							<?=$player_data->equip[3]['image'] ?>
						</div>
						<div class="slot shoulders">
							<?=$player_data->equip[4]['image'] ?>
						</div>
						<div class="slot cloak">
							<?=$player_data->equip[5]['image'] ?>
						</div>
						<div class="slot chest">
							<?=$player_data->equip[6]['image'] ?>
						</div>
						<div class="slot hands">
							<?=$player_data->equip[7]['image'] ?>
						</div>
						<div class="slot waist">
							<?=$player_data->equip[8]['image'] ?>
						</div>
						<div class="slot pants">
							<?=$player_data->equip[9]['image'] ?>
						</div>
						<div class="slot boots">
							<?=$player_data->equip[10]['image'] ?>
						</div>
						<div class="slot amulet">
							<?=$player_data->equip[11]['image'] ?>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div class="default"><?=$player_data->inv ?></div>
				</td>
			</tr>
		</table>
	</div>
</div>

