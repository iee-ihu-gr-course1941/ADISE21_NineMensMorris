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
	
    $color1 = current_bcolor($token,$x2,$y2);
	if($color1 == 'r'){
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"cant move there."]); 
		exit;
	}	
	
	$color = current_color($token);
	$color2 = current_color2($token,$x2,$y2);
	
	if(($color2 == 'W') || ($color2 == 'B')){
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"cant move there,there is a pawn there."]); 
		exit;
	}
	
	if($token==null || $token=='') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"token is not set."]);
		exit;
	}
	

	if($color==null ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You are not a player of this game."]);
		exit;
	}
	/*$status = read_status();
	if($status['status']!='started') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Game is not in action."]);
		exit;
	}
	if($status['p_turn']!=$color) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"It is not your turn."]);
		exit;
	}*/ 
	//OTAN KANEI 3ADA META APO MOVE KAMIA FORA ALLAZEI TO TURN ENW DN PREPEI

	$orig_board=read_board();
	$board=convert_board($orig_board);
	$n = add_valid_moves_to_piece($board,$color,$x,$y,$token);
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


function convert_board(&$orig_board) {
	$board=[];
	foreach($orig_board as $i=>&$row) {
		$board[$row['X']][$row['Y']] = &$row;
	} 
	return($board);
}

function add_valid_moves_to_piece(&$board,$b,$x,$y,$token) {
	$number_of_moves=0;
	$cNumber = currentNumber($token);
	if($board[$x][$y]['piece_color']==$b) {
		if($cNumber != 3){
			switch($x){
				case '1': 
					switch($y){
						case '1': $number_of_moves+=one1_moves($board,$b,$x,$y);break;
						case '2': break;
						case '3': break;
						case '4': $number_of_moves+=one4_moves($board,$b,$x,$y);break;
						case '5': break;
						case '6': break;
						case '7': $number_of_moves+=one1_moves($board,$b,$x,$y);break;
					}
					break;
				case '2': 
					switch($y){
						case '1': break;
						case '2': $number_of_moves+=two2_moves($board,$b,$x,$y);break;
						case '3': break;
						case '4': $number_of_moves+=two4_moves($board,$b,$x,$y);break;
						case '5': break;
						case '6': $number_of_moves+=two2_moves($board,$b,$x,$y);break;
						case '7': break;
					}
					break;
				case '3': 
					switch($y){
						case '1': break;
						case '2': break;
						case '3': $number_of_moves+=three3_moves($board,$b,$x,$y);break;
						case '4': $number_of_moves+=three3_moves($board,$b,$x,$y);break;
						case '5': $number_of_moves+=three3_moves($board,$b,$x,$y);break;
						case '6': break;
						case '7': break;
					}
					break;
				case '4': 
					switch($y){
						case '1': $number_of_moves+=four1_moves($board,$b,$x,$y);break;
						case '2': $number_of_moves+=four2_moves($board,$b,$x,$y);break;
						case '3': $number_of_moves+=three3_moves($board,$b,$x,$y);break;
						case '4': break;
						case '5': $number_of_moves+=three3_moves($board,$b,$x,$y);break;
						case '6': $number_of_moves+=four2_moves($board,$b,$x,$y);break;
						case '7': $number_of_moves+=four1_moves($board,$b,$x,$y);break;
					}
					break;
				case '5': 
					switch($y){
						case '1': break;
						case '2': break;
						case '3': $number_of_moves+=three3_moves($board,$b,$x,$y);break;
						case '4': $number_of_moves+=three3_moves($board,$b,$x,$y);break;
						case '5': $number_of_moves+=three3_moves($board,$b,$x,$y);break;
						case '6': break;
						case '7': break;
					}
					break;
				case '6': 
					switch($y){
						case '1': break;
						case '2': $number_of_moves+=two2_moves($board,$b,$x,$y);break;
						case '3': break;
						case '4': $number_of_moves+=two4_moves($board,$b,$x,$y);break;
						case '5': break;
						case '6': $number_of_moves+=two2_moves($board,$b,$x,$y);break;
						case '7': break;
					}
					break;
				case '7': 
					switch($y){
						case '1': $number_of_moves+=one1_moves($board,$b,$x,$y);break;
						case '2': break;
						case '3': break;
						case '4': $number_of_moves+=one4_moves($board,$b,$x,$y);break;
						case '5': break;
						case '6': break;
						case '7': $number_of_moves+=one1_moves($board,$b,$x,$y);break;
					}
					break;
			}
		}else {
			$number_of_moves+=final_moves($board,$b,$x,$y);
		}
	} 
	return($number_of_moves);
}

