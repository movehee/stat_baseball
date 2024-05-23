let position_sid = null; // 전역 변수로 선언

//시즌 변경 시 전달할 데이터(시즌sid, 포지션sid)
function click_position(clicked_position_sid){

	//클릭한 포지션 sid 전달
	position_sid = clicked_position_sid;

	if(position_sid === 1){
		setSectionShow(true, false); // 타자 선택 시 타자 섹션만 표시
	}else if(position_sid === 2){
		setSectionShow(false, true); // 투수 선택 시 투수 섹션만 표시
	}

	let senddata = new Object();
	senddata.position_sid = position_sid;

	api('api_position_select', senddata, function(output){
		if(output.is_success){
			//api로 받아온 데이터로 기록 업데이트
			position_data = output.position_data;
			window.position_sid = output.position_sid;

			draw();
		}else{
			alert(output.msg);
		}
	});
}

// 타자, 투수 섹션 표시 여부 설정 함수
function setSectionShow(batterShow, pitcherShow){
	if(batterShow){
		$('#batter_section').show();
		$('#batter_section').prev('.header').show(); // 타자 섹션의 이전 요소가 헤더이므로 함께 표시
	}else{
		$('#batter_section').hide();
		$('#batter_section').prev('.header').hide(); // 타자 섹션의 이전 요소가 헤더이므로 함께 숨김
	}
	if(pitcherShow){
		$('#pitcher_section').show();
		$('#pitcher_section').prev('.header').show(); // 투수 섹션의 이전 요소가 헤더이므로 함께 표시
	}else{
		$('#pitcher_section').hide();
		$('#pitcher_section').prev('.header').hide(); // 투수 섹션의 이전 요소가 헤더이므로 함께 숨김
	}
}

function draw(){

	let html = '';

	// 객체를 배열로 변환
	let position = [];
	for(let key in position_data){
		//output_cnt가 있어서 객체였던걸 제외 후 새 배열에 push
		if (key !== 'output_cnt'){
			position.push(position_data[key]);
		}
	}
	// 타자인 경우
	if(position_sid === 1){
		// 데이터 행 추가
		for(let i=0; i<position.length; i++){ 
		html += '<tr>';
			html += '<td>' + (i + 1) + '</td>';
			html += '<td>' + '<img src="' + team_logo[player_team[position[i]['player_sid']]] + '" alt="Team Logo">' + team_name[player_team[position[i]['player_sid']]] + '</td>';
			html += '<td>' + player_name[position[i]['player_sid']] + '</td>';
			html += '<td>' + position[i]['game'] + '</td>';
			html += '<td>' + position[i]['ab'] + '</td>';
			html += '<td>' + position[i]['hits'] + '</td>';
			html += '<td>' + position[i]['double_hits'] + '</td>';
			html += '<td>' + position[i]['triple_hits'] + '</td>';
			html += '<td>' + position[i]['hr'] + '</td>';
			html += '<td>' + position[i]['rbi'] + '</td>';
			html += '<td>' + position[i]['r'] + '</td>';
			html += '<td>' + position[i]['bb'] + '</td>';
			html += '<td>' + position[i]['sb'] + '</td>';
			html += '<td>' + (position[i]['hits'] / position[i]['ab']).toFixed(3) + '</td>';
			html += '<td>' + ((position[i]['hits'] + position[i]['bb'] + position[i]['hbp']) / (position[i]['ab'] + position[i]['bb'] + position[i]['hbp'] + position[i]['sf'])).toFixed(3) + '</td>';		
			html += '<td>' + ((position[i]['hits'] + 2 * position[i]['double_hits'] + 3 * position[i]['triple_hits'] + 4 * position[i]['hr']) / position[i]['ab']).toFixed(3) + '</td>';
			html += '<td>' + ((position[i]['hits'] + position[i]['bb'] + position[i]['hbp']) / (position[i]['ab'] + position[i]['bb'] + position[i]['hbp'] + position[i]['sf'])+((position[i]['hits'] + 2 * position[i]['double_hits'] + 3 * position[i]['triple_hits'] + 4 * position[i]['hr']) / position[i]['ab'])).toFixed(3) + '</td>';
		html += '</tr>'; // team_row 닫기
		}
		$('#batter_section').html(html);
	}else if(position_sid === 2){
		// 투수인 경우
		for(let i=0; i<position.length; i++){
		html += '<tr>';
			html += '<td>' + (i + 1) + '</td>';
			html += '<td>' + '<img src="' + team_logo[player_team[position[i]['player_sid']]] + '" alt="Team Logo">' + team_name[player_team[position[i]['player_sid']]] + '</td>';
			html += '<td>' + player_name[position[i]['player_sid']] + '</td>';
			html += '<td>' + position[i]['game'] + '</td>';
			html += '<td>' + (position[i]['er'] / position[i]['ip']).toFixed(2) + '</td>';
			html += '<td>' + position[i]['win'] + '</td>';
			html += '<td>' + position[i]['lose'] + '</td>';
			html += '<td>' + position[i]['r'] + '</td>';
			html += '<td>' + position[i]['ip'] + '</td>';
			html += '<td>' + position[i]['cg'] + '</td>';
			html += '<td>' + position[i]['sho'] + '</td>';
			html += '<td>' + position[i]['sv'] + '</td>';
			html += '<td>' + position[i]['qs'] + '</td>';
			html += '<td>' + position[i]['k'] + '</td>';
			html += '<td>' + position[i]['bb'] + '</td>';
			html += '<td>' + (position[i]['k'] / position[i]['bb']).toFixed(2) + '</td>';
			html += '<td>' + ((position[i]['hits'] + position[i]['bb']) / position[i]['ip']).toFixed(2) + '</td>';
		html += '</tr>'; // team_row 닫기
		}
		$('#pitcher_section').html(html);
	}

	return null;
}

