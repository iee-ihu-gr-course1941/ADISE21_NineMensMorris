//var me={token:null,color:null};
var me ={};
var game_status={};
//var board={};
//var last_update=new Date().getTime();
//var timer=null;

$(function () {
	draw_empty_board();
	fill_board();
	game_status_update();
	$('#nmm_login').click( login_to_game);
	$('#nmm_reset').click( reset_board);
	
});

function draw_empty_board(p) {
	var t='<table id="nmm_table">';
	for(var i=7;i>0;i--) {
		t += '<tr>';
		for(var j=1;j<8;j++) {
			t += '<td class="nmm_square" id="square_'+j+'_'+i+'">' + j +','+i+'</td>'; 
		}
		t+='</tr>';
	}
	t+='</table>';
	
	$('#nmm_board').html(t);
}

function fill_board() {
	$.ajax({url: "NineMenMorris.php/board/", method: 'get', success: fill_board_by_data });
	
}

function reset_board() {
	$.ajax({url: "ninemenmorris.php/board/", headers: {"X-Token": me.token}, method: 'POST',  success: fill_board_by_data });
	$('#move_div').hide();
	$('#game_initializer').show(2000);
	document.getElementById('username').value = '';
	document.getElementById('username').select();
}

function fill_board_by_data(data,y,z) {
	for(var i=0;i<data.length;i++) {
		var o = data[i];
		var id = '#square_'+ o.X + '_' + o.Y;
		if (o.Bcolor == 'r') { 
			var div ='<div class="r_square">'; 
		} else if (o.Bcolor == 'g') {
			var div = '<div class="g_square">'; 
		}
		$(id).addClass(o.Bcolor+'_square').html(div);
		
	}
}

function login_to_game() {
	if($('#username').val()=='') {
		alert('You have to set a username');
		return;
	}
	var p_color = $('#pcolor').val();
	draw_empty_board(p_color);
	fill_board();
	
	
	$.ajax({url: "NineMenMorris.php/players/"+p_color, 
			method: 'PUT',
			dataType: "json",
			headers: {"X-Token": me.token},
			contentType: 'application/json',
			data: JSON.stringify( {username: $('#username').val(), piece_color: p_color}),
			success: login_result,
			error: login_error});
}

function login_result(data) {
	me = data[0];
	$('#game_initializer').hide();
	update_info();
	game_status_update();
}

function login_error(data,y,z,c) {
	var x = data.responseJSON;
	alert(x.errormesg);
}

function game_status_update() {
	
	//clearTimeout(timer);
	$.ajax({url: "NineMenMorris.php/status/", success: update_status });
}

function update_status(data) {
	//last_update=new Date().getTime();
	//var game_stat_old = game_status;
	game_status=data[0];
	update_info();
	//clearTimeout(timer);
	if(game_status.p_turn==me.piece_color &&  me.piece_color!=null) {
		x=0;
		// do play
		//if(game_stat_old.p_turn!=game_status.p_turn) {
		//	fill_board();
		//}
		$('#move_div').show(1000);
		setTimeout(function() { game_status_update();}, 15000);
	} else {
		// must wait for something
		$('#move_div').hide(1000);
		setTimeout(function() { game_status_update();}, 4000);
	}
 	
}

function update_info(){
	$('#game_info').html("I am Player: "+me.piece_color+", my name is "+me.username +'<br>Token='+me.token+'<br>Game state: '+game_status.status+', '+ game_status.p_turn+' must play now.');
	
	
}