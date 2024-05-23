//선수 조회
function search(page=1){
	let player_name = $('#player_name').val();
	let team_name = $('#team_name').val();
	let position = $('#position').val();
	let note = $('#note').val();

	senddata = new Object();
	senddata.page = page;
	senddata.is_player_name = player_name;
	senddata.is_team_name = team_name;
	senddata.is_position = position;
	senddata.is_note = note;

	render('info/player_info', senddata);
	return null;
}

//선수 상세정보 페이지로 이동
function detail(player_sid){

	senddata = new Object();
	senddata.player_sid = player_sid;

	render('info/player_detail', senddata);
	return null;
}