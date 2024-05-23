// 게임 기록 변경 이벤트 처리 함수
function is_onchange(_this, index){
	index = parseInt(index);
	let parent = $(_this).parent().parent(); 
	let all_inputs = parent.find('input'); // 모든 input요소 선택

	let is_valid = true; // 유효성 검사 위한 변수 초기화
	// 모든 input 요소에 대해 반복
	for(let i=0; i<all_inputs.length; i++){
		let input_value = parseFloat($(all_inputs[i]).val()); // 현재 반복 중인 input 요소의 값 가져오기
		// 값이 음수인 경우
		if(input_value < 0){
			alert("음수 값은 입력할 수 없습니다.");
			$(all_inputs[i]).val(0); // 해당 input 값을 0으로 설정
			return; // 함수 종료
		}
	}

	// 홈팀 타자 입력값 가져오기
	let h_b_player_sid = parent.find('select[key="home_batter_' + index + '_name"]').val(); // 선수명
	let h_b_pa = parent.find('input[key="home_batter_' + index + '_pa"]').val(); // 타석
	h_b_pa = parseInt(h_b_pa) || 0; // 정수로 변환 후, 값이 NaN이면 0으로 설정
	let h_b_hits_key = parent.find('input[key="home_batter_' + index + '_ab"]').val(); // 타수
	h_b_hits_key = parseInt(h_b_hits_key) || 0;
	let h_b_hits = parent.find('input[key="home_batter_' + index + '_hits"]').val(); // 안타 수
	h_b_hits = parseInt(h_b_hits) || 0;
	let h_b_double_hits = parent.find('input[key="home_batter_' + index + '_double_hits"]').val(); // 2루타 수
	h_b_double_hits = parseInt(h_b_double_hits) || 0;
	let h_b_triple_hits = parent.find('input[key="home_batter_' + index + '_triple_hits"]').val(); // 3루타 수
	h_b_triple_hits = parseInt(h_b_triple_hits) || 0;
	let h_b_hr = parent.find('input[key="home_batter_' + index + '_hr"]').val(); // 홈런 수
	h_b_hr = parseInt(h_b_hr) || 0;
	let h_b_r = parent.find('input[key="home_batter_' + index + '_r"]').val(); // 득점
	h_b_r = parseInt(h_b_r) || 0;
	let h_b_rbi = parent.find('input[key="home_batter_' + index + '_rbi"]').val(); // 타점
	h_b_rbi = parseInt(h_b_rbi) || 0;
	let h_b_so = parent.find('input[key="home_batter_' + index + '_so"]').val(); // 삼진
	h_b_so = parseInt(h_b_so) || 0;
	let h_b_bb = parent.find('input[key="home_batter_' + index + '_bb"]').val(); // 볼넷
	h_b_bb = parseInt(h_b_bb) || 0;
	let h_b_hbp = parent.find('input[key="home_batter_' + index + '_hbp"]').val(); // 사구
	h_b_hbp = parseInt(h_b_hbp) || 0;
	let h_b_gb = parent.find('input[key="home_batter_' + index + '_gb"]').val(); // 땅볼아웃
	h_b_gb = parseInt(h_b_gb) || 0;
	let h_b_fb = parent.find('input[key="home_batter_' + index + '_fb"]').val(); // 뜬공아웃
	h_b_fb = parseInt(h_b_fb) || 0;
	let h_b_sf = parent.find('input[key="home_batter_' + index + '_sf"]').val(); // 희생플라이
	h_b_sf = parseInt(h_b_sf) || 0;
	let h_b_sh = parent.find('input[key="home_batter_' + index + '_sh"]').val(); // 희생번트
	h_b_sh = parseInt(h_b_sh) || 0;
	let h_b_gdp = parent.find('input[key="home_batter_' + index + '_gdp"]').val(); // 병살타
	h_b_gdp = parseInt(h_b_gdp) || 0;
	let h_b_sb = parent.find('input[key="home_batter_' + index + '_sb"]').val(); // 도루
	h_b_sb = parseInt(h_b_sb) || 0;
	let h_b_error = parent.find('input[key="home_batter_' + index + '_error"]').val(); // 실책
	h_b_error = parseInt(h_b_error) || 0;
	let h_b_b_note = parent.find('input[key="home_batter_' + index + '_note"]').val(); // 노트

	//홈팀 투수 입력값 가져오기
	let h_p_player_sid = parent.find('select[key="home_pitcher_' + index + '_name"]').val(); // 선택된 선수명
	let h_p_win = parent.find('input[key="home_pitcher_' + index + '_win"]').val(); // 승
	h_p_win = parseInt(h_p_win) || 0; // 정수로 변환 후, 값이 NaN이면 0으로 설정
	let h_p_lose = parent.find('input[key="home_pitcher_' + index + '_lose"]').val(); // 패
	h_p_lose = parseInt(h_p_lose) || 0;
	let h_p_runs = parent.find('input[key="home_pitcher_' + index + '_r"]').val(); // 실점
	h_p_runs = parseInt(h_p_runs) || 0;
	let h_p_ip = parent.find('input[key="home_pitcher_' + index + '_ip"]').val(); // 이닝
	h_p_ip = parseFloat(h_p_ip) || 0;
	let h_p_cg = parent.find('input[key="home_pitcher_' + index + '_cg"]').val(); // 완투
	h_p_cg = parseInt(h_p_cg) || 0;
	let h_p_sho = parent.find('input[key="home_pitcher_' + index + '_sho"]').val(); // 완봉
	h_p_sho = parseInt(h_p_sho) || 0;
	let h_p_sv = parent.find('input[key="home_pitcher_' + index + '_sv"]').val(); // 세이브
	h_p_sv = parseInt(h_p_sv) || 0;
	let h_p_qs = parent.find('input[key="home_pitcher_' + index + '_qs"]').val(); // 퀄리티스타트
	h_p_qs = parseInt(h_p_qs) || 0;
	let h_p_pa = parent.find('input[key="home_pitcher_' + index + '_pa"]').val(); // 상대 타자 수
	h_p_pa = parseInt(h_p_pa) || 0;
	let h_p_gb = parent.find('input[key="home_pitcher_' + index + '_gb"]').val(); // 땅볼
	h_p_gb = parseInt(h_p_gb) || 0;
	let h_p_fb = parent.find('input[key="home_pitcher_' + index + '_fb"]').val(); // 뜬공
	h_p_fb = parseInt(h_p_fb) || 0;
	let h_p_iffb = parent.find('input[key="home_pitcher_' + index + '_iffb"]').val(); // 내야 뜬공
	h_p_iffb = parseInt(h_p_iffb) || 0;
	let h_p_ifh = parent.find('input[key="home_pitcher_' + index + '_ifh"]').val(); // 내야 안타
	h_p_ifh = parseInt(h_p_ifh) || 0;
	let h_p_hr = parent.find('input[key="home_pitcher_' + index + '_hr"]').val(); // 홈런 허용 수
	h_p_hr = parseInt(h_p_hr) || 0;
	let h_p_k = parent.find('input[key="home_pitcher_' + index + '_k"]').val(); // 삼진
	h_p_k = parseInt(h_p_k) || 0;
	let h_p_bb = parent.find('input[key="home_pitcher_' + index + '_bb"]').val(); // 볼넷
	h_p_bb = parseInt(h_p_bb) || 0;
	let h_p_pe = parent.find('input[key="home_pitcher_' + index + '_error"]').val(); // 실책
	h_p_pe = parseInt(h_p_pe) || 0;
	let h_p_hits = parent.find('input[key="home_pitcher_' + index + '_hits"]').val(); // 피안타 수
	h_p_hits = parseInt(h_p_hits) || 0;
	let h_p_pitches = parent.find('input[key="home_pitcher_' + index + '_pitches"]').val(); // 투구 수
	h_p_pitches = parseInt(h_p_pitches) || 0;
	let h_p_ibb = parent.find('input[key="home_pitcher_' + index + '_ibb"]').val(); // 고의사구
	h_p_ibb = parseInt(h_p_ibb) || 0;
	let h_p_er = parent.find('input[key="home_pitcher_' + index + '_er"]').val(); // 자책점
	h_p_er = parseInt(h_p_er) || 0;
	let h_p_sf = parent.find('input[key="home_pitcher_' + index + '_sf"]').val(); // 희생플라이
	h_p_sf = parseInt(h_p_sf) || 0;
	let h_p_hbp = parent.find('input[key="home_pitcher_' + index + '_hbp"]').val(); // 사사구
	h_p_hbp = parseInt(h_p_hbp) || 0;
	let h_p_note = parent.find('input[key="home_pitcher_' + index + '_note"]').val(); // 비고


	// 어웨이팀 타자 입력값 가져오기
	let a_b_player_sid = parent.find('select[key="away_batter_' + index + '_name"]').val();
	let a_b_pa = parent.find('input[key="home_batter_' + index + '_pa"]').val(); // 타석
	a_b_pa = parseInt(a_b_pa) || 0; // 정수로 변환 후, 값이 NaN이면 0으로 설정
	let a_b_hits_key = parent.find('input[key="away_batter_' + index + '_ab"]').val(); // 타수
	a_b_hits_key = parseInt(a_b_hits_key) || 0;
	let a_b_hits = parent.find('input[key="away_batter_' + index + '_hits"]').val(); // 안타 수
	a_b_hits = parseInt(a_b_hits) || 0;
	let a_b_double_hits = parent.find('input[key="away_batter_' + index + '_double_hits"]').val(); // 2루타 수
	a_b_double_hits = parseInt(a_b_double_hits) || 0;
	let a_b_triple_hits = parent.find('input[key="away_batter_' + index + '_triple_hits"]').val(); // 3루타 수
	a_b_triple_hits = parseInt(a_b_triple_hits) || 0;
	let a_b_hr = parent.find('input[key="away_batter_' + index + '_hr"]').val(); // 홈런 수
	a_b_hr = parseInt(a_b_hr) || 0;
	let a_b_r = parent.find('input[key="away_batter_' + index + '_r"]').val(); // 득점
	a_b_r = parseInt(a_b_r) || 0;
	let a_b_rbi = parent.find('input[key="away_batter_' + index + '_rbi"]').val(); // 타점
	a_b_rbi = parseInt(a_b_rbi) || 0;
	let a_b_so = parent.find('input[key="away_batter_' + index + '_so"]').val(); // 삼진
	a_b_so = parseInt(a_b_so) || 0;
	let a_b_bb = parent.find('input[key="away_batter_' + index + '_bb"]').val(); // 볼넷
	a_b_bb = parseInt(a_b_bb) || 0;
	let a_b_hbp = parent.find('input[key="away_batter_' + index + '_hbp"]').val(); // 사구
	a_b_hbp = parseInt(a_b_hbp) || 0;
	let a_b_gb = parent.find('input[key="away_batter_' + index + '_gb"]').val(); // 땅볼아웃
	a_b_gb = parseInt(a_b_gb) || 0;
	let a_b_fb = parent.find('input[key="away_batter_' + index + '_fb"]').val(); // 뜬공아웃
	a_b_fb = parseInt(a_b_fb) || 0;
	let a_b_sf = parent.find('input[key="away_batter_' + index + '_sf"]').val(); // 희생플라이
	a_b_sf = parseInt(a_b_sf) || 0;
	let a_b_sh = parent.find('input[key="away_batter_' + index + '_sh"]').val(); // 희생번트
	a_b_sh = parseInt(a_b_sh) || 0;
	let a_b_gdp = parent.find('input[key="away_batter_' + index + '_gdp"]').val(); // 병살타
	a_b_gdp = parseInt(a_b_gdp) || 0;
	let a_b_sb = parent.find('input[key="away_batter_' + index + '_sb"]').val(); // 도루
	a_b_sb = parseInt(a_b_sb) || 0;
	let a_b_error = parent.find('input[key="away_batter_' + index + '_error"]').val(); // 실책
	a_b_error = parseInt(a_b_error) || 0;
	let a_b_note = parent.find('input[key="away_batter_' + index + '_note"]').val(); // 비고

	//어웨이팀 투수 입력값 가져오기
	let a_p_player_sid = parent.find('select[key="away_pitcher_' + index + '_name"]').val(); // 선택된 선수명
	let a_p_win = parent.find('input[key="away_pitcher_' + index + '_win"]').val(); // 승
	a_p_win = parseInt(a_p_win) || 0; // 정수로 변환 후, 값이 NaN이면 0으로 설정
	let a_p_lose = parent.find('input[key="away_pitcher_' + index + '_lose"]').val(); // 패
	a_p_lose = parseInt(a_p_lose) || 0;
	let a_p_runs = parent.find('input[key="away_pitcher_' + index + '_r"]').val(); // 실점
	a_p_runs = parseInt(a_p_runs) || 0;
	let a_p_ip = parent.find('input[key="away_pitcher_' + index + '_ip"]').val(); // 이닝
	a_p_ip = parseFloat(a_p_ip) || 0;
	let a_p_cg = parent.find('input[key="away_pitcher_' + index + '_cg"]').val(); // 완투
	a_p_cg = parseInt(a_p_cg) || 0;
	let a_p_sho = parent.find('input[key="away_pitcher_' + index + '_sho"]').val(); // 완봉
	a_p_sho = parseInt(a_p_sho) || 0;
	let a_p_sv = parent.find('input[key="away_pitcher_' + index + '_sv"]').val(); // 세이브
	a_p_sv = parseInt(a_p_sv) || 0;
	let a_p_qs = parent.find('input[key="away_pitcher_' + index + '_qs"]').val(); // 퀄리티스타트
	a_p_qs = parseInt(a_p_qs) || 0;
	let a_p_pa = parent.find('input[key="away_pitcher_' + index + '_pa"]').val(); // 상대 타자 수
	a_p_pa = parseInt(a_p_pa) || 0;
	let a_p_gb = parent.find('input[key="away_pitcher_' + index + '_gb"]').val(); // 땅볼
	a_p_gb = parseInt(a_p_gb) || 0;
	let a_p_fb = parent.find('input[key="away_pitcher_' + index + '_fb"]').val(); // 뜬공
	a_p_fb = parseInt(a_p_fb) || 0;
	let a_p_iffb = parent.find('input[key="away_pitcher_' + index + '_iffb"]').val(); // 내야 뜬공
	a_p_iffb = parseInt(a_p_iffb) || 0;
	let a_p_ifh = parent.find('input[key="away_pitcher_' + index + '_ifh"]').val(); // 내야 안타
	a_p_ifh = parseInt(a_p_ifh) || 0;
	let a_p_hr = parent.find('input[key="away_pitcher_' + index + '_hr"]').val(); // 홈런 허용 수
	a_p_hr = parseInt(a_p_hr) || 0;
	let a_p_k = parent.find('input[key="away_pitcher_' + index + '_k"]').val(); // 삼진
	a_p_k = parseInt(a_p_k) || 0;
	let a_p_bb = parent.find('input[key="away_pitcher_' + index + '_bb"]').val(); // 볼넷
	a_p_bb = parseInt(a_p_bb) || 0;
	let a_p_pe = parent.find('input[key="away_pitcher_' + index + '_error"]').val(); // 실책
	a_p_pe = parseInt(a_p_pe) || 0;
	let a_p_hits = parent.find('input[key="away_pitcher_' + index + '_hits"]').val(); // 피안타 수
	a_p_hits = parseInt(a_p_hits) || 0;
	let a_p_pitches = parent.find('input[key="away_pitcher_' + index + '_pitches"]').val(); // 투구 수
	a_p_pitches = parseInt(a_p_pitches) || 0;
	let a_p_ibb = parent.find('input[key="away_pitcher_' + index + '_ibb"]').val(); // 고의사구
	a_p_ibb = parseInt(a_p_ibb) || 0;
	let a_p_er = parent.find('input[key="away_pitcher_' + index + '_er"]').val(); // 자책점
	a_p_er = parseInt(a_p_er) || 0;
	let a_p_sf = parent.find('input[key="away_pitcher_' + index + '_sf"]').val(); // 희생플라이
	a_p_sf = parseInt(a_p_sf) || 0;
	let a_p_hbp = parent.find('input[key="away_pitcher_' + index + '_hbp"]').val(); // 사사구
	a_p_hbp = parseInt(a_p_hbp) || 0;
	let a_p_note = parent.find('input[key="away_pitcher_' + index + '_note"]').val(); // 비고

}

