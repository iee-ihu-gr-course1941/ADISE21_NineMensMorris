//var me={token:null,color:null};
var me ={};
var game_status={};
var board={};
//var last_update=new Date().getTime();
//var timer=null;

$(function () {
	draw_empty_board();
	fill_board();

    $('#nmm_login').click( login_to_game);
	$('#nmm_reset').click( reset_board);
	$('#do_move').click( do_move);
	$('#move_div').hide();

	game_status_update();
    $('#the_move_src').change( update_moves_selector);
	$('#do_move2').click( do_move2);
	//$('#nmm_login').click( login_to_game);
	//$('#nmm_reset').click( reset_board);
	
});

function draw_empty_board(p) {
	
	if(p!='B') {p='W';}
	var draw_init = {
		'W': {i1:7,i2:0,istep:-1,j1:1,j2:8,jstep:1},
		'B': {i1:1,i2:8,istep:1, j1:7,j2:0,jstep:-1}
	};
	var s=draw_init[p];
	var t='<table id="nmm_table">';
	for(var i=s.i1;i!=s.i2;i+=s.istep) {
		t += '<tr>';
		for(var j=s.j1;j!=s.j2;j+=s.jstep) {
			t += '<td class="nmm_square" id="square_'+j+'_'+i+'">' + j +','+i+'</td>'; 
		}
		t+='</tr>';
	}
	t+='</table>';
	
	$('#nmm_board').html(t);
	$('.nmm_square').click(click_on_piece);
}

function fill_board() {
	$.ajax({url: "NineMenMorris.php/board/", 
	headers: {"X-Token": me.token},
	//dataType: "json",
	//contentType: 'application/json',
	//data: JSON.stringify( {token: me.token}),
	success: fill_board_by_data });
	//method: 'get', success: fill_board_by_data });
	
}

function reset_board() {
	$.ajax({url: "ninemenmorris.php/board/", headers: {"X-Token": me.token}, method: 'POST',  success: fill_board_by_data });
	$('#move_div').hide();
	$('#game_initializer').show(2000);
}

function fill_board_by_data(data) {
	board=data;
	for(var i=0;i<data.length;i++) {
		var o = data[i];
		var id = '#square_'+ o.X + '_' + o.Y;
		//var c = (o.piece!=null)?o.piece_color + o.piece:'';
		//var im = (o.piece!=null)?'<img class="piece" src="images/'+c+'.png">':'';
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
	$.ajax({url: "NineMenMorris.php/status/", success: update_status,headers: {"X-Token": me.token} });
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
			//fill_board();
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
	
	
	function do_move() {
		var s = $('#the_move').val();
		
		var a = s.trim().split(/[ ]+/);
		if(a.length!=4) {
			alert('Must give 4 numbers');
			return;
		}
		$.ajax({url: "chess.php/board/piece/"+a[0]+'/'+a[1], 
				method: 'PUT',
				dataType: "json",
				contentType: 'application/json',
				data: JSON.stringify( {x: a[2], y: a[3]}),
				headers: {"X-Token": me.token},
				success: move_result,
				error: login_error});
		
	}
	
	function move_result(data){
		fill_board_by_data(data);
		$('#move_div').hide(1000);
	}
	
	function update_moves_selector() {
		$('.chess_square').removeClass('pmove').removeClass('tomove');
		var s = $('#the_move_src').val();
		var a = s.trim().split(/[ ]+/);
		$('#the_move_dest').html('');
		if(a.length!=2) {
			return;
		}
		var id = '#square_'+ a[0]+'_'+a[1];
		$(id).addClass('tomove');
		for(i=0;i<board.length;i++) {
			if(board[i].x==a[0] && board[i].y==a[1]) {
				for(m=0;m<board[i].moves.length;m++) {
					$('#the_move_dest').append('<option value="'+board[i].moves[m].x+' '+board[i].moves[m].y+'">'+board[i].moves[m].x+' '+board[i].moves[m].y+'</option>');
					var id = '#square_'+ board[i].moves[m].x +'_' + board[i].moves[m].y;
					$(id).addClass('pmove');
				}
				
			}
		}
	}
	
	function do_move2() {
		$('#the_move').val($('#the_move_src').val() +' ' + $('#the_move_dest').val());
		do_move();
		$('.chess_square').removeClass('pmove').removeClass('tomove');
	}
	
	function click_on_piece(e) {
		var o=e.target;
		if(o.tagName!='TD') {o=o.parentNode;}
		if(o.tagName!='TD') {return;}
		
		var id=o.id;
		var a=id.split(/_/);
		$('#the_move_src').val(a[1]+' ' +a[2]);
		update_moves_selector();
	}


}