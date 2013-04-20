<div class="row-fluid">
	<div class="offset1 span10">
		<table class="default table table-striped table-bordered">
			<thead>
				<tr>
					<th colspan="4"><h2>Manage guild <?=$guild->name?></h2></th>
				</tr>
			</thead>
			<tr class="row" height="70" style="text-align:center;">
				<td width="25%">&laquo;<a href="<?=base_url('guild/manage/members')?>">Edit Members</a>&raquo;</td>
				<td width="25%">&laquo;<a>Edit Ranks</a>&raquo;</td>
				<td width="25%">&laquo;<a>Edit Guild Description</a>&raquo;</td>
				<td width="25%">&laquo;<a>Add Members</a>&raquo;</td>
			</tr>
			<tr class="header">
				<td colspan="4"><h3>Other actions</h3></td>
			</tr>
			<tr class="row" height="70" style="text-align:center;">
				<td colspan="2" width="50%">&laquo;<a>Resign/Take Over</a>&raquo;</td>
				<td colspan="2" width="50%">&laquo;<a>Disband</a>&raquo;</td>
			</tr>
		</table>
	</div>
</div>