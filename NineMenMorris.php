<?php 


require_once "lib/dbconnect.php";
require_once "lib/board.php";
require_once "lib/game.php";
require_once "lib/users.php";

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);



switch ($r=array_shift($request)) {
    case 'board' : 
        switch ($b=array_shift($request)) {
            case '':
            case null: handle_board($method);
                        break;
            case 'piece': handle_piece($method, $request[0],$request[1],$input);
                        break;
            }
            break;
    case 'status': 
			if(sizeof($request)==0) {handle_status($method);}
			else {header("HTTP/1.1 404 Not Found");}
			break;
	case 'playernumber': handle_playernumber($method, $request,$input);
			break;
	case 'removepiece': handle_removepiece($method, $request[0],$request[1],$request[2],$input);
			break;
	case 'removeturn': handle_removeturn($method, $request[0]);
			break;
	case 'putpiece': handle_putpiece($method, $request[0],$request[1],$request[2],$input);
			break;
	case 'changeW': handle_changeW($method);
			break;
	case 'changeB': handle_changeB($method);
			break;
	case 'counternumber': handle_counternumber($method, $request[0]);
			break;
	case 'getcounternumber': handle_getcounternumber($method, $request[0]);
			break;
	case 'winB': handle_winB($method);
			break;
	case 'winW': handle_winW($method);
			break;
	case 'players': handle_player($method, $request,$input);
			    break;
	default:  header("HTTP/1.1 404 Not Found");
                        exit;
}

function handle_winW($method){
	if($method=='GET') {
            winW();
    } else {
        header('HTTP/1.1405 Method Not Allowed');
    }
}

function handle_winB($method){
	if($method=='GET') {
            winB();
    } else {
        header('HTTP/1.1405 Method Not Allowed');
    }
}

function handle_getcounternumber($method, $col){
	if($method=='GET') {
            getcounterNum($col);
    } else {
        header('HTTP/1.1405 Method Not Allowed');
    }
}

function handle_counternumber($method, $col){
	if($method=='PUT') {
            counterNum($col);
    } else {
        header('HTTP/1.1405 Method Not Allowed');
    }
}

function handle_removepiece($method , $x, $y, $piece_color, $input){
	if($method=='PUT') {
            rempiece($x, $y, $piece_color, $input);
    } else {
        header('HTTP/1.1405 Method Not Allowed');
    }
}

function handle_removeturn($method,$x){
	if($method=='GET') {
            turn($x);
    } else {
        header('HTTP/1.1405 Method Not Allowed');
    }
}

function handle_putpiece($method ,$x, $y,$piece_color,$input){
	if($method=='PUT'){
		ppiece($x,$y,$piece_color,$input);
	}else{
		header("HTTP/1.1 404 Not Found");
		print json_encode(['errormesg'=>"error"]);
	}
}

function handle_playernumber($method, $p,$input) {
    switch ($b=array_shift($p)) {
        case 'B': 
		case 'W': handle_pn($method, $b,$input);
					break;
		default: header("HTTP/1.1 404 Not Found");
				 print json_encode(['errormesg'=>"Player $b not found."]);
                 break;
	}
}

function handle_changeW($method){
    if($method == 'GET'){
        changeW();
    }else{
        header('HTTP/1.1405 Method Not Allowed');
    }
}

function handle_changeB($method){
    if($method == 'GET'){
        changeB();
    }else{
        header('HTTP/1.1405 Method Not Allowed');
    }
}

function handle_board($method) {
    if($method=='GET') {
            show_board();
    } else if ($method=='POST') {
            reset_board();
    } else {
        header('HTTP/1.1405 Method Not Allowed');
    }
    
}

function handle_piece($method, $x,$y,$input) {
    if($method=='GET') {
        show_piece($x,$y);
    } else if ($method=='PUT') {
		move_piece($x,$y,$input['x'],$input['y'],$input['token']);
    }  
}

function handle_player($method, $p,$input) {
    switch ($b=array_shift($p)) {
		case '':
		case null: if($method=='GET') {show_users($method);}
				   else {header("HTTP/1.1 400 Bad Request"); 
						 print json_encode(['errormesg'=>"Method $method not allowed here."]);}
                    break;
        case 'B': 
		case 'W': handle_user($method, $b,$input);
					break;
		default: header("HTTP/1.1 404 Not Found");
				 print json_encode(['errormesg'=>"Player $b not found."]);
                 break;
	}
}

function handle_status($method) {
    if($method=='GET') {
        show_status();
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }
}

?>