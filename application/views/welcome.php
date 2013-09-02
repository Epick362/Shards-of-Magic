<div class="select-background default">
<?=form_open('welcome/select') ?>
	<div class="char-preview"></div>
	<div class="char-select-text epic-font">Character Select</div>
<?
	$i = 0;
	foreach($characters as $character) {
		$classData = $this->core->getClassData($character->class);
?>
	<div class="slot" style="top: <?=$i*60+20?>px; right: 20px;">
		<div id="<?=$character->cid?>" class="char-radio-btn btn <?=($i == 0 ? 'btn-warning active' : '')?>">
			<div class="select-icon tip-left" title="<?=$classData['name']?>"><?= $this->core->getClassIcon($character->class) ?></div>
			<?=$character->name?><br />
			Level <?=$character->level?>
		</div>
	</div>
<?
		if($i == 0) {
			$firstone = $character->cid;
		}
		$i++;
	}
?>
	<input type="hidden" name="selected-character" id="selected-character" value="<?=$firstone?>">

	<a class="btn char-create" href="<?=base_url('character/create')?>">Create a character</a>
	<?=form_submit(array('name' => 'submit', 'class' => 'btn btn-primary btn-submit'), 'Enter World')?>
</div>
<?=form_close() ?>