//필드 기록 정렬
let sortForm = 'desc'; //정렬의 기본 형식 === 내림차순

function sortField(field){
	// 객체를 배열로 변환
	let positionArray = [];
	for(let key in position_data){
		if(key !== 'output_cnt'){
			positionArray.push(position_data[key]);
		}
	}

	//정렬 방식에 따라 정렬 함수 선택
	let sortFunction;
	if(sortForm === 'desc'){
		sortFunction= (a, b) => b[field] - a[field] // 내림차순 정렬
		sortForm = 'asc'; // 오름차순으로 변경
	}else{
		sortFunction= (a, b) => a[field] - b[field] // 오름차순 정렬
		sortForm = 'desc'; //내림차순으로 변경
	}
	//선택한 정렬 반영
	positionArray.sort(sortFunction);

	// 정렬된 배열을 다시 position_data에 할당하여 업데이트
	position_data = {}; // 기존 데이터 초기화
	for(let i=0; i <positionArray.length; i++){
		position_data[i] = positionArray[i];
	}
	draw();
	return null;
}
//공통 기준
function sortGames(){
	sortField('game'); // 게임 수 기준 정렬
}
function sortR(){
	sortField('r'); // 득점 기준 정렬
}
function sortBb(){
	sortField('bb'); // 볼넷 기준 정렬
}
// 타자 기준
function sortAbs(){
	sortField('ab'); // 타수 기준 정렬
}
function sortHits(){
	sortField('hits'); // 안타 기준 정렬
}
function sortDouble(){
	sortField('double_hits'); // 2루타 기준 정렬
}
function sortTriple(){
	sortField('triple_hits'); // 3루타 기준 정렬
}
function sortHomeRuns(){
	sortField('hr'); // 홈런 기준 정렬
}
function sortRbi(){
	sortField('rbi'); // 타점 기준 정렬
}

function sortSb(){
	sortField('sb'); // 도루 기준 정렬
}
//투수 기준
function sortWin(){
	sortField('win'); // 승리 기준 정렬
}
function sortLose(){
	sortField('lose'); // 패배 기준 정렬
}
function sortIp(){
	sortField('ip'); // 이닝 기준 정렬
}
function sortCg(){
	sortField('cg'); // 완투 기준 정렬
}
function sortSho(){
	sortField('sho'); // 완봉 기준 정렬
}
function sortSv(){
	sortField('sv'); // 세이브 기준 정렬
}
function sortQs(){
	sortField('qs'); // 퀄리티스타트 기준 정렬
}
function sortK(){
	sortField('k'); // 삼진 기준 정렬
}

