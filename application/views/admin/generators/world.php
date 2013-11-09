<?=form_open()?>
<div class="default">
	<div class="row-fluid">
		<div class="span12"><h2>Map Generator</h2></div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<?=form_label('X-Coordinate', 'x')?>
			<?=form_input(array('name' => 'x', 'value' => set_value('x')))?>
		</div>
		<div class="span3">
			<?=form_label('Y-Coordinate', 'y')?>
			<?=form_input(array('name' => 'y', 'value' => set_value('y')))?>
		</div>
		<div class="span3">
			<?=form_label('Level Range', 'level')?>
			<?=form_input(array('name' => 'level', 'value' => set_value('level')))?>
		</div>
		<div class="span3">
			<?=form_label('Map ID', 'mapid')?>
			<?=form_input(array('name' => 'mapid', 'value' => set_value('mapid')))?>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span10">
			<?=form_submit(array('name' => 'send', 'value' => 'Generate', 'class' => 'btn btn-primary btn-block btn-large'))?>
		</div>
		<div class="span2">
			<a class="btn btn-block btn-large" onClick="copyText('generatedContent')">Select</a>
		</div>
	</div>
</div>
<?=form_close()?>
<div class="alert alert-info">
	<b>Note:</b> Y-coordinate is the latitude. Y-Coordinate between 4-6 are non-Northern zones.
</div>
<div class="row-fluid">
	<div class="span12">
		<div id="generatedContent"><?=$sql?></div>
		<pre><?=print_r($map)?></pre>
	</div>
</div>
<div class="text-right">
	<a class="btn btn-danger" href="<?=base_url('admin/choose')?>">Back</a>
</div>