function final_moves($board,$b,$x,$y){
	$directions = [
		 [1,0],[-1,0],[0,1],[0,-1],[1,1],[-1,1],[1,-1],[-1,-1],
		 [2,0],[-2,0],[0,2],[0,-2],[2,1],[-2,1],[1,2],[1,-2],[2,-1],[-2,-1],[-1,2],[-1,-2],[2,2],[-2,2],[2,-2],[-2,-2],
		 [3,0],[-3,0],[0,3],[0,-3],[3,1],[-3,1],[1,3],[1,-3],[3,-1],[-3,-1],[-1,3],[-1,-3],[3,2],[-3,2],[2,3],[2,-3],[3,-2],[-3,-2],[-2,3],[-2,-3],[3,3],[-3,3],[3,-3],[-3,-3],
		 [4,0],[-4,0],[0,4],[0,-4],[4,1],[-4,1],[1,4],[1,-4],[4,-1],[-4,-1],[-1,4],[-1,-4],[-4,2],[2,4],[2,-4],[4,-2],[-4,-2],[-2,4],[-2,-4], 
		 [-4,3],[3,4],[3,-4],[4,-3],[-4,-3],[-3,4],[-3,-4],[4,4],[-4,4],[4,-4],[-4,-4],
		 [5,0],[-5,0],[0,5],[0,-5],[5,1],[-5,1],[1,5],[1,-5],[5,-1],[-5,-1],[-1,5],[-1,-5],[5,2],[-5,2],[2,5],[2,-5],[5,-2],[-5,-2],[-2,5],[-2,-5],
		 [5,3],[-5,3],[3,5],[3,-5],[5,-3],[-5,-3],[-3,5],[-3,-5],[5,4],[-5,4],[4,5],[4,-5],[5,-4],[-5,-4],[-4,5],[-4,-5],[5,5],[-5,5],[5,-5],[-5,-5],
		 [6,0],[-6,0],[0,6],[0,-6],[6,1],[-6,1],[1,6],[1,-6],[6,-1],[-6,-1],[-1,6],[-1,-6],[6,2],[-6,2],[2,6],[2,-6],[6,-2],[-6,-2],[-2,6],[-2,-6],
		 [6,3],[-6,3],[3,6],[3,-6],[6,-3],[-6,-3],[-3,6],[-3,-6],[6,4],[-6,4],[4,6],[4,-6],[6,-4],[-6,-4],[-4,6],[-4,-6],
		 [6,5],[-6,5],[5,6],[5,-6],[6,-5],[-6,-5],[-5,6],[-5,-6],[6,6],[-6,6],[6,-6],[-6,-6],
		 [7,0],[-7,0],[0,7],[0,-7],[7,1],[-7,1],[1,7],[1,-7],[7,-1],[-7,-1],[-1,7],[-1,-7],[7,2],[-7,2],[2,7],[2,-7],[7,-2],[-7,-2],[-2,7],[-2,-7],
		 [7,3],[-7,3],[3,7],[3,-7],[7,-3],[-7,-3],[-3,7],[-3,-7],[7,4],[-7,4],[4,7],[4,-7],[7,-4],[-7,-4],[-4,7],[-4,-7],
		 [7,5],[-7,5],[5,7],[5,-7],[7,-5],[-7,-5],[-5,7],[-5,-7],[7,6],[-7,6],[6,7],[6,-7],[7,-6],[-7,-6],[-6,7],[-6,-7],[7,7],[-7,7],[7,-7],[-7,-7]
	];
	return(finalpawnmoves($board,$b,$x,$y,$directions));
}

function three3_moves(&$board,$b,$x,$y) {
	$m = [
		[1,0],
		[-1,0],
		[0,1],
		[0,-1]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
	
}

function two4_moves(&$board,$b,$x,$y) {
	$m = [
		[1,0],
		[-1,0],
		[0,2],
		[0,-2]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
	
}

function four2_moves(&$board,$b,$x,$y) {
	$m = [
		[2,0],
		[-2,0],
		[0,1],
		[0,-1]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
	
}

function one1_moves(&$board,$b,$x,$y) {
	$m = [
		[3,0],
		[-3,0],
		[0,3],
		[0,-3]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
	
}

function one4_moves(&$board,$b,$x,$y) {
	$m = [
		[1,0],
		[-1,0],
		[0,3],
		[0,-3]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
}

function four1_moves(&$board,$b,$x,$y) {
	$m = [
		[3,0],
		[-3,0],
		[0,1],
		[0,-1]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
}

function two2_moves(&$board,$b,$x,$y) {
	$m= [
		[2,0],
		[-2,0],
		[0,2],
		[0,-2]
	];	
	return(pawnmoves($board,$b,$x,$y,$m));
}

function finalpawnmoves(&$board,$b,$x,$y,$directions) {
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

function pawnmoves(&$board,$b,$x,$y,$m) {
	$moves=[];
	foreach($m as $k=>$t) {
		$x2=$x+$t[0];
		$y2=$y+$t[1];
		if( $x2>=1 && $x2<=7 && $y2>=1 && $y2<=7 &&
			$board[$x2][$y2]['piece_color'] !=$b ) {
			// Αν ο προορισμός είναι εντός σκακιέρας και δεν υπάρχει δικό μου πιόνι
			$move=['x'=>$x2, 'y'=>$y2];
			$moves[]=$move;
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

function getcounterNum($col){
	global $mysqli;
	$sql = 'select counterNumber from players where piece_color=? ';
	$st = $mysqli->prepare($sql);
	$st->bind_param('s',$col);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}

function counterNum($col){
	global $mysqli;
	$sql = 'update players set counterNumber = counterNumber - 1 where piece_color=? ';
	$st = $mysqli->prepare($sql);
	$st->bind_param('s',$col);
	$st->execute();
	
	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
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