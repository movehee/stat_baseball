//조회
function search(page=1){
	let team_name = $('#is_team_name').val();

	let senddata = new Object();
	senddata.is_team_name = team_name;

	render('info/team_info', senddata);

	return null;
}

//시즌 변경 시 전달할 데이터(시즌sid, 포지션sid)
function select_team(_this) {
	let league_key = $(_this).val();
	let senddata = new Object();
	senddata.league_sid = league_key;
	api('api_team_select', senddata, function(output){
		if(output.is_success){
		//api로 받아온 데이터로 기록 업데이트
		league_data = output.league_data;

		draw();
		}else{
			alert(output.msg);
		}
	});
}

function draw() {
	let html = '';
	let total_team = league_data['output_cnt']; // 리그에 참가 중인 팀의 총 수

	// 팀의 총 개수에 따라 칼럼을 나누기
	let total_col = Math.ceil(total_team / 5);

	for(let col=0; col<total_col; col++){
	html += '<div class="team_column">';

	// 각 칼럼에 팀 정보 표시
		for (let i=col * 5; i<Math.min((col + 1) * 5, total_team); i++){
			let team_record = 0;
			let wins = parseInt(league_data[i]['team_record_win']);
			let draws = parseInt(league_data[i]['team_record_draw']);
			let losses = parseInt(league_data[i]['team_record_lose']);
			if (wins + losses + draws > 0){
				team_record = wins / (wins + losses + draws);
			}

		html += '<div class="team_item">';
			html += '<div class="team_logo ' + league_data[i]['short_name'] + '"></div>';
			html += '<div class="team_info">';
				html += '<div>' + league_data[i]['team_name'] + '</div>';
				html += '<div>리그 1위 횟수 : ' + league_data[i]['winner'] + '</div>';
				html += '<div>' + wins + '승 ' + draws + '무 ' + losses + '패(' + team_record.toFixed(3) + ')</div>';
			html += '</div>';
		html += '</div>';
		}

	html += '</div>';
	}

	// HTML 추가
	$('#team_section').html(html);

	return null;
}
