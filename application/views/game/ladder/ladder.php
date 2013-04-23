<div class="row-fluid">
	<div class="offset1 span10">
		<table class="default table table-striped table-bordered">
			<tr>
				<td colspan="6">
					<?=$info ?> 
					<span class="pull-right">
						<?=$this->pagination->create_links(); ?>
					</span>
				</td>
			</tr>
			<tr>
				<td>Name</td>
				<td>Level</td>
				<td>Class</td>
				<td>Guild</td>
				<td>Location</td>
				<td>Actions</td>
			</tr>
			<?=$content ?>
		</table>
	</div>
</div>