//게임 등록 함수
function registration(){
	let parent = $(event.target).parent().parent(); 

	// 전체 홈팀 타석 수 초기화
	let total_h_b_pa = 0;
	// 홈팀 타자 배열 순회
	for(let i=0; i<home_batters.length; i++){
		// 현재 타자의 타석 수 가져오기
		let batter_pa = parseInt(parent.find('input[key="home_batter_' + i + '_pa"]').val());

		// 만약 값이 숫자라면 합산
		if(isNaN(batter_pa) === false){
			total_h_b_pa += batter_pa;
		}
	}
	// 전체 홈팀 상대타자 수 초기화
	let total_h_p_pa = 0;
	// 홈팀 타자 배열 순회
	for(let i=0; i<home_pitchers.length; i++){
		// 현재 타자의 타석 수 가져오기
		let pitcher_pa = parseInt(parent.find('input[key="home_pitcher_' + i + '_pa"]').val());

		// 만약 값이 숫자라면 합산
		if(isNaN(pitcher_pa) === false){
			total_h_p_pa += pitcher_pa;
		}
	}
	// 전체 어웨이팀 타석 수 초기화
	let total_a_b_pa = 0;
	// 어웨이팀 타자 배열 순회
	for(let i=0; i<away_batters.length; i++){
		// 현재 타자의 타석 수 가져오기
		let batter_pa = parseInt(parent.find('input[key="away_batter_' + i + '_pa"]').val());

		// 만약 값이 숫자라면 합산
		if(isNaN(batter_pa) === false){
			total_a_b_pa += batter_pa;
		}
	}
	// 전체 어웨이팀 상대타자 수 초기화
	let total_a_p_pa = 0;
	// 홈팀 타자 배열 순회
	for(let i=0; i<away_pitchers.length; i++){
		// 현재 타자의 타석 수 가져오기
		let pitcher_pa = parseInt(parent.find('input[key="away_pitcher_' + i + '_pa"]').val());

		// 만약 값이 숫자라면 합산
		if(isNaN(pitcher_pa) === false){
			total_a_p_pa += pitcher_pa;
		}
	}

	//전체 타자의 타석과 투수가 상대한 타자의 수가 다른지 유효성 검사
	if(total_h_b_pa !== 0 && total_a_p_pa !== 0){
		// 홈팀 타석 수와 어웨이팀 상대 투수 타석 수 비교
		if(parseInt(total_h_b_pa) !== parseInt(total_a_p_pa)){
			alert("홈팀 타석 수와 어웨이팀 상대 투수 타석 수가 일치하지 않습니다.");
		}
		return null;
	}
	// 값이 모두 채워졌는지 확인
	if(total_a_b_pa !== 0 && total_h_p_pa !== 0){
		// 어웨이팀 타석 수와 홈팀 상대 투수 타석 수 비교
		if(parseInt(total_a_b_pa) !== parseInt(total_h_p_pa)){
			alert("어웨이팀 타석 수와 홈팀 상대 투수 타석 수가 일치하지 않습니다.");
		}
		return null;
	}

	let senddata = new Object();

	// 홈팀 타자 데이터 추가
	senddata.home_batters = [];
	for(let i=0; i<home_batters.length; i++){
		let select_element = document.querySelector('select[key="home_batter_' + i + '_name"]');
		let selected_option = select_element ? select_element.options[select_element.selectedIndex] : null;

		if(selected_option && selected_option.value !== ""){ 
			let batter = {
				player_sid: parent.find('select[key="home_batter_' + i + '_name"]').val(),
				pa: parent.find('input[key="home_batter_' + i + '_pa"]').val() || 0,
				ab: parent.find('input[key="home_batter_' + i + '_ab"]').val() || 0,
				hits: parent.find('input[key="home_batter_' + i + '_hits"]').val() || 0,
				double_hits: parent.find('input[key="home_batter_' + i + '_double_hits"]').val() || 0,
				triple_hits: parent.find('input[key="home_batter_' + i + '_triple_hits"]').val() || 0,
				hr: parent.find('input[key="home_batter_' + i + '_hr"]').val() || 0,
				r: parent.find('input[key="home_batter_' + i + '_r"]').val() || 0,
				rbi: parent.find('input[key="home_batter_' + i + '_rbi"]').val() || 0,
				so: parent.find('input[key="home_batter_' + i + '_so"]').val() || 0,
				bb: parent.find('input[key="home_batter_' + i + '_bb"]').val() || 0,
				hbp: parent.find('input[key="home_batter_' + i + '_hbp"]').val() || 0,
				gb: parent.find('input[key="home_batter_' + i + '_gb"]').val() || 0,
				fb: parent.find('input[key="home_batter_' + i + '_fb"]').val() || 0,
				sf: parent.find('input[key="home_batter_' + i + '_sf"]').val() || 0,
				sh: parent.find('input[key="home_batter_' + i + '_sh"]').val() || 0,
				gdp: parent.find('input[key="home_batter_' + i + '_gdp"]').val() || 0,
				k: parent.find('input[key="home_batter_' + i + '_k"]').val() || 0,
				sb: parent.find('input[key="home_batter_' + i + '_sb"]').val() || 0,
				error: parent.find('input[key="home_batter_' + i + '_error"]').val() || 0,
				note: parent.find('input[key="home_batter_' + i + '_note"]').val()
			};
			senddata.home_batters.push(batter);
		}
	}

	// 홈팀 투수 데이터 추가
	senddata.home_pitchers = [];
	for(let i=0; i<home_pitchers.length; i++){
		let select_element = document.querySelector('select[key="away_pitcher_' + i + '_name"]');
		let selected_option = select_element ? select_element.options[select_element.selectedIndex] : null;

		if(selected_option && selected_option.value !== ""){ 
			let pitcher = {
				player_sid: parent.find('select[key="home_pitcher_' + i + '_name"]').val(),
				win: parent.find('input[key="home_pitcher_' + i + '_win"]').val() || 0,
				lose: parent.find('input[key="home_pitcher_' + i + '_lose"]').val() || 0,
				r: parent.find('input[key="home_pitcher_' + i + '_r"]').val() || 0,
				ip: parent.find('input[key="home_pitcher_' + i + '_ip"]').val() || 0,
				cg: parent.find('input[key="home_pitcher_' + i + '_cg"]').val() || 0,
				sho: parent.find('input[key="home_pitcher_' + i + '_sho"]').val() || 0,
				sv: parent.find('input[key="home_pitcher_' + i + '_sv"]').val() || 0,
				qs: parent.find('input[key="home_pitcher_' + i + '_qs"]').val() || 0,
				pa: parent.find('input[key="home_pitcher_' + i + '_pa"]').val() || 0,
				gb: parent.find('input[key="home_pitcher_' + i + '_gb"]').val() || 0,
				fb: parent.find('input[key="home_pitcher_' + i + '_fb"]').val() || 0,
				iffb: parent.find('input[key="home_pitcher_' + i + '_iffb"]').val() || 0,
				ifh: parent.find('input[key="home_pitcher_' + i + '_ifh"]').val() || 0,
				hr: parent.find('input[key="home_pitcher_' + i + '_hr"]').val() || 0,
				k: parent.find('input[key="home_pitcher_' + i + '_k"]').val() || 0,
				bb: parent.find('input[key="home_pitcher_' + i + '_bb"]').val() || 0,
				error: parent.find('input[key="home_pitcher_' + i + '_error"]').val() || 0,
				hits: parent.find('input[key="home_pitcher_' + i + '_hits"]').val() || 0,
				pitches: parent.find('input[key="home_pitcher_' + i + '_pitches"]').val() || 0,
				ibb: parent.find('input[key="home_pitcher_' + i + '_ibb"]').val() || 0,
				er: parent.find('input[key="home_pitcher_' + i + '_er"]').val() || 0,
				sf: parent.find('input[key="home_pitcher_' + i + '_sf"]').val() || 0,
				hbp: parent.find('input[key="home_pitcher_' + i + '_hbp"]').val() || 0,
				note: parent.find('input[key="home_pitcher_' + i + '_note"]').val()
			};
			senddata.home_pitchers.push(pitcher);
		}
	}

	// 어웨이팀 타자 데이터 추가
	senddata.away_batters = [];
	for(let i=0; i<away_batters.length; i++){
		let select_element = document.querySelector('select[key="away_batter_' + i + '_name"]');
		let selected_option = select_element ? select_element.options[select_element.selectedIndex] : null;

		if(selected_option && selected_option.value !== ""){ 
			let batter = {
				player_sid: parent.find('select[key="away_batter_' + i + '_name"]').val(),
				pa: parent.find('input[key="away_batter_' + i + '_pa"]').val() || 0,
				ab: parent.find('input[key="away_batter_' + i + '_ab"]').val() || 0,
				hits: parent.find('input[key="away_batter_' + i + '_hits"]').val() || 0,
				double_hits: parent.find('input[key="away_batter_' + i + '_double_hits"]').val() || 0,
				triple_hits: parent.find('input[key="away_batter_' + i + '_triple_hits"]').val() || 0,
				hr: parent.find('input[key="away_batter_' + i + '_hr"]').val() || 0,
				r: parent.find('input[key="away_batter_' + i + '_r"]').val() || 0,
				rbi: parent.find('input[key="away_batter_' + i + '_rbi"]').val() || 0,
				so: parent.find('input[key="away_batter_' + i + '_so"]').val() || 0,
				bb: parent.find('input[key="away_batter_' + i + '_bb"]').val() || 0,
				hbp: parent.find('input[key="away_batter_' + i + '_hbp"]').val() || 0,
				gb: parent.find('input[key="away_batter_' + i + '_gb"]').val() || 0,
				fb: parent.find('input[key="away_batter_' + i + '_fb"]').val() || 0,
				sf: parent.find('input[key="away_batter_' + i + '_sf"]').val() || 0,
				sh: parent.find('input[key="away_batter_' + i + '_sh"]').val() || 0,
				gdp: parent.find('input[key="away_batter_' + i + '_gdp"]').val() || 0,
				k: parent.find('input[key="away_batter_' + i + '_k"]').val() || 0,
				sb: parent.find('input[key="away_batter_' + i + '_sb"]').val() || 0,
				error: parent.find('input[key="away_batter_' + i + '_error"]').val() || 0,
				note: parent.find('input[key="away_batter_' + i + '_note"]').val()
			};
			senddata.away_batters.push(batter);
		}
	}

	// 어웨이팀 투수 데이터 추가
	senddata.away_pitchers = [];
	for(let i=0; i<away_pitchers.length; i++){
		let select_element = document.querySelector('select[key="away_pitcher_' + i + '_name"]');
		let selected_option = select_element ? select_element.options[select_element.selectedIndex] : null;

		if(selected_option && selected_option.value !== ""){ 
			let pitcher = {
				player_sid: parent.find('select[key="away_pitcher_' + i + '_name"]').val(),
				win: parent.find('input[key="away_pitcher_' + i + '_win"]').val() || 0,
				lose: parent.find('input[key="away_pitcher_' + i + '_lose"]').val() || 0,
				r: parent.find('input[key="away_pitcher_' + i + '_r"]').val() || 0,
				ip: parent.find('input[key="away_pitcher_' + i + '_ip"]').val() || 0,
				cg: parent.find('input[key="away_pitcher_' + i + '_cg"]').val() || 0,
				sho: parent.find('input[key="away_pitcher_' + i + '_sho"]').val() || 0,
				sv: parent.find('input[key="away_pitcher_' + i + '_sv"]').val() || 0,
				qs: parent.find('input[key="away_pitcher_' + i + '_qs"]').val() || 0,
				pa: parent.find('input[key="away_pitcher_' + i + '_pa"]').val() || 0,
				gb: parent.find('input[key="away_pitcher_' + i + '_gb"]').val() || 0,
				fb: parent.find('input[key="away_pitcher_' + i + '_fb"]').val() || 0,
				iffb: parent.find('input[key="away_pitcher_' + i + '_iffb"]').val() || 0,
				ifh: parent.find('input[key="away_pitcher_' + i + '_ifh"]').val() || 0,
				hr: parent.find('input[key="away_pitcher_' + i + '_hr"]').val() || 0,
				k: parent.find('input[key="away_pitcher_' + i + '_k"]').val() || 0,
				bb: parent.find('input[key="away_pitcher_' + i + '_bb"]').val() || 0,
				error: parent.find('input[key="away_pitcher_' + i + '_error"]').val() || 0,
				hits: parent.find('input[key="away_pitcher_' + i + '_hits"]').val() || 0,
				pitches: parent.find('input[key="away_pitcher_' + i + '_pitches"]').val() || 0,
				ibb: parent.find('input[key="away_pitcher_' + i + '_ibb"]').val() || 0,
				er: parent.find('input[key="away_pitcher_' + i + '_er"]').val() || 0,
				sf: parent.find('input[key="away_pitcher_' + i + '_sf"]').val() || 0,
				hbp: parent.find('input[key="away_pitcher_' + i + '_hbp"]').val() || 0,
				note: parent.find('input[key="away_pitcher_' + i + '_note"]').val()
			};
			senddata.away_pitchers.push(pitcher);
		}
	}
	
	//게임 날짜 추가
	senddata.game_date = game_date;
	senddata.game_sid = game_sid;
	senddata.home_team_sid = home_team_sid;
	senddata.away_team_sid = away_team_sid;
	senddata.league_sid = league_sid;

	// api 호출
	api('api_player_record_insert', senddata, function(output){
		if(output.is_success){
			render('record/recent_matches');
		}else{
			alert(output.msg);
		}
	});
}