//선수 계산식 반영 함수
function sortStat(){
	// 객체를 배열로 변환
	let positionArray = [];
	for(let key in position_data){
		if(key !== 'output_cnt'){
			positionArray.push(position_data[key]);
		}
	}

	// 타자의 타율과 출루율을 계산하여 추가
	for(let i=0; i<positionArray.length; i++){
		let player = positionArray[i];
		player.avg = calculateAvg(player);
		player.obp = calculateObp(player);
		player.slg = calculateSlg(player);
		player.ops = calculateOps(player);
		// 투수인 경우
		if(position_sid === 2){
			player.era = calculateEra(player);
			player.kbb = calculateKbb(player);
			player.whip = calculateWhip(player);
		}
	}

	// 정렬 방식에 따라 정렬 함수 선택
	let sortFunction;
	if(sortForm === 'desc'){
		sortFunction = (a, b) => b.avg - a.avg || b.obp - a.obp || b.slg - a.slg || b.ops - a.ops || a.era - b.era || b.kbb - a.kbb || b.whip - a.whip; // 내림차순 정렬 & 투수는 반대
		sortForm = 'asc'; // 오름차순으로 변경
	}else{
		sortFunction = (a, b) => a.avg - b.avg || a.obp - b.obp || a.slg - b.slg || a.ops - b.ops || b.era - a.era || a.kbb - b.kbb || a.whip - b.whip; // 오름차순 정렬 & 투수는 반대
		sortForm = 'desc'; // 내림차순으로 변경
	}

	// 선택한 정렬 반영
	positionArray.sort(sortFunction);

	// 정렬된 배열을 다시 position_data에 할당하여 업데이트
	position_data = {}; // 기존 데이터 초기화
	for(let i=0; i<positionArray.length; i++){
		position_data[i] = positionArray[i];
	}
	draw();
}
// 타자 기준
// 타율 계산 함수
function calculateAvg(player){
	if(player.ab === 0){
		return 0; // 타수가 0인 경우 타율은 0으로 처리
	}
	return (player.hits / player.ab).toFixed(3);
}

// 출루율 계산 함수
function calculateObp(player){
	if(player.ab === 0){
		return 0; // 타수가 0인 경우 출루율은 0으로 처리
	}
	return((player.hits + player.bb + player.hbp) / (player.ab + player.bb + player.hbp + player.sf)).toFixed(3);
}

// 장타율 계산 함수
function calculateSlg(player){
	if(player.ab === 0){
		return 0; // 타수가 0인 경우 장타율은 0으로 처리
	}
	return ((player.hits + 2 * player.double_hits + 3 * player.triple_hits + 4 * player.hr) / player.ab).toFixed(3);
}

// ops 계산 함수
function calculateOps(player){
	if(player.ab === 0){
		return 0; // 타수가 0인 경우 OPS는 0으로 처리
	}
	return (parseFloat(calculateObp(player)) + parseFloat(calculateSlg(player))).toFixed(3);
}

//투수 기준
// 평균자책점 계산 함수
function calculateEra(player){
	if(player.ip === 0){
		return 0; // 이닝이 0인 경우 평균자책점은 0으로 처리
	}
	return ((player.er * 9) / player.ip).toFixed(2);
}

// 삼진/볼넷비율 계산 함수
function calculateKbb(player){
	if(player.bb === 0){
		return player.k; // 볼넷이 0인 경우 삼진/볼넷비율은 삼진 수와 동일
	}
	return (player.k / player.bb).toFixed(2);
}

// 이닝당 출루허용률 계산 함수
function calculateWhip(player){
	if(player.ip === 0){
		return 0; // 이닝이 0인 경우 Whip은 0으로 처리
	}
	return ((player.hits + player.bb) / player.ip).toFixed(2);
}



