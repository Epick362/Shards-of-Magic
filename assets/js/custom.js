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
	  	refreshHealthBar(".character-health", data);
	  	refreshManaBar(".character-mana", data);
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

	$(element).each(function() {
		var bar = $(this).find('.bar');
		var span = $(this).find('span')
	    bar.css('width', health_perc + '%');
	    span.html(data.health+' / '+data.health_max);
	});
}

function refreshManaBar(element, data) {
	data.mana = Math.round(data.mana);
	mana_perc = data.mana * 100 / data.mana_max;
	if (mana_perc > 100) {
		mana_perc = 100;
	}

	$(element).each(function() {
		var bar = $(this).find('.bar');
		var span = $(this).find('span')
	    bar.css('width', mana_perc + '%');
	    span.html(data.mana+' / '+data.mana_max);
    });
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

function copyText(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select();
    } else if (window.getSelection()) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
    }
}