<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?=$title?> · Shards of Magic</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- CSS -->
		<link href="<?=base_url('assets/css/bootstrap.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/css/stylesheet.css')?>" rel="stylesheet">
		<link href="<?=base_url('assets/css/tooltip.css')?>" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Metamorphous|Open+Sans|Montez|Flamenco|Cinzel+Decorative' rel='stylesheet' type='text/css'>
		<? if(!empty($css)) echo '<link href="'.base_url('assets/css/game/'.$css.'.css').'" rel="stylesheet">';?>
	</head>
	<body>
		<div id="wrap">
			<div class="container">
				<div class="page-header">
					<h1 class="shards-of-magic"><a href="<?=base_url('character/')?>"><img src="<?=base_url('assets/images/mini.png')?>" alt="Shards of Magic"> Shards of Magic</a></h1>
						<div class="navbar">
							<div class="navbar-inner">
								<div class="container">
									<ul class="nav">
										<?=$navigation?>
									</ul>
								</div>
							</div>
						</div>
				</div>
				<div class="page-content">
					<?=$contents?>
				</div>
			</div>

			<div id="push"></div>
		</div>

		<div id="footer">
			<div class="container">
				<p class="muted credit">Page rendered in {elapsed_time} seconds. <?=$this->db->total_queries();?> total queries</p>
			</div>
		</div>

		<!-- Le javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="<?=base_url('assets/js/jquery.js')?>"></script>
		<script src="<?=base_url('assets/js/bootstrap.js')?>"></script>
		<script src="<?=base_url('assets/js/jquery.tooltip.js')?>"></script>
		<script>
			$(function(){
				$('#content_2, #content_3').hide();

				$(".tip").tipTip({defaultPosition: "right"});

				$('div.item').each(function(){ var self = $(this);self.tipTip({content: self.find('.tooltip').eq(0).html()}); });

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

				$("#checkbox").click(function() {
				    if($(this).is(":checked")) {
				    	$("#members tr").slice(2).each(function(){
				    		if(!$(this).attr("data-online")) {
				    			$(this).hide();
				    		}
				    	});
				    }else{
				    	$("#members tr").slice(2).each(function(){
				    		$(this).show();
				    	});			    	
				    }
				});
			});
		</script>
	</body>
</html>