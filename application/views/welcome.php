<?=form_open('welcome/select') ?>
<?
	$options = array();
	foreach($characters as $character) {
		$options[$character->cid] = $character->name;
	}
?>
<?=form_dropdown('dropdown', $options)?>
<?=form_submit(array('name' => 'submit', 'class' => 'btn btn-primary'), 'Submit')?>
<?=form_close() ?>