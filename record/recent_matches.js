//경기 조회
function search(page=1){
	let start_date = $('#start_date').val();
	let end_date = $('#end_date').val();

	//시작 날짜, 마지막 날짜가 선택 안되었을 시 알림창
	if(start_date === false){
		alert('시작 날짜를 선택해 주세요.');
		return null;
	}
	if(end_date === false){
		alert('마지막 날짜를 선택해 주세요.');
		return null;
	}

	senddata = new Object();
	senddata.page = page;
	senddata.start_date = start_date;
	senddata.end_date = end_date;

	render('record/recent_matches', senddata);
	return null;
}

//경기 기록 등록 페이지로 이동
function detail(game_sid, game_date, home_team_sid, away_team_sid, league_sid){

	senddata = new Object();
	senddata.game_sid = game_sid;
	senddata.game_date = game_date;
	senddata.home_team_sid = home_team_sid;
	senddata.away_team_sid = away_team_sid;
	senddata.league_sid = league_sid;

	render('record/game_detail', senddata);
	return null;
}

//경기 상세정보 페이지로 이동
function view(game_sid, game_date, home_team_sid, away_team_sid, league_sid){

	senddata = new Object();
	senddata.game_sid = game_sid;
	senddata.game_date = game_date;
	senddata.home_team_sid = home_team_sid;
	senddata.away_team_sid = away_team_sid;
	senddata.league_sid = league_sid;

	render('record/view_detail', senddata);
	return null;
}