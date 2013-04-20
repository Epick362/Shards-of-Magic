<div class="row-fluid">
	<div class="offset1 span10">
		<table class="table default">
				<tr>
					<td>
						<div class="summary-inventory default">
							<div class="char-preview">
							</div>
							<div class="slot mainhand">
								<?=$player_data->equip[1]['image'] ?>
							</div>
							<div class="slot offhand">
								<?=$player_data->equip[2]['image'] ?>
							</div>
							<div class="slot head">
								<?=$player_data->equip[3]['image'] ?>
							</div>
							<div class="slot shoulders">
								<?=$player_data->equip[4]['image'] ?>
							</div>
							<div class="slot cloak">
								<?=$player_data->equip[5]['image'] ?>
							</div>
							<div class="slot chest">
								<?=$player_data->equip[6]['image'] ?>
							</div>
							<div class="slot hands">
								<?=$player_data->equip[7]['image'] ?>
							</div>
							<div class="slot waist">
								<?=$player_data->equip[8]['image'] ?>
							</div>
							<div class="slot pants">
								<?=$player_data->equip[9]['image'] ?>
							</div>
							<div class="slot boots">
								<?=$player_data->equip[10]['image'] ?>
							</div>
							<div class="slot amulet">
								<?=$player_data->equip[11]['image'] ?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td style="padding: 10px;">
						<center>
							<ul class="tabs">
								<li><a href="#" slide="content_1" class="tab active">Inventory</a></li>
								<li><a href="#" slide="content_2" class="tab">Stats</a></li>
							</ul>
						</center>
					</td>
				</tr>
				<tr>
					<td>
						<div id="content_1" class="content default">
							<?=$player_data->inv ?>
						</div>
						<div id="content_2" class="content default">
							<table width="98%">
								<tbody>
									<tr>
										<td>
											<div class="summary-stats">
												<div class="summary-stats-simple">
													<div class="summary-stats-simple-base">

														<div class="summary-stats-column">
															<h4>Base</h4>
															<ul>
																<li class="">
																	<span class="name">Strength</span>
																		<span class="value bonus tip" title="<?=$player_data->base_stats['str'] ?> <div class='bonus'>+ <?=$player_data->bonus_stats['str'] ?></div>">
																			<?=$player_data->str ?>
																		</span>
																<span class="clear"><!-- --></span>
																</li>

																<li class="">
																	<span class="name">Dexterity</span>
																		<span class="value bonus tip" title="<?=$player_data->base_stats['dex'] ?> <div class='bonus'>+ <?=$player_data->bonus_stats['dex'] ?></div>">
																			<?=$player_data->dex ?>
																		</span>
																<span class="clear"><!-- --></span>
																</li>

																<li class="">
																	<span class="name">Stamina</span>
																		<span class="value bonus tip" title="<?=$player_data->base_stats['sta'] ?> <div class='bonus'>+ <?=$player_data->bonus_stats['sta'] ?></div>">
																			<?=$player_data->sta ?>
																		</span>
																<span class="clear"><!-- --></span>
																</li>

																<li class="">
																	<span class="name">Intellect</span>
																		<span class="value bonus tip" title="<?=$player_data->base_stats['int'] ?> <div class='bonus'>+ <?=$player_data->bonus_stats['int'] ?></div>">
																			<?=$player_data->int ?>
																		</span>
																<span class="clear"><!-- --></span>
																</li>

																<li class="">
																	<span class="name">Luck</span>
																		<span class="value bonus tip" title="<?=$player_data->base_stats['luc'] ?> <div class='bonus'>+ <?=$player_data->bonus_stats['luc'] ?></div>">
																			<?=$player_data->luc ?>
																		</span>
																<span class="clear"><!-- --></span>
																</li>

															</ul>
														</div>
													</div>
													<div class="summary-stats-simple-other">

														<div class="summary-stats-column">
															<h4>Other</h4>
															<ul>
																<li class="">
																	<span class="name">Damage per Second</span>
										<span class="value bonus tip" title="<?=$player_data->combat['dps_sources'] ?>">
																		<?=$player_data->combat['dps'] ?>
																	</span>
																<span class="clear"><!-- --></span>
																</li>

																<li class="">
																	<span class="name">Armor</span>
										<span class="value bonus tip" title="<?=$player_data->armor_reduction ?>% Damage reduction">
																		<?=$player_data->combat['armor'] ?>
																	</span>
																<span class="clear"><!-- --></span>
																</li>
															</ul>
														</div>
													</div>
												<div class="summary-stats-end"></div>
												</div>
											</div>
										</td>
									</tr>		
								</tbody>	
							</table>
						</div>
						<!-- =========== END OF TABLE 3 =========== -->
						</div>
					</td>
				</tr>
		</table>
	</div>
</div>

