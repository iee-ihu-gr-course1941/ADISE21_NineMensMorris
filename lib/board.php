<?php 


function show_piece($x,$y) {
	global $mysqli;
	
	$sql = 'select * from board where x=? and y=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ii',$x,$y);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}

function move_piece($x,$y,$x2,$y2,$token) {
	
/*	$color1 = current_bcolor($token);
	if($color1 == 'r'){
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"cant move there."]); 
		exit;
	}	
	
	
	if($token==null || $token=='') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"token is not set."]);
		exit;
	}
*/	
	$color = current_color($token);
	/*
	if($color==null ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You are not a player of this game."]);
		exit;
	}
	$status = read_status();
	if($status['status']!='started') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Game is not in action."]);
		exit;
	}
	if($status['p_turn']!=$color) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"It is not your turn."]);
		exit;
	}
	*/
	$board=read_board();
	$n = add_valid_moves_to_piece($board,$color,$x,$y);
	if($n==0) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"This piece cannot move."]);
		exit;
	}
	foreach($board[$x][$y]['moves'] as $i=>$move) {
		if($x2==$move['x'] && $y2==$move['y']) {
			do_move($x,$y,$x2,$y2);
			exit;
		}
	}
	header("HTTP/1.1 400 Bad Request");
	print json_encode(['errormesg'=>"This move is illegal."]);
	exit;
}


function add_valid_moves_to_piece(&$board,$b,$x,$y) {
	$number_of_moves=0;
	if($board[$x][$y]['piece_color']==$b) {
		switch($board[$x][$y]['piece_color']){
			case 'W': $number_of_moves+=opawn_moves($board,$b,$x,$y);break;
			case 'B': $number_of_moves+=mpawn_moves($board,$b,$x,$y);break;
			case 'I': $number_of_moves+=ipawn_moves($board,$b,$x,$y);break;
		}
	} 
	return($number_of_moves);
}

function opawn_moves(&$board,$b,$x,$y) {
	$directions = [
		[3,0],
		[-3,0],
		[0,3],
		[0,-3],
		[1,0],
		[-1,0],
		[0,1],
		[0,-1]
	];	
	return(bishop_rook_queen_moves($board,$b,$x,$y,$directions));
	
}

function mpawn_moves(&$board,$b,$x,$y) {
	$directions = [
		[2,0],
		[-2,0],
		[0,2],
		[0,-2],
		[1,0],
		[-1,0],
		[0,1],
		[0,-1]
	];	
	return(bishop_rook_queen_moves($board,$b,$x,$y,$directions));
}

function ipawn_moves(&$board,$b,$x,$y) {
	$directions = [
		[1,0],
		[-1,0],
		[0,1],
		[0,-1],
		[1,0],
		[-1,0],
		[0,1],
		[0,-1]
	];	
	return(bishop_rook_queen_moves($board,$b,$x,$y,$directions));
}

function bishop_rook_queen_moves(&$board,$b,$x,$y,$directions) {
	$moves=[];

	foreach($directions as $d=>$direction) {
		for($i=$x+$direction[0],$j=$y+$direction[1]; $i>=1 && $i<=7 && $j>=1 && $j<=7; $i+=$direction[0], $j+=$direction[1]) {
			if( $board[$i][$j]['piece_color'] == null ){ 
				$move=['x'=>$i, 'y'=>$j];
				$moves[]=$move;
			} else if ( $board[$i][$j]['piece_color'] != $b) {
				$move=['x'=>$i, 'y'=>$j];
				$moves[]=$move;
				// Υπάρχει πιόνι αντιπάλου... Δεν πάμε παραπέρα.
				break;
			} else if ( $board[$i][$j]['piece_color'] == $b) {
				break;
			}
		}

	}
	$board[$x][$y]['moves'] = $moves;
	return(sizeof($moves));
}


function show_board() {

	global $mysqli;
	
	$sql = 'select * from board' ;
	$st = $mysqli->prepare($sql);
	
	$st->execute();
	$res = $st->get_result();
	
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
	
}

function reset_board() {
	global $mysqli;
	
	$sql = 'call reset_board()';
	$mysqli->query($sql);
	show_board();
}

function read_board() {
	global $mysqli;
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	return($res->fetch_all(MYSQLI_ASSOC));
}

function ppiece($x,$y,$piece_color,$input){
	global $mysqli;
	$sql = 'update board set piece_color=? where X=? and Y=? ';
	$st = $mysqli->prepare($sql);
	$st->bind_param('sss',$piece_color,$x,$y);
	$st->execute();
	
	$username=$input['username'];
	$sql = ' update players set playerNumber = playernumber + 1 where username=? ';
	$st3 = $mysqli->prepare($sql);
	$st3->bind_param('s',$username);
	$st3->execute();
	
	$sql = 'call `move_piece`(?,?);';
	$st2 = $mysqli->prepare($sql);
	$st2->bind_param('ii',$x,$y);
	$st2->execute();
	
	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
	
}

function rempiece($x,$y,$piece_color,$input){
	global $mysqli;
	$sql = ' update board set piece_color=null where X=? and Y=? ';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ss',$x,$y);
	$st->execute();
	
	$sql = 'call `turnupdate`(?);';
	$st2 = $mysqli->prepare($sql);
	$st2->bind_param('i',$x);
	$st2->execute();
	
	$arr = array('a' => $x, 'b' => $y, 'c' => $piece_color);
	header('Content-type: application/json');
	print json_encode($arr, JSON_PRETTY_PRINT);

}

function changeB(){
	global $mysqli;
	$sql = "update game_status set p_turn='W'";
	$st2 = $mysqli->prepare($sql);
	$st2->execute();

	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
}

function changeW(){
	global $mysqli;
	$sql = "update game_status set p_turn='B'";
	$st2 = $mysqli->prepare($sql);
	$st2->execute();

	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
}

function turn($x) {
	global $mysqli;
	$sql = 'call `turnupdate`(?);';
	$st2 = $mysqli->prepare($sql);
	$st2->bind_param('i',$x);
	$st2->execute();
	
	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
}

function do_move($x,$y,$x2,$y2) {
	global $mysqli;
	$sql = 'call `move_piece2`(?,?,?,?);';
	$st = $mysqli->prepare($sql);
	$st->bind_param('iiii',$x,$y,$x2,$y2 );
	$st->execute();

	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
}

?>