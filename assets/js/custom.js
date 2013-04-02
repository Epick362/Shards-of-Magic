var minHeartbeat = 5000;
var HeartbeatTime = minHeartbeat;

$(document).ready(function(){
	Heartbeat();
});

function Heartbeat() {
	$.ajax({
	  url: "data/regenerate",
	  cache: false,
	  dataType: "json",
	  success: function(data) {

	  	refreshHealthBar("#character", data);
	  	refreshManaBar("#character", data);

		setTimeout('Heartbeat();', HeartbeatTime);
	}});
}

function refreshHealthBar(element, data) {
	data.health = Math.round(data.health);
	health_perc = data.health * 100 / data.health_max;
	if (health_perc > 100) {
		health_perc = 100;
	}

    $(element).find('#health.resource-value').css('width', health_perc + '%');
    $(element).find('#health.resource-text').html('<strong>'+data.health+' / '+data.health_max+'</strong>');
}

function refreshManaBar(element, data) {
	data.mana = Math.round(data.mana);
	mana_perc = data.mana * 100 / data.mana_max;
	if (mana_perc > 100) {
		mana_perc = 100;
	}

    $(element).find('#mana.resource-value').css('width', mana_perc + '%');
    $(element).find('#mana.resource-text').html('<strong>'+data.mana+' / '+data.mana_max+'</strong>');
}

function limitChars(textid, limit, infodiv)
{
	var text = $('#'+textid).val(); 
	var textlength = text.length;
	if(textlength > limit) {
		$('#' + infodiv).html('You cannot write more then '+limit+' characters!');
		$('#' + textid).val(text.substr(0,limit));
		return false;
	}else{
		$('#' + infodiv).html(''+textlength+'/'+limit+' characters used');
		return true;
	}
}