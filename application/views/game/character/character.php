<div class="row-fluid">
	<div class="offset1 span10">
		<table class="table">
<? if($inspect) { ?>
			<tr>
				<td width="100%">
					<span class="character-info-name">
						<div class="icon-frame frame32">
							<img src="<?php echo base_url('assets/images/classes/'.$character->classData['image'].''); ?>" style="vertical-align:top;"/>
						</div>
						<?= $character->name ?>
						<a style="font-size:11px;"><?= $character->guildData->name ?></a>
					</span>
					<span style="color: <?= $character->classData['color'] ?>; font-size: 12px;">
						<strong><span class="epic-font"><?= $character->level ?></span></strong> 
								<?= $character->gender_name ?> 
								<?= $character->classData['name'] ?>
					</span>
				</td>
			</tr>
			<tr>
				<td width="100%">
					<?= $this->characters->showResourceBar(1, $character->health, $character->health_max ); ?>
					<?= $this->characters->showResourceBar(2, $character->mana, $character->mana_max ); ?>
					<? if($character->level < 40) {
						$this->characters->showResourceBar(3, $character->xp, $character->xp_needed );
					} ?>
				</td>
			</tr>				
<? } ?>
			<tr>
				<td>
					<div class="summary-inventory default">
						<div class="char-preview"></div>
						<div class="slot mainhand">
							<?=$character->equip[1]['image'] ?>
						</div>
						<div class="slot offhand">
							<?=$character->equip[2]['image'] ?>
						</div>
						<div class="slot head">
							<?=$character->equip[3]['image'] ?>
						</div>
						<div class="slot shoulders">
							<?=$character->equip[4]['image'] ?>
						</div>
						<div class="slot cloak">
							<?=$character->equip[5]['image'] ?>
						</div>
						<div class="slot chest">
							<?=$character->equip[6]['image'] ?>
						</div>
						<div class="slot hands">
							<?=$character->equip[7]['image'] ?>
						</div>
						<div class="slot waist">
							<?=$character->equip[8]['image'] ?>
						</div>
						<div class="slot pants">
							<?=$character->equip[9]['image'] ?>
						</div>
						<div class="slot boots">
							<?=$character->equip[10]['image'] ?>
						</div>
						<div class="slot amulet">
							<?=$character->equip[11]['image'] ?>
						</div>
					</div>
				</td>
			</tr>
<? if(!$inspect) { ?>
			<tr>
				<td>
					<div class="default"><?=$character->inv ?></div>
				</td>
			</tr>
<? } ?>
		</table>
	</div>
</div>

