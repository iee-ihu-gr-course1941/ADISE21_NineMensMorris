<?php 

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

function ppiece($x,$y,$piece_color){
	global $mysqli;
	$sql = ' update board set piece_color=? where X=? and Y=? ';
	$st = $mysqli->prepare($sql);
	$st->bind_param('sss',$piece_color,$x,$y);
	$st->execute();
	
	
	
	$sql = 'call `move_piece`(?,?);';
	$st2 = $mysqli->prepare($sql);
	$st2->bind_param('ii',$x,$y);
	$st2->execute();
	
	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
	
}

?>