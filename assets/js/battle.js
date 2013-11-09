$(document).ready(function(){
	var keyBinds = [81, 87, 69, 82];
		
	document.onkeydown = function(e){
		if ($.inArray(e.which, keyBinds) != -1) { //"left" key.
			$.ajax({
			  url: 'battle/cast',
			  cache: false,
			  data: {'key': e.which, 'target': { 0: {'cid': $('#target').val(), 'guid': null}}},
			  dataType: 'json',
			  success: function(data) {
			  	$('#battle-log').append('<div>'+data+'</div>');
				$("#battle-log-container").scrollTop($("#battle-log-container")[0].scrollHeight);
			}});
		}
	}

	$('.char-radio-btn').on('click', function(e){
	    $('.char-radio-btn').removeClass("active");
	    $(this).addClass("active");
	    $('#target').val($(this).attr('id'));
	});	
});