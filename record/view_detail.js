function player(sid){
	
	let senddata = new Object();
	senddata.player_sid = sid;

	render('info/player_detail', senddata);
	return null;
}