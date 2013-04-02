<script>
$(function(){
	var date = new Date(<?=$end_time ?>*1000);

	$('#travelCountdown').countdown({until: date, expiryUrl: '<?=base_url() ?>world/'});
 }); 
</script>
<div class="summary-world summary-travel default">
	<div class="countdown">
		<div class="text">You are travelling to <?=$destination->zone->name ?> in <?=$destination->map->name ?>!</div>
		<span id="travelCountdown"></span>
	</div>
	<div style="clear:both;"></div>
</div>