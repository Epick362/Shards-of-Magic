<?
	if(validation_errors()) {
?>
		<div class="alert alert-error"><?=validation_errors();?></div>
<?
	}
?>

<div class="row-fluid">
	<div class="offset4 span4">
		<?php echo form_open("auth/login", array('class' => 'form-signin'));?>
			<h2 class="form-signin-heading">Please sign in</h2>
			<?php echo form_input($identity);?>
			<?php echo form_input($password);?>
			<label class="checkbox">
				<?=form_checkbox('remember', '1', FALSE, 'id="remember"');?> Remember Me
			</label>
			<?=form_submit(array('name' => 'submit', 'value' => 'Login', 'class' => 'btn btn-large btn-primary btn-block'));?>
			<a class="pull-right" style="margin-top: 8px;" href="<?=base_url('auth/forgot_password')?>">Forgot password?</a>
		<?php echo form_close();?>
	</div>
</div>

