// 리그 변경 시 전달 할 데이터(리그sid)
function select_league(_this){
	let league_key = $(_this).val();
	let senddata = new Object();
	senddata.league_sid = league_key;
	api('api_season_league', senddata, function(output){
		if(output.is_success){
			season_data = output.season_data;

			draw();
		}else{
			alert(output.msg);
		}
	});
}

function draw(){
	let html = '';

	//기록 표 제목
	html += '<div class="team_table">';
		html += '<div class="team_row header">';
		html += '<div class="team_cell">순위</div>';
		html += '<div class="team_cell">팀 이름</div>';
		html += '<div class="team_cell">경기</div>';
		html += '<div class="team_cell">승점</div>';
		html += '<div class="team_cell">승리</div>';
		html += '<div class="team_cell">무승부</div>';
		html += '<div class="team_cell">패배</div>';
		html += '<div class="team_cell">승률</div>';
		html += '<div class="team_cell">타수</div>';
		html += '<div class="team_cell">안타</div>';
		html += '<div class="team_cell">2루타</div>';
		html += '<div class="team_cell">3루타</div>';
		html += '<div class="team_cell">홈런</div>';
		html += '<div class="team_cell">득점</div>';
		html += '<div class="team_cell">도루</div>';
		html += '<div class="team_cell">이닝</div>';
		html += '<div class="team_cell">삼진</div>';
		html += '<div class="team_cell">볼넷</div>';
		html += '<div class="team_cell">세이브</div>';
		html += '<div class="team_cell">실책</div>';
		html += '<div class="team_cell">타율</div>';
		html += '<div class="team_cell">방어율</div>';
	html += '</div>';

	// 각 팀 정보 === 표 형식
	for(let i=0; i<season_data['output_cnt']; i++){

		let team_record = 0;
		let wins = parseInt(season_data[i]['win']);
		let draws = parseInt(season_data[i]['draw']);
		let losses = parseInt(season_data[i]['lose']);

		if(wins + losses + draws > 0){
			//승점 계산 === 현재 프로야구는 승점제를 시행하지 않고 있다 하지만 승점제가 순위 결정을 하는데 가장 공평하다고 생각되어 넣었다.
			team_point = (wins * 3) + (draws * 1) + (losses * 0);
			//승률 계산
			team_record = wins / ((wins + losses + draws) - draws);
		}

		//타율 계산
		let avg = (season_data[i]['hits'] / season_data[i]['ab']).toFixed(3);
		//방어율 계산
		let era = (season_data[i]['er'] * 9 * 3 / season_data[i]['ip']).toFixed(2);

		html += '<div class="team_row">';
			html += '<div class="team_cell">' + (i + 1) + '</div>';
			html += '<div class="team_cell team_name_with_logo">';
				html += '<img src="' + team_logo[season_data[i]['team_sid']] + '" class="team_logo">';
				html += '<span>' + team_name[season_data[i]['team_sid']] + '</span>';
			html += '</div>';
			html += '<div class="team_cell">' + season_data[i]['game'] + '</div>';
			html += '<div class="team_cell">' + team_point + '</div>';
			html += '<div class="team_cell">' + wins + '</div>';
			html += '<div class="team_cell">' + draws + '</div>';
			html += '<div class="team_cell">' + losses + '</div>';
			html += '<div class="team_cell">' + team_record.toFixed(3) + '</div>';
			html += '<div class="team_cell">' + season_data[i]['ab'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['hits'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['double_hits'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['triple_hits'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['hr'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['r'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['sb'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['ip'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['sv'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['k'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['bb'] + '</div>';
			html += '<div class="team_cell">' + season_data[i]['error'] + '</div>';
			html += '<div class="team_cell">' + avg + '</div>';
			html += '<div class="team_cell">' + era + '</div>';
		html += '</div>';
	}
	html += '</div>';

	//HTML 추가
	$('#team_section').html(html);

	return null
}