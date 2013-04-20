<table class="summary-character-table default">
	<tbody>
		<tr>
			<td width="100%">
				<table class="default" width="98.5%">
					<tbody>
						<tr>
							<td width="100%">
								<span class="character-info-name">
									<div class="icon-frame frame32">
										<img src="<?php echo base_url('assets/images/classes/'.$class_data['image'].''); ?>" style="vertical-align:top;"/>
									</div>
									<?= $username ?>
									<a style="font-size:11px;"><?= $guildData->name ?></a>
								</span>
								<span style="color: <?= $class_data['color'] ?>; font-size: 12px;">
									<strong><span class="epic-font"><?= $level ?></span></strong> 
											<?= $gender_name ?> 
											<?= $class_data['name'] ?>
								</span>
							</td>
						</tr>
						<tr>
							<td width="100%">
								<?= $this->characters->showResourceBar(1, $health, $health_max ); ?>
								<?= $this->characters->showResourceBar(2, $mana, $mana_max ); ?>
								<?= $this->characters->showResourceBar(3, $xp, $xp_needed ); ?>
							</td>
						</tr>	
					</tbody>	
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div class="summary-inventory default">
					<div class="slot mainhand">
						<?=$equip[1]['image'] ?>
					</div>
					<div class="slot offhand">
						<?=$equip[2]['image'] ?>
					</div>
					<div class="slot head">
						<?=$equip[3]['image'] ?>
					</div>
					<div class="slot shoulders">
						<?=$equip[4]['image'] ?>
					</div>
					<div class="slot cloak">
						<?=$equip[5]['image'] ?>
					</div>
					<div class="slot chest">
						<?=$equip[6]['image'] ?>
					</div>
					<div class="slot hands">
						<?=$equip[7]['image'] ?>
					</div>
					<div class="slot waist">
						<?=$equip[8]['image'] ?>
					</div>
					<div class="slot pants">
						<?=$equip[9]['image'] ?>
					</div>
					<div class="slot boots">
						<?=$equip[10]['image'] ?>
					</div>
					<div class="slot amulet">
						<?=$equip[11]['image'] ?>
					</div>
				</div>
			</td>
		</tr>
	</tbody>
</table>


