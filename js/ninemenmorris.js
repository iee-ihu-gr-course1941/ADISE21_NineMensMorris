$(function () {
	draw_empty_board();
	fill_board();
});

function draw_empty_board() {
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

function fill_board_by_data(data,y,z) {
	for(var i=0;i<data.length;i++) {
		var o = data[i];
		var id = '#square_'+ o.X + '_' + o.Y;
		if (o.Bcolor == 'r') { 
			var im ='<img class="piece" src="images/B.png">'; 
		} else if (o.Bcolor == 'g') {
			var im = '<img class="piece" src="images/W.png">'; 
		}
		$(id).addClass(o.Bcolor+'_square').html(im);
		
	}
}