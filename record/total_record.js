//시즌 변경 시 전달할 데이터(시즌sid, 포지션sid)
function select_season(_this){
	//시즌 키값
	let season_key = $(_this).val();

	let senddata = new Object();
	senddata.season = season_key;

	api('api_total_season', senddata, function(output){
		if(output.is_success){
		//api로 받아온 데이터로 기록 업데이트
		batter_data = output.batter_data;
		pitcher_data = output.pitcher_data;

		draw();
		}else{
			alert(output.msg);
		}
	});
}

//통산 기록 이동
function total_record(){
    window.location.href = 'http://localhost/record/total_record.php';
}

function draw(){
	let batter_html = '';

	for(i=0; i<batter_data.length; i++){
		batter = batter_data[i];
		var avg = parseInt(batter['hits']) / parseInt(batter['pa']);
		var obp = (parseInt(batter['hits']) + parseInt(batter['bb']) + parseInt(batter['hbp'])) / (parseInt(batter['pa']) + parseInt(batter['bb']) + parseInt(batter['hbp']) + parseInt(batter['sf']));
		var slg = (parseInt(batter['hits']) + parseInt(batter['double_hits']) * 2 + parseInt(batter['triple_hits']) * 3 + parseInt(batter['hr']) * 4) / parseInt(batter['pa']);
		var ops = obp + slg;
		var babip = (parseInt(batter['hits']) - parseInt(batter['hr'])) / (parseInt(batter['pa']) - parseInt(batter['hr']) - parseInt(batter['k']) + parseInt(batter['sf']));
		
		batter_html += '<tr>';
			batter_html += '<td>' + batter['name'] + '</td>';
			batter_html += '<td>' + batter['position'] + '</td>';
			batter_html += '<td>' + parseInt(batter['game']) + '</td>';
			batter_html += '<td>' + parseInt(batter['pa']) + '</td>';
			batter_html += '<td>' + parseInt(batter['hits']) + '</td>';
			batter_html += '<td>' + parseInt(batter['double_hits']) + '</td>';
			batter_html += '<td>' + parseInt(batter['triple_hits']) + '</td>';
			batter_html += '<td>' + parseInt(batter['hr']) + '</td>';
			batter_html += '<td>' + parseInt(batter['r']) + '</td>';
			batter_html += '<td>' + parseInt(batter['rbi']) + '</td>';
			batter_html += '<td>' + parseInt(batter['bb']) + '</td>';
			batter_html += '<td>' + parseInt(batter['hbp']) + '</td>';
			batter_html += '<td>' + parseInt(batter['gb']) + '</td>';
			batter_html += '<td>' + parseInt(batter['fb']) + '</td>';
			batter_html += '<td>' + parseInt(batter['sf']) + '</td>';
			batter_html += '<td>' + parseInt(batter['sh']) + '</td>';
			batter_html += '<td>' + parseInt(batter['gdp']) + '</td>';
			batter_html += '<td>' + parseInt(batter['k']) + '</td>';
			batter_html += '<td>' + parseInt(batter['sb']) + '</td>';
			batter_html += '<td>' + parseInt(batter['error']) + '</td>';
			batter_html += '<td>' + avg.toFixed(3) + '</td>';
			batter_html += '<td>' + obp.toFixed(3) + '</td>';
			batter_html += '<td>' + slg.toFixed(3) + '</td>';
			batter_html += '<td>' + ops.toFixed(3) + '</td>';
			batter_html += '<td>' + babip.toFixed(3) + '</td>';
		batter_html += '</tr>';
		}
		$('#batter_table tbody').html(batter_html);

	let pitcher_html = '';
	for(i=0; i<pitcher_data.length; i++){
		pitcher = pitcher_data[i];
		var era = (parseInt(pitcher['er']) * 9) / parseFloat(pitcher['ip']);
		var whip = (parseInt(pitcher['bb']) + parseInt(pitcher['hits'])) / (parseFloat(pitcher['ip']) / 3);
		var fip = (((parseInt(pitcher['hr']) * 13) + ((parseInt(pitcher['bb']) + parseInt(pitcher['hbp'])) * 3) - parseInt(pitcher['k']) * 2) / (parseFloat(pitcher['ip']) / 3)) + 3.2;
		
		pitcher_html += '<tr>'
			pitcher_html += '<td>' + pitcher['name'] + '</td>';
			pitcher_html += '<td>' + pitcher['position'] + '</td>';		
			pitcher_html += '<td>' + parseInt(pitcher['game']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['win']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['lose']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['r']) + '</td>';
			pitcher_html += '<td>' + parseFloat(pitcher['ip']).toFixed(1) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['cg']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['sho']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['sv']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['qs']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['gb']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['fb']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['iffb']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['ifh']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['hr']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['k']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['bb']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['error']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['hits']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['pitches']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['ibb']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['er']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['sf']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['hbp']) + '</td>';
			pitcher_html += '<td>' + parseInt(pitcher['bb']) + '</td>';
			pitcher_html += '<td>' + era.toFixed(2) + '</td>';
			pitcher_html += '<td>' + whip.toFixed(2) + '</td>';
			pitcher_html += '<td>' + fip.toFixed(2) + '</td>';
		pitcher_html += '</tr>';
	}
	$('#pitcher_table tbody').html(pitcher_html);
	return null;
}