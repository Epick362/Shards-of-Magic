<div class="row-fluid">
	<div class="span3 well well-small evenBoxes">
		<div id="1" class="char-radio-btn btn btn-inverse">
			<div class="select-icon tip-left" title="Rogue"><?= $this->core->getClassIcon($player_data->class) ?></div>
			<?=$player_data->name?><br />
			Level <?=$player_data->level?>
			<div style="clear:both">
				<?=$this->characters->showResourceBar(1, $player_data->health, $player_data->health_max, 1)?>
				<?=$this->characters->showResourceBar(2, $player_data->mana, $player_data->mana_max, 1)?>
			</div>
		</div>
		<div id="2" class="char-radio-btn btn btn-inverse">
			<div class="select-icon tip-left" title="Rogue"><?= $this->core->getClassIcon(3) ?></div>
			Carl<br />
			Level 17
			<div style="clear:both"><?=$this->characters->showResourceBar(1, 180, 360)?><?=$this->characters->showResourceBar(2, 150, 400)?></div>
		</div>
	</div>
	<div class="span6">
		<div class="well well-small" id="battle-log-container" style="height:200px; overflow-y:auto;">
			<div id="battle-log"></div>
		</div>

		<div class="row-fluid">
			<div class="span2">
				<?=$this->spell->Spell(1, 1)?>
			</div>
			<div class="span2">
				<?=$this->spell->Spell(1, 2)?>
			</div>
			<div class="span2">
				<?=$this->spell->Spell(1, 3)?>
			</div>
			<div class="span2">
				<?=$this->spell->Spell(1, 4)?>
			</div>

			<div class="offset2 span2">
				<?=$this->spell->Spell(1, 4)?>
			</div>
		</div>
	</div>
	<div class="span3 well well-small evenBoxes">
		<div id="3" class="char-radio-btn btn btn-danger">
			<div class="select-icon tip-left" title="Rogue"><?= $this->core->getClassIcon(4) ?></div>
			Joe<br />
			Level 40
			<div style="clear:both"><?=$this->characters->showResourceBar(1, 3000, 4000)?><?=$this->characters->showResourceBar(2, 150, 400)?></div>
		</div>	
	</div>
</div>
	
<input type="hidden" name="target" id="target" value="<?=$player_data->cid?>">
