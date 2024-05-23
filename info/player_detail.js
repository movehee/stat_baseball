//시즌 변경 시 전달할 데이터(시즌sid, 포지션sid)
function select_season(_this) {
	let season_key = $(_this).val();
	let senddata = new Object();
	senddata.season_sid = season_key;
	senddata.position_sid = position_sid;
	api('api_season_select', senddata, function(output){
		if(output.is_success){
		//api로 받아온 데이터로 기록 업데이트
			season_data = output.season_data;

			// h2 태그의 내용 업데이트
			let selected_season = $("#season_key option:selected").text();
			$("#season_id").text(selected_season + "기록");

			draw();
		}else{
			alert(output.msg);
		}
	});
}


function draw(){
	let html = '';

	if(position_sid > 9){
		// 투수인 경우
		html += '<tr>';
			html += '<td>' + season_data[0]['game'] + '</td>';
			html += '<td>' + season_data[0]['win'] + '</td>';
			html += '<td>' + season_data[0]['lose'] + '</td>';
			html += '<td>' + (season_data[0]['er'] / season_data[0]['ip']).toFixed(2) + '</td>';
			html += '<td>' + season_data[0]['r'] + '</td>';
			html += '<td>' + season_data[0]['ip'] + '</td>';
			html += '<td>' + season_data[0]['cg'] + '</td>';
			html += '<td>' + season_data[0]['sho'] + '</td>';
			html += '<td>' + season_data[0]['sv'] + '</td>';
			html += '<td>' + season_data[0]['qs'] + '</td>';
			html += '<td>' + season_data[0]['pa'] + '</td>';
			html += '<td>' + season_data[0]['gb'] + '</td>';
			html += '<td>' + season_data[0]['fb'] + '</td>';
			html += '<td>' + season_data[0]['iffb'] + '</td>';
			html += '<td>' + season_data[0]['ifh'] + '</td>';
			html += '<td>' + season_data[0]['hr'] + '</td>';
			html += '<td>' + season_data[0]['k'] + '</td>';
			html += '<td>' + season_data[0]['bb'] + '</td>';
			html += '<td>' + season_data[0]['error'] + '</td>';
			html += '<td>' + ((season_data[0]['hits'] + season_data[0]['bb'] + season_data[0]['hbp'] - season_data[0]['r']) / (season_data[0]['hits'] + season_data[0]['bb'] + season_data[0]['hbp'] - 1.4 * season_data[0]['hr'])).toFixed(3) + '</td>';
			html += '<td>' + ((12 * season_data[0]['hr'] + 3.2 * (season_data[0]['bb'] + season_data[0]['hbp']) - 2.5 * season_data[0]['k']) / (season_data[0]['ip'] + league_C)).toFixed(3) + '</td>';
			html += '<td>' + ((season_data[0]['hits'] + season_data[0]['bb']) / season_data[0]['ip']).toFixed(3) + '</td>';
			html += '<td>' + (season_data[0]['iffb'] / season_data[0]['fb']).toFixed(3) + '</td>';
			html += '<td>' + (season_data[0]['hr'] / season_data[0]['fb']).toFixed(3) + '</td>';
		html += '</tr>';
	}else{
		// 타자인 경우
		html += '<tr>';
			html += '<td>' + season_data[0]['game'] + '</td>';
			html += '<td>' + season_data[0]['pa'] + '</td>';
			html += '<td>' + season_data[0]['ab'] + '</td>';
			html += '<td>' + season_data[0]['hits'] + '</td>';
			html += '<td>' + season_data[0]['double_hits'] + '</td>';
			html += '<td>' + season_data[0]['triple_hits'] + '</td>';
			html += '<td>' + season_data[0]['hr'] + '</td>';
			html += '<td>' + season_data[0]['r'] + '</td>';
			html += '<td>' + season_data[0]['rbi'] + '</td>';
			html += '<td>' + season_data[0]['bb'] + '</td>';
			html += '<td>' + season_data[0]['hbp'] + '</td>';
			html += '<td>' + season_data[0]['gb'] + '</td>';
			html += '<td>' + season_data[0]['fb'] + '</td>';
			html += '<td>' + season_data[0]['sf'] + '</td>';
			html += '<td>' + season_data[0]['sh'] + '</td>';
			html += '<td>' + season_data[0]['gdp'] + '</td>';
			html += '<td>' + season_data[0]['k'] + '</td>';
			html += '<td>' + season_data[0]['sb'] + '</td>';
			html += '<td>' + (season_data[0]['hits'] / season_data[0]['ab']).toFixed(3) + '</td>';
			html += '<td>' + ((season_data[0]['hits'] + season_data[0]['bb'] + season_data[0]['hbp']) / (season_data[0]['ab'] + season_data[0]['bb'] + season_data[0]['hbp'] + season_data[0]['sf'])).toFixed(3) + '</td>';
			html += '<td>' + ((season_data[0]['hits'] + 2 * season_data[0]['double_hits'] + 3 * season_data[0]['triple_hits'] + 4 * season_data[0]['hr']) / season_data[0]['ab']).toFixed(3) + '</td>';
			html += '<td>' + ((season_data[0]['hits'] + season_data[0]['bb'] + season_data[0]['hbp']) / (season_data[0]['ab'] + season_data[0]['bb'] + season_data[0]['hbp'] + season_data[0]['sf'])+((season_data[0]['hits'] + 2 * season_data[0]['double_hits'] + 3 * season_data[0]['triple_hits'] + 4 * season_data[0]['hr']) / season_data[0]['ab'])).toFixed(3) + '</td>';
			html += '<td>' + ((season_data[0]['hits'] - season_data[0]['hr']) / (season_data[0]['pa'] - season_data[0]['hr'] - season_data[0]['k'] + season_data[0]['sf'])).toFixed(3) + '</td>';
			html += '<td>' + (((season_data[0]['hits'] + 2 * season_data[0]['double_hits'] + 3 * season_data[0]['triple_hits'] + 4 * season_data[0]['hr']) / season_data[0]['ab'])-(season_data[0]['hits'] / season_data[0]['ab'])).toFixed(3) + '</td>';
		html += '</tr>';
	}

	// 테이블에 삽입
	$('#grid_table tbody').html(html);

	return null;
}
