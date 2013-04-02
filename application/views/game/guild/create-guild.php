<?php

$name = array(
	'name'	=> 'name',
	'id'	=> 'name',
	'value' => set_value('name'),
	'maxlength'	=> 16,
	'size'	=> 77,
);

$textarea = array(
	'name'	=> 'info',
	'id'	=> 'info',
	'value' => set_value('info'),
	'rows'	=> 6,
	'cols'	=> 66,
);
?>
<script>
$(function(){
	limitChars('info', 512, 'charlimitinfo');

	$('#info').keyup(function(){
		limitChars('info', 512, 'charlimitinfo');
	})
});
</script>
<?php echo form_open($this->uri->uri_string()); ?>
<?php if(validation_errors()) { ?>
<table class="error-table ui-table default" width="90%">
	<tr>
		<td class="head">
			Oops! Error(s) occured
		</td>
	</tr>
	<tr class="error">
		<td>
			<?php echo validation_errors(); ?>
		</td>
	</tr>
</table>
<?php } ?>
<table class="ui-table default">
	<thead>
		<tr>
			<th><h1>Create a guild</h1><small>Or wait until someone sends you invite into a guild</small></th>
		</tr>
	</thead>
	<tbody>
		<tr class="row">
			<td><?php echo form_label('Guild Name', $name['id']); ?><?php echo form_input($name); ?></td>
		</tr>
		<tr class="row">
			<td>
				<?php echo form_label('Guild Information', $textarea['id']); ?><?php echo form_textarea($textarea); ?><br />
				<small><div id="charlimitinfo"></div></small>
			</td>
		</tr>
		<tr class="row">
			<td>
				<h3><a class="red">Warning!</a> Creating a guild will cost you <?=$this->core->showMoney(200000)?></h3>
			</td>
		</tr>
		<tr class="row">
			<td>
				<?php echo form_submit('create', 'Create'); ?> <?php echo form_close(); ?>
			</td>
		</tr>
	</tbody>
</table>