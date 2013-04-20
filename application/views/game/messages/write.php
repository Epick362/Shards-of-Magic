<?php
$recipient = array(
	'name'	=> 'recipient',
	'id'	=> 'recipient',
	'value' => set_value('recipient'),
	'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
	'size'	=> 77,
);

$uri_data = $this->uri->uri_to_assoc(3);
if(array_key_exists('to', $uri_data)) { 
	$recipient['value'] = $uri_data['to'];
}

$subject = array(
	'name'	=> 'subject',
	'id'	=> 'subject',
	'value' => set_value('subject'),
	'maxlength'	=> 64,
	'size'	=> 77,
);

$textarea = array(
	'name'	=> 'message',
	'id'	=> 'message',
	'value' => set_value('message'),
	'rows'	=> 6,
	'cols'	=> 66,
);
$submit = array(
	'name'  => 'send',
	'value' => 'Send',
	'class' => 'btn btn-block btn-primary btn-large'
	);
?>
<script>
$(function(){
	limitChars('message', 512, 'charlimitinfo');

	$('#message').keyup(function(){
		limitChars('message', 512, 'charlimitinfo');
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
	<div class="offset1 span10">
		<table class="default table table-striped table-bordered">
			<tbody>
				<tr class="row">
					<td><?php echo form_label('Recipient', $recipient['id']); ?><?php echo form_input($recipient); ?></td>
				</tr>
				<tr class="row">
					<td><?php echo form_label('Subject', $subject['id']); ?><?php echo form_input($subject); ?></td>
				</tr>
				<tr class="row">
					<td>
						<?php echo form_label('Message', $textarea['id']); ?><?php echo form_textarea($textarea); ?><br />
						<div id="charlimitinfo"></div>
					</td>
				</tr>
				<tr class="row">
					<td colspan="2">
						<?php echo form_submit($submit); ?> <?php echo form_close(); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>