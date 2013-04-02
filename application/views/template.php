<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">

		<title><?= $subtitle ?> | Shards of Magic</title>
	
		<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>" />
		<link rel="stylesheet" href="<?php echo base_url('assets/css/tooltip.css'); ?>" />
		<link rel="stylesheet" href="<?php echo base_url('assets/css/chat.css'); ?>" />
		<link rel="stylesheet" href="<?php echo base_url('assets/css/jquery.countdown.css'); ?>" />
		<?php
			if( $css_file ) {
		?>
		<link rel="stylesheet" href="<?php echo base_url('assets/css/game/'. $css_file .'.css'); ?>" />
		<?php
			}
		?>
		<link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>">

		<link href='http://fonts.googleapis.com/css?family=Metamorphous' rel='stylesheet' type='text/css'>
		<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
		<script src="<?php echo base_url('assets/js/jquery.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/js/jquery.tooltip.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/js/jquery.countdown.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/js/jquery.scrollview.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/js/bootstrap.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/js/chat.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/js/custom.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/js/modernizr-2.5.3.min.js'); ?>"></script>
	</head>
	<body>
	<script>
		$(function(){
			$(".tip").tipTip({defaultPosition: "right"});

			$('.item_tooltip').each(function(){ var self = $(this);self.tipTip({content: self.find('.tooltip').eq(0).html()}); });

			$("a.tab").click(function () {

				$(".active").removeClass("active");

				$(this).addClass("active");

				$(".content").hide();

				var content_show = $(this).attr("slide");
				$("#"+content_show).show();
				return false;
			});

			$('li').mouseover(function()
			{
				$(this).children('ul').css('display', 'block');
			});
			$('li').mouseout(function()
			{
				$(this).children('ul').css('display', 'none');
			});

			$("a.vendor-type").click(function () {

				$(".content").hide();

				var content_show = $(this).attr("id");
				$("#"+content_show).show();
				return false;
			});

			$(".slot").click(function() {
				var this_slot = $(this);
				if(this_slot.attr("id") && this_slot.attr("id") != 0) {
					var item_id = this_slot.attr("id");
					if(this_slot.attr("action") == 3) {
						jQuery.ajax( {
							url: "<?=base_url('character/buy/item/') ?>/"+item_id,
							dataType: 'json',
							success: function(data) {
								if( data.successful ) {
									$(".player-money").html(data.player_money);

									if( $("#trade-log span").attr("id") == item_id ) {
										data.count += 1;
									}
									// UNFINISHED
									$("#trade-log").html("You bought "+data.count+"x <span id='"+item_id+"' class='q"+data.quality+"'>["+data.name+"]</span><br />");
								}else{
									$("#trade-log").html("<span style='color:red;'>Not enough money!</span>");
								}
							}
						});
					}else if(this_slot.attr("action") == 4){
						jQuery.ajax( {
							url: "<?=base_url('character/sell/item/') ?>/"+item_id,
							dataType: 'json',
							success: function(data) {
								if( data.successful ) {
									$(".player-money").html(data.player_money);

									if( $("#trade-log span").attr("id") == item_id ) {
										data.count += 1;
									}
									// UNFINISHED
									$("#trade-log").html("You sold "+data.count+"x <span data-count="+data.count+" id='"+item_id+"' class='q"+data.quality+"'>["+data.name+"]</span><br />");
	
									$("#inventory").html(data.inv);
								}else{
									$("#trade-log").html("<span style='color:red;'>You don't own that item!</span>");
								}
							}
						});
					}
				}
			});
			$("#map").scrollview({
				grab:"https://mail.google.com/mail/images/2/openhand.cur",
				grabbing:"https://mail.google.com/mail/images/2/closedhand.cur"
			});

			$("#checkbox").click(function() {
			    if($(this).is(":checked")) {
			    	$("tr.row").each(function(){
			    		if(!$(this).attr("data-online")) {
			    			$(this).hide();
			    		}
			    	});
			    }else{
			    	$("tr.row").each(function(){
			    		$(this).show();
			    	});			    	
			    }
			});
		});
	</script>
	<?php
	
		$username = $this->tank_auth->get_username();

		//Logged in
		if (logged_in()) {
	?>
		<div class="chatbox" style="bottom: 0px; right: 20px; display: block;">
			<div class="chatboxhead">
				Online friends
			</div>
			<div class="chatboxfriends">
				<?php
					foreach($chat_data as $chat) {
						echo $chat;
					}
				?>
			</div>
		</div>
	<?php
		}
	?>
		<div id="container">
			<header>
				<!-- Header content -->
				<?php 
					//Logged in

					if (logged_in()) {
				?>
						<table style="position:absolute; right:45px; top:10px;" class="default">
							<tbody>
								<tr>
									<td width="100%">
										<span class="character-info-name">
											<?= $this->core->getClassIcon($player_data->class) ?>
											<?= $player_data->username ?>
											<a style="font-size:11px;"><?= $player_data->guildData->name ?></a>
											<a href="<?php echo base_url('logout/'); ?>" class="red" style="font-size:14px; float: right;">Logout</a>
										</span>
										<span style="color: <?= $player_data->class_data['color'] ?>; font-size: 12px;">
											<strong><span class="epic-font"><?= $player_data->level ?></span></strong> 
													<?= $player_data->gender_name ?> 
													<?= $player_data->class_data['name'] ?>
										</span>
										<span class="player-money" style="font-size: 16px; float: right;">
											<?=$player_data->money ?>
										</span>
									</td>
								</tr>
								<tr>
									<td width="100%">
										<?=$this->characters->showHealthBar( $player_data->health, $player_data->health_max, 400, 22, 1 ); ?>
										<?=$this->characters->showManaBar( $player_data->mana, $player_data->mana_max, 400, 22, 1 ); ?>
										<?php if($player_data->level < 40) { ?>
											<?=$this->characters->showXpBar( $player_data->xp, $player_data->xp_needed, 400, 22, 1 ); ?>
										<?php } ?>
										<? if($player_data->authlevel) {?>
										<a href="<?php echo base_url('admin/'); ?>" style="font-size:14px;">Administration</a>
										<? } ?>
									</td>
								</tr>	
							</tbody>	
						</table>
				<?php
					}else{
				?>
					<div class="links">
						<a class="ui-button" href="<?=base_url('login'); ?>">
							<span>Login</span>
						</a>
						<a class="ui-button" href="<?=base_url('register'); ?>">
							<span>Register</span>
						</a>
					</div>
				<?php
					}
				?>
				<?php 
					//Logged in

					if (logged_in()) {
				?>
				<div class="message-conatainer">
					<center><?=$flash_data ?></center>
				</div>
				<?php
					}
				?>
			</header>
				<?php
					if (logged_in()) {
				?>
			<div id="menu">			
				<ul class="menu">
					<li>
						<a href="<?php echo base_url('character/'); ?>">Character</a>
					</li>

					<li>
						<a href="<?php echo base_url('zone/'); ?>">Zone</a>
					</li>

					<li>
						<a href="<?php echo base_url('world/'); ?>">Map</a>
					</li>

					<li>
						<a href="<?php echo base_url('quests/'); ?>">Quests</a>
					</li>

					<li>
						<a href="<?php echo base_url('guild/'); ?>">Guild</a>
					</li>

					<li>
						<a href="<?php echo base_url('messages/'); ?>">
							Mailbox
							<?php
							if( $this->core->countNewMessages( $player_data->user_id ) > 0 ) {
								echo "<span style=\"color: #FF4400;\">(".$this->core->countNewMessages( $player_data->user_id ).")</span>";
							}
							?>
						</a>
					</li>

					<li>
						<a href="<?php echo base_url('ladder/'); ?>">Ladder</a>
					</li>
				</ul>
			</div>
				<?php
					}else{
				?>
			<div id="menu">			
				<ul class="menu">
					<li>
						<a href="<?php echo base_url(); ?>">Home</a>
					</li>

					<li>
						<a href="#">Game Info</a>
						<ul class="sub-menu" style="display: none; ">
							<li class="first"><a href="#" title="">Classes</a></li>
							<li><a href="#" title="">Items</a></li>
							<li class="last"><a href="#" title="">NPCs</a></li>
						</ul>
					</li>

					<li>
						<a href="#">Ladders</a>
						<ul class="sub-menu" style="display: none; ">
							<li class="first"><a href="#" title="">2v2</a></li>
							<li><a href="#" title="">3v3</a></li>
							<li class="last"><a href="#" title="">5v5</a></li>
						</ul>
					</li>

					<li>
						<a href="#">Forums</a>
					</li>

					<li>
						<a href="#">Guides</a>
					</li>
				
					<li>
						<a href="#">Support</a>
					</li>

					<li>
						<a href="#">Media</a>
					</li>				
				</ul>
			</div>
				<?php
					}
				?>
			<div id="content">
				<!-- Content -->
					<?= $contents ?>
					<br />
			</div>

		</div>
			<div id="footer">
				<div id="footer-text">
					<small>
						Powered by HTML5, jQuery and CodeIgniter
						<br />
						Version 0.1 Alpha
						<br />
						Shards of Magic &copy; 2011
						<br />
						<?php echo $this->benchmark->elapsed_time();?> seconds. 
						<?php echo $this->db->total_queries();?> queries.
					</small>
				</div>
			</div>
	</body>
</html>








