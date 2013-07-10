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
	'class' => 'btn btn-primary btn-large btn-block'
	);
?>
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
	<?=form_open('messages/write', array('class' => 'form-horizontal offset3 span6 table')); ?>
		<fieldset>
			<div id="legend">
				<legend>Compose a message</legend>
			</div>

			<div class="control-group">
				<!-- Username -->
				<?php echo form_label('Recipient', $recipient['id'], array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo form_input($recipient); ?>
				</div>
			</div>

			<div class="control-group">
				<!-- Username -->
				<?php echo form_label('Subject', $subject['id'], array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo form_input($subject); ?>
				</div>
			</div>

			<div class="control-group">
				<!-- Username -->
				<?php echo form_label('Message', $textarea['id'], array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo form_textarea($textarea); ?>
					<p class="help-block"><div id="charlimitinfo"></div></p>
				</div>
			</div>

			<?php echo form_submit($submit); ?>
		</fieldset>
	<?=form_close()?>
</div>