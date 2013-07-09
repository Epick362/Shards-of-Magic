<div class="row-fluid">
	<?=$this->pagination->create_links()?>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td width="15%">&nbsp;</td>
				<td width="20%">From</td>
				<td width="20%">Subject</td>
				<td width="40%">Message</td>
				<td width="5%">Delete</td>
			</tr>
		</thead>
		<tbody>
			 <?=$content ?>
		</tbody>
	</table>
	<div class="pull-right">
		<a class="btn btn-primary" href="<?=base_url('messages/write'); ?>">Write a Message</a>
	</div>
</div>