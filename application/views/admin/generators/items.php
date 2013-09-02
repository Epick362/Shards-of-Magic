<?=form_open()?>
<div class="default">
		<h2>Items</h2>
		<?=form_submit(array('name' => 'send', 'value' => 'Generate', 'class' => 'btn btn-primary'))?>
</div>
<?=form_close()?>
<div class="row-fluid" style="height:400px;overflow:scroll;">
	<div class="span12">
		<?=$content?>
	</div>
</div>
<div class="default">
	<?=$queries?>
</div>