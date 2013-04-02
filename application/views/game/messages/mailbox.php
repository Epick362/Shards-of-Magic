<a class="ui-button" style="float: right; margin-right: 40px;" href="<?=base_url('messages/write'); ?>">
	<span>Write a Message</span>
</a>
<table class="ui-table default" width="90%">
	<thead>
		<tr>
			<th colspan="5">
				<?=$info ?> 
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