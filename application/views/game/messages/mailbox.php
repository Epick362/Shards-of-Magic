<div class="row-fluid">
	<div class="offset1 span10">
		<table class="default table table-striped table-bordered">
			<tr>
				<td colspan="5">
					<?=$info ?> <a class="btn btn-mini btn-primary" href="<?=base_url('messages/write'); ?>">Write a Message</a>
					<div class="pull-right">
						<?=$this->pagination->create_links(); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td widtd="15%">#</td>
				<td widtd="20%">From</td>
				<td widtd="20%">Subject</td>
				<td widtd="40%">Message</td>
				<td widtd="5%">Delete</td>
			</tr>
			<?=$content ?>
		</table>
	</div>
</div>