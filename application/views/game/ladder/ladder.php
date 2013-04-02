<table class="ui-table default" width="80%">
	<thead>
		<tr>
			<th colspan="6">
				<?=$info ?> 
				<div class="right">
					<?=$this->pagination->create_links(); ?>
				</div>
			</th>
		</tr>
		<tr>
			<th>Name</th>
			<th>Level</th>
			<th>Class</th>
			<th>Guild</th>
			<th>Location</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?=$content ?>
	</tbody>
</table>