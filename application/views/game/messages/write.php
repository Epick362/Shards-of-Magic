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
<table class="error-table mailbox-table default" width="90%">
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
				<?php echo form_submit('send', 'Send'); ?> <?php echo form_close(); ?>
			</td>
		</tr>
	</tbody>
</table>