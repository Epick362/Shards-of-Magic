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
		<? foreach($characters_ladder as $character) { ?>
			<tr>
				<td><a href="<?=base_url('character/index/'.$character->name)?>" style="color:<?=$character->class_data['color']?>"><?=$character->name?></a></td>
				<td><span class="epic-font"><?=$character->level?></span></td>
				<td><?=$this->core->getClassIcon($character->class)?></td>
				<td><a href="<?=base_url('guild/main/view/id/'.$character->guild_data->id)?>"><?=$character->guild_data->name?></a></td>
				<td><?=$character->world_data->zone->name?></td>
				<td width="25%">
					<a class="btn" href="<?=base_url('world/travel/map/'.$character->map.'/zone/'.$character->zone)?>">Follow</a> 
					<a class="btn" href="<?=base_url('messages/write/to/'.$character->name)?>"><i class="icon-envelope"></i> PM</a>
					<a class="btn" href="<?=base_url('character/index/'.$character->name)?>"><i class="icon-user"></i> Inspect</a>
				</td>
			</tr>
		<? } ?>
		</tbody>
	</table>
</div>