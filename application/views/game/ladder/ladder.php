<div class="row-fluid">
	<div class="offset1 span10">
		<table class="default table table-striped table-bordered">
			<thead>
				<tr>
					<th colspan="6">
						<?=$info ?> 
						<span class="text-right">
							<?=$this->pagination->create_links(); ?>
						</span>
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
	</div>
</div>