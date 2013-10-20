<?=form_open()?>
<div class="default">
	<div class="row-fluid">
		<div class="span12"><h2>NPC Generator</h2></div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<?=form_label('Theme', 'theme')?>
			<?=form_dropdown('theme', $themes)?>
		</div>
		<div class="span3">
			<?=form_label('Biome', 'biome')?>
			<?=form_dropdown('biome', $biomes)?>
		</div>
		<div class="span3">
			<?=form_label('Family', 'family')?>
			<?=form_dropdown('family', $families)?>
		</div>
		<div class="span3">
			<?=form_label('LevelRange', 'level')?>
			<?=form_input(array('name' => 'level', 'value' => set_value('level')))?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<?=form_submit(array('name' => 'send', 'value' => 'Generate', 'class' => 'btn btn-primary btn-block btn-large', 'style' => 'margin-top:10px'))?>
		</div>
	</div>
</div>
<?=form_close()?>
<div class="row-fluid">
	<div class="span12">
		<pre><?=print_r($npc)?></pre>
	</div>
</div>
<div class="text-right">
	<a class="btn btn-danger" href="<?=base_url('admin/choose')?>">Back</a>
</div>