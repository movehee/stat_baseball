//  리스트 정보 변경시 수행
function is_onchange(_this, index) {
	index = parseInt(index); // index int 형변환
	let parent = $(_this).parent().parent(); // tr 부모

	// row의 입력값(날짜, 홈, 어웨이)
	let home_team_sid = parent.find('select[key="home_team_select"]').val();
	let away_team_sid = parent.find('select[key="away_team_select"]').val();

	// game_data 업데이트
	game_data[index]['home_team_sid'] = home_team_sid;
	game_data[index]['away_team_sid'] = away_team_sid;

	return null;
}

// 행 추가 함수
function add_row(){
	default_set = new Object();
	// 기본 설정값으로 새로운 행 추가
	default_set['home_team_sid'] = '';
	default_set['away_team_sid'] = '';

	// 새로운 행을 데이터에 추가
	game_data.push(default_set);

	// 화면 다시 그리기
	setTimeout(function(){
		draw();
	},100);

	return null;
}

// 행 삭제 함수
function row_delete(index){
	index = parseInt(index);

	let temp_data = game_data;
	game_data = [];

	// 선택한 인덱스를 제외하고 데이터 복사
	for(let i=0; i<temp_data.length; i++){
		if(i === index){
			continue;
		}
		game_data.push(temp_data[i]);
	}
	// 화면 다시 그리기
	setTimeout(function(){
		draw();
	},100);

	return null;
}

function select_league(_this){
	let league_key = $(_this).val();
	let senddata = new Object();
	senddata.league_key = league_key;

	api('api_game_league', senddata, function(output){
		if(output.is_success){
			//가져온 리그 데이터
			league_data = output.league_data;

			//기본 값 먼저 사용
			default_set = new Object();
			default_set['home_team_sid'] ='';
			default_set['away_team_sid'] ='';

			game_data = new Array();
			game_data.push(default_set);

			draw();
		}else{
			alert(output.msg);
		}
	});
}

function draw(){
	let html = '';

	for(let i=0; i<game_data.length; i++){
	html += '<tr>';
		html += '<td>';
			html += '<select id="home_team_select" key="home_team_select" onchange="is_onchange(this, ' + i + ');">';
				html += '<option value="" selected disabled>팀 선택</option>';
		for(let j=0; j<league_data.output_cnt; j++){
			let is_match = game_data[i]['home_team_sid'] === league_data[j]['sid'];
			if(is_match === true){
				html += '<option value="' + league_data[j]['sid'] + '" selected>' + league_data[j]['team_name'] + '</option>';
			}else{
				html += '<option value="' + league_data[j]['sid'] + '">' + league_data[j]['team_name'] + '</option>';
			}
		}
			html += '</select>';
		html += '</td>';
		html += '<td>vs</td>';
		html +='<td>';
			html += '<select id="away_team_select" key="away_team_select" onchange="is_onchange(this, ' + i + ');">';
				html += '<option value="" selected disabled>팀 선택</option>';
		for(let j=0; j<league_data.output_cnt; j++){
			let is_match = game_data[i]['away_team_sid'] === league_data[j]['sid'];
			if(is_match === true){
				html += '<option value="' + league_data[j]['sid'] + '" selected>' + league_data[j]['team_name'] + '</option>';
			}else{
				html += '<option value="' + league_data[j]['sid'] + '">' + league_data[j]['team_name'] + '</option>';
			}
		}
			html += '</select>';
		html += '</td>';
		// 버튼
		btn_text = '삭제';
		btn_function = 'row_delete(' + i + ');';
		if(i === game_data.length - 1){
			btn_text = '추가';
			btn_function = 'add_row();';
		}
		html += '<td>';
			html += '<button onclick="' + btn_function + '">' + btn_text + '</button>';
		html += '</td>';
	html += '</tr>';
	}
	$('#game_table tbody').html(html);
}

//경기 등록 함수
function registration(){
	if(confirm('등록 하시겠습니까?')){
		//게임 날짜 데이터
		let game_date = $('#game_date').val();
        let home_teams = [];
        let away_teams = [];

        // 선택된 홈팀과 어웨이팀 저장
        $('select[id="home_team_select"]').each(function(){
            home_teams.push($(this).val());
        });
        $('select[id="away_team_select"]').each(function(){
            away_teams.push($(this).val());
        });

		//경기 생성 유효성 검사
		for(let i=0; i<game_data.length; i++){
			if(game_data[i]['home_team_sid'] === ''){
				alert((i+1) + '번째 줄의 홈팀이 선택되지 않았습니다.');
				return false;
			}
			if(game_data[i]['away_team_sid'] === ''){
				alert((i+1) + '번째 줄의 어웨이팀이 선택되지 않았습니다.');
				return false;
			}
			if(game_data[i]['home_team_sid'] === game_data[i]['away_team_sid']){
				alert((i+1) + '번째 줄의 홈팀과 어웨이팀이 같으면 안됩니다.');
				return false;
			}
		}
		if(game_date === ''|| game_date === null){
			alert('경기 시간을 선택해야합니다.');
			return false;
		}

		let senddata = new Object();
		senddata.game_date = game_date;
		senddata.home_team = home_teams;
		senddata.away_team = away_teams;

		//api호출 하여 게임 등록
		api('api_game_insert', senddata, function(output){
			if(output.is_success){
				render('record/recent_matches');
			}
			alert(output.msg);
		});
		return null;
	}
}

