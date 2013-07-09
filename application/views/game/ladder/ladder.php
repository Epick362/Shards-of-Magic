<div class="row-fluid">
	<div class="well">
		<?=$this->pagination->create_links(); ?>
	</div>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td>Name</td>
				<td>Level</td>
				<td>Class</td>
				<td>Guild</td>
				<td>Location</td>
				<td>Actions</td>
			</tr>
		</thead>
		<tbody>
			<?=$content ?>	
		</tbody>
	</table>
</div>