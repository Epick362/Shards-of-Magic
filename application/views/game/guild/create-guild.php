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
$submit = array(
	'name'  => 'create',
	'value' => 'Create',
	'class' => 'btn btn-block btn-primary btn-large'
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
<div class="row-fluid">
	<div class="offset2 span8">
		<div class="alert alert-error">
			<strong>Oops! Error(s) occured:</strong> <?php echo validation_errors(); ?>
		</div>
	</div>
</div>
<?php } ?>
<div class="row-fluid">
	<div class="offset2 span8">
		<table class="default table table-striped table-bordered">
			<thead>
				<tr>
					<th><h1>Create a guild</h1><small>Or wait until someone sends you invite into a guild</small></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo form_label('Guild Name', $name['id']); ?><?php echo form_input($name); ?></td>
				</tr>
				<tr>
					<td>
						<?php echo form_label('Guild Information', $textarea['id']); ?><?php echo form_textarea($textarea); ?><br />
						<small><div id="charlimitinfo"></div></small>
					</td>
				</tr>
				<tr>
					<td>
						<h3><a class="red">Warning!</a> Creating a guild will cost you <?=$this->core->showMoney(200000)?></h3>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo form_submit($submit); ?> <?php echo form_close(); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
