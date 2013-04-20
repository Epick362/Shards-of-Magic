<div class="row-fluid">
	<div class="offset1 span10">
		<table class="default table table-striped table-bordered">
			<thead>
				<tr>
					<th colspan="5">
						<?=$info ?> <a class="btn btn-primary" href="<?=base_url('messages/write'); ?>">Write a Message</a>
						<div class="right">
							<?=$this->pagination->create_links(); ?>
						</div>
					</th>
				</tr>
				<tr>
					<th width="15%">#</th>
					<th width="20%">From</th>
					<th width="20%">Subject</th>
					<th width="40%">Message</th>
					<th width="5%">Delete</th>
				</tr>
			</thead>
			<tbody>
				<?=$content ?>
			</tbody>
		</table>
	</div>
</div>