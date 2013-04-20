var minHeartbeat = 3000;
var idleHeartbeat = 30000;

$(document).ready(function(){
	Heartbeat();
});

function Heartbeat() {
	$.ajax({
	  url: "data/regenerate",
	  cache: false,
	  dataType: "json",
	  success: function(data) {
	  	refreshHealthBar("#character-health", data);
	  	refreshManaBar("#character-mana", data);
	  	determineNextHeartbeat(data);
	}});
}

function determineNextHeartbeat(data) {
	if(data.health < data.health_max || data.mana < data.mana_max) {
		setTimeout('Heartbeat();', minHeartbeat);			
	}else{
		setTimeout('Heartbeat();', idleHeartbeat);
	}
}

function refreshHealthBar(element, data) {
	data.health = Math.round(data.health);
	health_perc = data.health * 100 / data.health_max;
	if (health_perc > 100) {
		health_perc = 100;
	}

    $(element).find('.bar').css('width', health_perc + '%');
    $(element).find('span').html(data.health+' / '+data.health_max);
}

function refreshManaBar(element, data) {
	data.mana = Math.round(data.mana);
	mana_perc = data.mana * 100 / data.mana_max;
	if (mana_perc > 100) {
		mana_perc = 100;
	}

    $(element).find('.bar').css('width', mana_perc + '%');
    $(element).find('span').html(data.mana+' / '+data.mana_max);
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