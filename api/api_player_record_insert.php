<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//경기 기록 데이터 유효성 검사
	if(isset($_POST['home_batters']) === false){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	if($_POST['home_batters'] === '' || $_POST['home_batters'] === null){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	$home_batters = $_POST['home_batters'];

		//경기 기록 데이터 유효성 검사
	if(isset($_POST['home_pitchers']) === false){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	if($_POST['home_pitchers'] === '' || $_POST['home_pitchers'] === null){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	$home_pitchers = $_POST['home_pitchers'];

	//경기 기록 데이터 유효성 검사
	if(isset($_POST['away_batters']) === false){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	if($_POST['away_batters'] === '' || $_POST['away_batters'] === null){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	$away_batters = $_POST['away_batters'];

	//경기 기록 데이터 유효성 검사
	if(isset($_POST['away_pitchers']) === false){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	if($_POST['away_pitchers'] === '' || $_POST['away_pitchers'] === null){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	$away_pitchers = $_POST['away_pitchers'];

	//경기 날짜 유효성 검사
	if(isset($_POST['game_date']) === false){
		nowexit(false,'필수 데이터가 누락되었습니다.');
	}
	if($_POST['game_date'] === '' || $_POST['game_date'] === null){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	$game_date = $_POST['game_date'];
	$year = date('Y', strtotime($game_date));

	//경기 sid 유효성 검사
	if(isset($_POST['game_sid']) === false){
		nowexit(false,'필수 데이터가 누락되었습니다.');
	}
	if($_POST['game_sid'] === '' || $_POST['game_sid'] === null){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	$game_sid = $_POST['game_sid'];

	//홈팀 sid 유효성 검사
	if(isset($_POST['home_team_sid']) === false){
		nowexit(false,'필수 데이터가 누락되었습니다.');
	}
	if($_POST['home_team_sid'] === '' || $_POST['home_team_sid'] === null){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	$home_team_sid = $_POST['home_team_sid'];

	//어웨이팀 sid 유효성 검사
	if(isset($_POST['away_team_sid']) === false){
		nowexit(false,'필수 데이터가 누락되었습니다.');
	}
	if($_POST['away_team_sid'] === '' || $_POST['away_team_sid'] === null){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	$away_team_sid = $_POST['away_team_sid'];

	//리그 sid 유효성 검사
	if(isset($_POST['league_sid']) === false){
		nowexit(false,'필수 데이터가 누락되었습니다.');
	}
	if($_POST['league_sid'] === '' || $_POST['league_sid'] === null){
		nowexit(false, '필수 데이터가 누락되었습니다.');
	}
	$league_sid = $_POST['league_sid'];

	///////////경기 등록 /////////
	$sql = "SELECT sid FROM batter_game_stat WHERE game_date='$game_date';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);
	//경기가 있는지 확인
	if($query_result['output_cnt'] > 0){
		nowexit(false, '중복된 게임 입니다.');
	}
	$sql = "SELECT sid FROM pitcher_game_stat WHERE game_date='$game_date';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);
	//경기가 있는지 확인
	if($query_result['output_cnt'] > 0){
		nowexit(false, '중복된 게임 입니다.');
	}

	// 득점 정보 및 투수, 타자 데이터 초기화
	$home_team_score = 0;
	$away_team_score = 0;

	$home_pitchers_er = 0;
	$home_pitchers_bb = 0;
	$home_pitchers_k = 0;
	$home_pitchers_sv = 0;
	$home_pitchers_ip = 0;
	$home_pitchers_error = 0;
	$home_batters_sb = 0;
	$home_batters_hr = 0;
	$home_batters_triple_hits = 0;
	$home_batters_double_hits = 0;
	$home_batters_hits = 0;
	$home_batters_ab = 0;
	$home_batters_hbp = 0;

	$away_pitchers_er = 0;
	$away_pitchers_bb = 0;
	$away_pitchers_k = 0;
	$away_pitchers_sv = 0;
	$away_pitchers_ip = 0;
	$away_pitchers_error = 0;
	$away_batters_sb = 0;
	$away_batters_hr = 0;
	$away_batters_triple_hits = 0;
	$away_batters_double_hits = 0;
	$away_batters_hits = 0;
	$away_batters_ab = 0;
	$away_batters_hbp = 0;

	// 홈팀 타자 데이터 삽입
	for($i=0; $i<count($home_batters); $i++){
		$batter = $home_batters[$i];
		$home_team_score += (int)$batter['r'];
		$home_batters_sb += (int)$batter['sb'];
		$home_batters_hr += (int)$batter['hr'];
		$home_batters_triple_hits += (int)$batter['triple_hits'];
		$home_batters_double_hits += (int)$batter['double_hits'];
		$home_batters_hits += (int)$batter['hits'];
		$home_batters_ab += (int)$batter['ab'];
		$home_batters_hbp += (int)$batter['hbp'];
	}
	//홈팀 투수 데이터 삽입
	for($i=0; $i<count($home_pitchers); $i++){
		$pitcher = $home_pitchers[$i];
		$home_pitchers_er += (int)$pitcher['er'];
		$home_pitchers_bb += (int)$pitcher['bb'];
		$home_pitchers_k += (int)$pitcher['k'];
		$home_pitchers_sv += (int)$pitcher['sv'];
		$home_pitchers_ip += (int)$pitcher['ip'];
		$home_pitchers_error += (int)$pitcher['error'];
	}
	//어웨이팀 타자 데이터 삽입
	for($i=0; $i<count($away_batters); $i++){
		$batter = $away_batters[$i];
		$away_team_score += (int)$batter['r'];
		$away_batters_sb += (int)$batter['sb'];
		$away_batters_hr += (int)$batter['hr'];
		$away_batters_triple_hits += (int)$batter['triple_hits'];
		$away_batters_double_hits += (int)$batter['double_hits'];
		$away_batters_hits += (int)$batter['hits'];
		$away_batters_ab += (int)$batter['ab'];
		$away_batters_hbp += (int)$batter['hbp'];
	}
	//어웨이팀 투수 데이터 삽입
	for($i=0; $i<count($away_pitchers); $i++){
		$pitcher = $away_pitchers[$i];
		$away_pitchers_er += (int)$pitcher['er'];
		$away_pitchers_bb += (int)$pitcher['bb'];
		$away_pitchers_k += (int)$pitcher['k'];
		$away_pitchers_sv += (int)$pitcher['sv'];
		$away_pitchers_ip += (int)$pitcher['ip'];
		$away_pitchers_error += (int)$pitcher['error'];
	}

	//홈팀 승리 여부 확인
	$home_team_win = ($home_team_score > $away_team_score) ? 1 : 0; 
	// 홈팀 무승부 여부 확인
	$home_team_draw = ($home_team_score == $away_team_score) ? 1 : 0;
	//홈팀 패배 여부 확인
	$home_team_lose = ($home_team_score < $away_team_score) ? 1 : 0; 

	// 어웨이팀 승리 여부 확인
	$away_team_win = ($home_team_score < $away_team_score) ? 1 : 0;
	// 어웨이팀 무승부 여부 확인
	$away_team_draw = ($home_team_score == $away_team_score) ? 1 : 0;
	// 어웨이팀 패배 여부 확인
	$away_team_lose = ($home_team_score > $away_team_score) ? 1 : 0; 

	// 홈 팀 정보 조회
	$sql = "SELECT sid FROM season_team_info WHERE team_sid = '$home_team_sid' AND season = '$year';";
	$query_result = sql($sql);
	$season_query = select_process($query_result);

	if($season_query['output_cnt'] > 0){
	    // 기존 팀 정보가 있을 경우, 해당 팀의 데이터를 누적하여 업데이트
	    $sql = "UPDATE season_team_info 
	            SET game = game + 1, 
	                win = win + $home_team_win, 
	                lose = lose + $home_team_lose, 
	                draw = draw + $home_team_draw, 
	                ab = ab + $home_batters_ab, 
	                hits = hits + $home_batters_hits, 
	                double_hits = double_hits + $home_batters_double_hits, 
	                triple_hits = triple_hits + $home_batters_triple_hits, 
	                hr = hr + $home_batters_hr, 
	                r = r + $home_team_score, 
	                sb = sb + $home_batters_sb, 
	                error = error + $home_pitchers_error, 
	                ip = ip + $home_pitchers_ip, 
	                sv = sv + $home_pitchers_sv, 
	                k = k + $home_pitchers_k, 
	                bb = bb + $home_pitchers_bb, 
	                er = er + $home_pitchers_er 
	            WHERE team_sid = $home_team_sid AND season = '$year';";
	} else {
	    // 기존 팀 정보가 없을 경우, 새로운 데이터 INSERT
	    $sql = "INSERT INTO season_team_info (team_sid, season, league_sid, game, win, lose, draw, ab, hits, double_hits, triple_hits, hr, r, sb, error, ip, sv, k, bb, er) 
	            VALUES ('$home_team_sid', '$year', '$league_sid', 1, $home_team_win, $home_team_lose, $home_team_draw, $home_batters_ab, $home_batters_hits, $home_batters_double_hits, $home_batters_triple_hits, $home_batters_hr, $home_team_score, $home_batters_sb, $home_pitchers_error, $home_pitchers_ip, $home_pitchers_sv, $home_pitchers_k, $home_pitchers_bb, $home_pitchers_er);";
	}
	$query_result = sql($sql);
	if(is_bool($query_result) === false){
	    nowexit(false, '팀 정보 등록에 실패했습니다.');
	}
	if($query_result === false){
	    nowexit(false, '팀 정보 등록에 실패했습니다.');
	}

	// 원정 팀 정보 조회
	$sql = "SELECT sid FROM season_team_info WHERE team_sid = '$away_team_sid' AND season = '$year';";
	$query_result = sql($sql);
	$season_query = select_process($query_result);

	if($season_query['output_cnt'] > 0){
	    // 기존 팀 정보가 있을 경우, 해당 팀의 데이터를 누적하여 업데이트
	    $sql = "UPDATE season_team_info 
	            SET game = game + 1, 
	                win = win + $away_team_win, 
	                lose = lose + $away_team_lose, 
	                draw = draw + $away_team_draw, 
	                ab = ab + $away_batters_ab, 
	                hits = hits + $away_batters_hits, 
	                double_hits = double_hits + $away_batters_double_hits, 
	                triple_hits = triple_hits + $away_batters_triple_hits, 
	                hr = hr + $away_batters_hr, 
	                r = r + $away_team_score, 
	                sb = sb + $away_batters_sb, 
	                error = error + $away_pitchers_error, 
	                ip = ip + $away_pitchers_ip, 
	                sv = sv + $away_pitchers_sv, 
	                k = k + $away_pitchers_k, 
	                bb = bb + $away_pitchers_bb, 
	                er = er + $away_pitchers_er 
	            WHERE team_sid = $away_team_sid AND season = '$year';";
	} else {
	    // 기존 팀 정보가 없을 경우, 새로운 데이터 INSERT
	    $sql = "INSERT INTO season_team_info (team_sid, season, league_sid, game, win, lose, draw, ab, hits, double_hits, triple_hits, hr, r, sb, error, ip, sv, k, bb, er) 
	            VALUES ('$away_team_sid', '$year', '$league_sid', 1, $away_team_win, $away_team_lose, $away_team_draw, $away_batters_ab, $away_batters_hits, $away_batters_double_hits, $away_batters_triple_hits, $away_batters_hr, $away_team_score, $away_batters_sb, $away_pitchers_error, $away_pitchers_ip, $away_pitchers_sv, $away_pitchers_k, $away_pitchers_bb, $away_pitchers_er);";
	}
	$query_result = sql($sql);
	if(is_bool($query_result) === false){
	    nowexit(false, '팀 정보 등록에 실패했습니다.');
	}
	if($query_result === false){
	    nowexit(false, '팀 정보 등록에 실패했습니다.');
	}

	// 홈팀 타자 데이터 삽입
	for($i=0; $i<count($home_batters); $i++){
		$batter = $home_batters[$i];

		// 타자 정보를 데이터베이스에 삽입하는 SQL 쿼리
		$sql = "INSERT INTO batter_game_stat (game_sid, player_sid, game_date, home_away, pa, ab, hits, double_hits, triple_hits, hr, r, rbi, so, bb, hbp, gb, fb, sf, sh, gdp, sb, error, note) 
		VALUES ($game_sid, '{$batter['player_sid']}', '$game_date', 0, {$batter['pa']}, {$batter['ab']}, {$batter['hits']}, {$batter['double_hits']}, {$batter['triple_hits']}, {$batter['hr']}, {$batter['r']}, {$batter['rbi']}, {$batter['so']}, {$batter['bb']}, {$batter['hbp']}, {$batter['gb']}, {$batter['fb']}, {$batter['sf']}, {$batter['sh']}, {$batter['gdp']}, {$batter['sb']}, {$batter['error']}, '{$batter['note']}' )";

		$query_result = sql($sql);

		if(is_bool($query_result) === false){
			nowexit(false, '기록 등록에 실패했습니다.');
		}
		if($query_result === false){
			nowexit(false, '기록 등록에 실패했습니다.');
		}
	}
	//홈팀 투수 데이터 삽입
	for($i=0; $i<count($home_pitchers); $i++){
		$pitcher = $home_pitchers[$i];

		// 투수 정보를 데이터베이스에 삽입하는 SQL 쿼리
		$sql = "INSERT INTO pitcher_game_stat (game_sid, player_sid, game_date, home_away, win, lose, r, er, pitches, ip, cg, sho, sv, qs, pa, gb, fb, iffb, ifh, hr, k, bb, error, hits, ibb, sf, hbp, note) VALUES ('$game_sid', '{$pitcher['player_sid']}', '$game_date', 0, {$pitcher['win']}, {$pitcher['lose']}, {$pitcher['r']}, {$pitcher['er']}, {$pitcher['pitches']}, {$pitcher['ip']}, {$pitcher['cg']}, {$pitcher['sho']}, {$pitcher['sv']}, {$pitcher['qs']}, {$pitcher['pa']}, {$pitcher['gb']}, {$pitcher['fb']}, {$pitcher['iffb']}, {$pitcher['ifh']}, {$pitcher['hr']}, {$pitcher['k']}, {$pitcher['bb']}, {$pitcher['error']}, {$pitcher['hits']}, {$pitcher['ibb']}, {$pitcher['sf']}, {$pitcher['hbp']}, '{$pitcher['note']}')";

		$query_result = sql($sql);

		if(is_bool($query_result) === false){
			nowexit(false, '기록 등록에 실패했습니다.');
		}
		if($query_result === false){
			nowexit(false, '기록 등록에 실패했습니다.');
		}
	}

	//어웨이팀 타자 데이터 삽입
	for($i=0; $i<count($away_batters); $i++){
		$batter = $away_batters[$i];

		// 타자 정보를 데이터베이스에 삽입하는 SQL 쿼리
		$sql = "INSERT INTO batter_game_stat (game_sid, player_sid, game_date, home_away, pa, ab, hits, double_hits, triple_hits, hr, r, rbi, so, bb, hbp, gb, fb, sf, sh, gdp, sb, error, note) 
		VALUES ($game_sid, '{$batter['player_sid']}', '$game_date', 1, {$batter['pa']}, {$batter['ab']}, {$batter['hits']}, {$batter['double_hits']}, {$batter['triple_hits']}, {$batter['hr']}, {$batter['r']}, {$batter['rbi']}, {$batter['so']}, {$batter['bb']}, {$batter['hbp']}, {$batter['gb']}, {$batter['fb']}, {$batter['sf']}, {$batter['sh']}, {$batter['gdp']}, {$batter['sb']}, {$batter['error']}, '{$batter['note']}' )";
		
		$query_result = sql($sql);

		if(is_bool($query_result) === false){
			nowexit(false, '기록 등록에 실패했습니다.');
		}
		if($query_result === false){
			nowexit(false, '기록 등록에 실패했습니다.');
		}
	}

	//어웨이팀 투수 데이터 삽입
	for($i=0; $i<count($away_pitchers); $i++){
		$pitcher = $away_pitchers[$i];

		// 투수 정보를 데이터베이스에 삽입하는 SQL 쿼리
		$sql = "INSERT INTO pitcher_game_stat (game_sid, player_sid, game_date, home_away, win, lose, r, er, pitches, ip, cg, sho, sv, qs, pa, gb, fb, iffb, ifh, hr, k, bb, error, hits, ibb, sf, hbp, note) VALUES ('$game_sid', '{$pitcher['player_sid']}', '$game_date', 1, {$pitcher['win']}, {$pitcher['lose']}, {$pitcher['r']}, {$pitcher['er']}, {$pitcher['pitches']}, {$pitcher['ip']}, {$pitcher['cg']}, {$pitcher['sho']}, {$pitcher['sv']}, {$pitcher['qs']}, {$pitcher['pa']}, {$pitcher['gb']}, {$pitcher['fb']}, {$pitcher['iffb']}, {$pitcher['ifh']}, {$pitcher['hr']}, {$pitcher['k']}, {$pitcher['bb']}, {$pitcher['error']}, {$pitcher['hits']}, {$pitcher['ibb']}, {$pitcher['sf']}, {$pitcher['hbp']}, '{$pitcher['note']}')";

		$query_result = sql($sql);

		if(is_bool($query_result) === false){
			nowexit(false, '기록 등록에 실패했습니다.');
		}
		if($query_result === false){
			nowexit(false, '기록 등록에 실패했습니다.');
		}
	}

	// game_info 테이블 업데이트 쿼리
	$sql = "UPDATE game_info SET home_team_score = $home_team_score, away_team_score = $away_team_score WHERE sid = $game_sid";
	$query_result = sql($sql);
	if(is_bool($query_result) === false){
		nowexit(false, '기록 등록에 실패했습니다.');
	}
	if($query_result === false){
		nowexit(false, '기록 등록에 실패했습니다.');
	}

	//홈 팀 정보가 있는지 확인
	$sql = "SELECT sid FROM team_info WHERE sid = '$home_team_sid';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);
	if($query_result['output_cnt'] === 0){
		nowexit(false, '팀 정보가 없습니다.');
	}
	//어웨이 팀 정보가 있는지 확인
	$sql = "SELECT sid FROM team_info WHERE sid = '$away_team_sid';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);
	if($query_result['output_cnt'] === 0){
		nowexit(false, '팀 정보가 없습니다.');
	}

	// 홈팀 정보 업데이트 쿼리
	$update_home_query = "UPDATE team_info 
					SET 
						team_record_win = team_record_win + $home_team_win,
						team_record_draw = team_record_draw + $home_team_draw,
						team_record_lose = team_record_lose + $home_team_lose,
						team_rs = team_rs + $home_team_score,
						team_ra = team_ra + $away_team_score,
						team_ip = team_ip + $home_pitchers_ip,
						team_hr = team_hr + $home_batters_hr,
						team_bb = team_bb + $home_pitchers_bb,
						team_k = team_k + $home_pitchers_k,
						team_hbp = team_hbp + $home_batters_hbp
					WHERE sid = '$home_team_sid';";
	$update_result_home = sql($update_home_query);

	// 어웨이팀 정보 업데이트 쿼리
	$update_away_query = "UPDATE team_info 
					SET 
						team_record_win = team_record_win + $away_team_win,
						team_record_draw = team_record_draw + $away_team_draw,
						team_record_lose = team_record_lose + $away_team_lose,
						team_rs = team_rs + $away_team_score,
						team_ra = team_ra + $home_team_score,
						team_ip = team_ip + $away_pitchers_ip,
						team_hr = team_hr + $away_batters_hr,
						team_bb = team_bb + $away_pitchers_bb,
						team_k = team_k + $away_pitchers_k,
						team_hbp = team_hbp + $away_batters_hbp
					WHERE sid = '$away_team_sid';";
	$update_result_away = sql($update_away_query);

	if($update_result_home === false || $update_result_away === false){
		nowexit(false, '팀 정보 업데이트에 실패했습니다.');
	}


	// 시즌별 선수 정보 삽입 전 정보가 존재하는지 확인
	for ($i = 0; $i < count($home_batters); $i++) {
	    $batter = $home_batters[$i];
	    $player_sid = $batter['player_sid'];
	    $sql = "SELECT sid FROM season_batter_info WHERE player_sid = '$player_sid' AND season = '$year';";
	    $query_result = sql($sql);
	    $batter_query_result = select_process($query_result);

	    // 시즌별 선수 정보가 존재하는지 확인
	    if ($batter_query_result['output_cnt'] > 0) {
	        // 타자 시즌 정보 업데이트
	        $pa = (int)$batter['pa'];
	        $ab = (int)$batter['ab'];
	        $hits = (int)$batter['hits'];
	        $double_hits = (int)$batter['double_hits'];
	        $triple_hits = (int)$batter['triple_hits'];
	        $hr = (int)$batter['hr'];
	        $r = (int)$batter['r'];
	        $rbi = (int)$batter['rbi'];
	        $bb = (int)$batter['bb'];
	        $hbp = (int)$batter['hbp'];
	        $gb = (int)$batter['gb'];
	        $fb = (int)$batter['fb'];
	        $sf = (int)$batter['sf'];
	        $sh = (int)$batter['sh'];
	        $gdp = (int)$batter['gdp'];
	        $k = (int)$batter['k'];
	        $sb = (int)$batter['sb'];
	        $error = (int)$batter['error'];

	        // 타자 시즌 정보 업데이트
	        $batter_sql = "UPDATE season_batter_info SET 
	                            game = game + 1,
	                            pa = pa + $pa,
	                            ab = ab + $ab,
	                            hits = hits + $hits,
	                            double_hits = double_hits + $double_hits,
	                            triple_hits = triple_hits + $triple_hits,
	                            hr = hr + $hr,
	                            r = r + $r,
	                            rbi = rbi + $rbi,
	                            bb = bb + $bb,
	                            hbp = hbp + $hbp,
	                            gb = gb + $gb,
	                            fb = fb + $fb,
	                            sf = sf + $sf,
	                            sh = sh + $sh,
	                            gdp = gdp + $gdp,
	                            k = k + $k,
	                            sb = sb + $sb,
	                            error = error + $error
	                        WHERE player_sid = '$player_sid' AND season = '$year';";
	        $query_result = sql($batter_sql);
	        if ($query_result === false) {
	            nowexit(false, '기록 등록에 실패했습니다.');
	        }
	    } else {
	        // 타자 시즌 정보 신규 등록
	        $pa = (int)$batter['pa'];
	        $ab = (int)$batter['ab'];
	        $hits = (int)$batter['hits'];
	        $double_hits = (int)$batter['double_hits'];
	        $triple_hits = (int)$batter['triple_hits'];
	        $hr = (int)$batter['hr'];
	        $r = (int)$batter['r'];
	        $rbi = (int)$batter['rbi'];
	        $bb = (int)$batter['bb'];
	        $hbp = (int)$batter['hbp'];
	        $gb = (int)$batter['gb'];
	        $fb = (int)$batter['fb'];
	        $sf = (int)$batter['sf'];
	        $sh = (int)$batter['sh'];
	        $gdp = (int)$batter['gdp'];
	        $k = (int)$batter['k'];
	        $sb = (int)$batter['sb'];
	        $error = (int)$batter['error'];

	        // 타자 시즌 정보 신규 등록
	        $batter_sql = "INSERT INTO season_batter_info (player_sid, season, game, pa, ab, hits, double_hits, triple_hits, hr, r, rbi, bb, hbp, gb, fb, sf, sh, gdp, k, sb, error) 
	                            VALUES ('$player_sid', '$year', 1, $pa, $ab, $hits, $double_hits, $triple_hits, $hr, $r, $rbi, $bb, $hbp, $gb, $fb, $sf, $sh, $gdp, $k, $sb, $error);";
	        $query_result = sql($batter_sql);
	        if ($query_result === false) {
	            nowexit(false, '기록 등록에 실패했습니다.');
	        }
	    }
	}


	// 시즌별 선수 정보 삽입 전 정보가 존재하는지 확인
	for ($i = 0; $i < count($away_batters); $i++) {
	    $batter = $away_batters[$i];
	    $player_sid = $batter['player_sid'];
	    $sql = "SELECT sid FROM season_batter_info WHERE player_sid = '$player_sid' AND season = '$year';";
	    $query_result = sql($sql);
	    $batter_query_result = select_process($query_result);

	    // 시즌별 선수 정보가 존재하는지 확인
	    if ($batter_query_result['output_cnt'] > 0) {
	        // 타자 시즌 정보 업데이트
	        $pa = (int)$batter['pa'];
	        $ab = (int)$batter['ab'];
	        $hits = (int)$batter['hits'];
	        $double_hits = (int)$batter['double_hits'];
	        $triple_hits = (int)$batter['triple_hits'];
	        $hr = (int)$batter['hr'];
	        $r = (int)$batter['r'];
	        $rbi = (int)$batter['rbi'];
	        $bb = (int)$batter['bb'];
	        $hbp = (int)$batter['hbp'];
	        $gb = (int)$batter['gb'];
	        $fb = (int)$batter['fb'];
	        $sf = (int)$batter['sf'];
	        $sh = (int)$batter['sh'];
	        $gdp = (int)$batter['gdp'];
	        $k = (int)$batter['k'];
	        $sb = (int)$batter['sb'];
	        $error = (int)$batter['error'];

	        // 타자 시즌 정보 업데이트
	        $batter_sql = "UPDATE season_batter_info SET 
	                            game = game + 1,
	                            pa = pa + $pa,
	                            ab = ab + $ab,
	                            hits = hits + $hits,
	                            double_hits = double_hits + $double_hits,
	                            triple_hits = triple_hits + $triple_hits,
	                            hr = hr + $hr,
	                            r = r + $r,
	                            rbi = rbi + $rbi,
	                            bb = bb + $bb,
	                            hbp = hbp + $hbp,
	                            gb = gb + $gb,
	                            fb = fb + $fb,
	                            sf = sf + $sf,
	                            sh = sh + $sh,
	                            gdp = gdp + $gdp,
	                            k = k + $k,
	                            sb = sb + $sb,
	                            error = error + $error
	                        WHERE player_sid = '$player_sid' AND season = '$year';";
	        $query_result = sql($batter_sql);
	        if ($query_result === false) {
	            nowexit(false, '기록 등록에 실패했습니다.');
	        }
	    } else {
	        // 타자 시즌 정보 신규 등록
	        $pa = (int)$batter['pa'];
	        $ab = (int)$batter['ab'];
	        $hits = (int)$batter['hits'];
	        $double_hits = (int)$batter['double_hits'];
	        $triple_hits = (int)$batter['triple_hits'];
	        $hr = (int)$batter['hr'];
	        $r = (int)$batter['r'];
	        $rbi = (int)$batter['rbi'];
	        $bb = (int)$batter['bb'];
	        $hbp = (int)$batter['hbp'];
	        $gb = (int)$batter['gb'];
	        $fb = (int)$batter['fb'];
	        $sf = (int)$batter['sf'];
	        $sh = (int)$batter['sh'];
	        $gdp = (int)$batter['gdp'];
	        $k = (int)$batter['k'];
	        $sb = (int)$batter['sb'];
	        $error = (int)$batter['error'];

	        // 타자 시즌 정보 신규 등록
	        $batter_sql = "INSERT INTO season_batter_info (player_sid, season, game, pa, ab, hits, double_hits, triple_hits, hr, r, rbi, bb, hbp, gb, fb, sf, sh, gdp, k, sb, error) 
	                            VALUES ('$player_sid', '$year', 1, $pa, $ab, $hits, $double_hits, $triple_hits, $hr, $r, $rbi, $bb, $hbp, $gb, $fb, $sf, $sh, $gdp, $k, $sb, $error);";
	        $query_result = sql($batter_sql);
	        if ($query_result === false) {
	            nowexit(false, '기록 등록에 실패했습니다.');
	        }
	    }
	}


	// 시즌별 선수 정보 삽입 전 정보가 존재하는지 확인
	for ($i = 0; $i < count($home_pitchers); $i++) {
	    $pitcher = $home_pitchers[$i];
	    $player_sid = $pitcher['player_sid'];
	    $sql = "SELECT sid FROM season_pitcher_info WHERE player_sid = '$player_sid' AND season = '$year';";
	    $query_result = sql($sql);
	    $pitcher_query_result = select_process($query_result);

	    // 시즌별 투수 정보가 존재하는지 확인
	    if ($pitcher_query_result['output_cnt'] > 0) {
	        // 투수 시즌 정보 업데이트
	        $win = (int)$pitcher['win'];
	        $lose = (int)$pitcher['lose'];
	        $r = (int)$pitcher['r'];
	        $ip = (int)$pitcher['ip'];
	        $cg = (int)$pitcher['cg'];
	        $sho = (int)$pitcher['sho'];
	        $sv = (int)$pitcher['sv'];
	        $qs = (int)$pitcher['qs'];
	        $pa = (int)$pitcher['pa'];
	        $gb = (int)$pitcher['gb'];
	        $fb = (int)$pitcher['fb'];
	        $iffb = (int)$pitcher['iffb'];
	        $ifh = (int)$pitcher['ifh'];
	        $hr = (int)$pitcher['hr'];
	        $k = (int)$pitcher['k'];
	        $bb = (int)$pitcher['bb'];
	        $error = (int)$pitcher['error'];
	        $hits = (int)$pitcher['hits'];
	        $pitches = (int)$pitcher['pitches'];
	        $ibb = (int)$pitcher['ibb'];
	        $er = (int)$pitcher['er'];
	        $sf = (int)$pitcher['sf'];
	        $hbp = (int)$pitcher['hbp'];

	        // 투수 시즌 정보 업데이트
	        $pitcher_sql = "UPDATE season_pitcher_info SET 
	                            game = game + 1,
	                            win = win + $win,
	                            lose = lose + $lose,
	                            r = r + $r,
	                            ip = ip + $ip,
	                            cg = cg + $cg,
	                            sho = sho + $sho,
	                            sv = sv + $sv,
	                            qs = qs + $qs,
	                            pa = pa + $pa,
	                            gb = gb + $gb,
	                            fb = fb + $fb,
	                            iffb = iffb + $iffb,
	                            ifh = ifh + $ifh,
	                            hr = hr + $hr,
	                            k = k + $k,
	                            bb = bb + $bb,
	                            error = error + $error,
	                            hits = hits + $hits,
	                            pitches = pitches + $pitches,
	                            ibb = ibb + $ibb,
	                            er = er + $er,
	                            sf = sf + $sf,
	                            hbp = hbp + $hbp
	                        WHERE player_sid = '$player_sid' AND season = '$year';";
	        $query_result = sql($pitcher_sql);
	        if ($query_result === false) {
	            nowexit(false, '기록 등록에 실패했습니다.');
	        }
	    } else {
	        // 투수 시즌 정보 신규 등록
	        $win = (int)$pitcher['win'];
	        $lose = (int)$pitcher['lose'];
	        $r = (int)$pitcher['r'];
	        $ip = (int)$pitcher['ip'];
	        $cg = (int)$pitcher['cg'];
	        $sho = (int)$pitcher['sho'];
	        $sv = (int)$pitcher['sv'];
	        $qs = (int)$pitcher['qs'];
	        $pa = (int)$pitcher['pa'];
	        $gb = (int)$pitcher['gb'];
	        $fb = (int)$pitcher['fb'];
	        $iffb = (int)$pitcher['iffb'];
	        $ifh = (int)$pitcher['ifh'];
	        $hr = (int)$pitcher['hr'];
	        $k = (int)$pitcher['k'];
	        $bb = (int)$pitcher['bb'];
	        $error = (int)$pitcher['error'];
	        $hits = (int)$pitcher['hits'];
	        $pitches = (int)$pitcher['pitches'];
	        $ibb = (int)$pitcher['ibb'];
	        $er = (int)$pitcher['er'];
	        $sf = (int)$pitcher['sf'];
	        $hbp = (int)$pitcher['hbp'];

	        // 투수 시즌 정보 신규 등록
	        $pitcher_sql = "INSERT INTO season_pitcher_info (player_sid, season, game, win, lose, r, ip, cg, sho, sv, qs, pa, gb, fb, iffb, ifh, hr, k, bb, error, hits, pitches, ibb, er, sf, hbp) 
	                            VALUES ('$player_sid', '$year', 1, $win, $lose, $r, $ip, $cg, $sho, $sv, $qs, $pa, $gb, $fb, $iffb, $ifh, $hr, $k, $bb, $error, $hits, $pitches, $ibb, $er, $sf, $hbp);";
			$query_result = sql($pitcher_sql);
			if(is_bool($query_result) === false){
				nowexit(false, '기록 등록에 실패했습니다.');
			}
			if($query_result === false){
				nowexit(false, '기록 등록에 실패했습니다.');
			}
		}
	}

	// 시즌별 선수 정보 삽입 전 정보가 존재하는지 확인
	for ($i = 0; $i < count($away_pitchers); $i++) {
	    $pitcher = $away_pitchers[$i];
	    $player_sid = $pitcher['player_sid'];
	    $sql = "SELECT sid FROM season_pitcher_info WHERE player_sid = '$player_sid' AND season = '$year';";
	    $query_result = sql($sql);
	    $pitcher_query_result = select_process($query_result);

	    // 시즌별 투수 정보가 존재하는지 확인
	    if ($pitcher_query_result['output_cnt'] > 0) {
	        // 투수 시즌 정보 업데이트
	        $win = (int)$pitcher['win'];
	        $lose = (int)$pitcher['lose'];
	        $r = (int)$pitcher['r'];
	        $ip = (int)$pitcher['ip'];
	        $cg = (int)$pitcher['cg'];
	        $sho = (int)$pitcher['sho'];
	        $sv = (int)$pitcher['sv'];
	        $qs = (int)$pitcher['qs'];
	        $pa = (int)$pitcher['pa'];
	        $gb = (int)$pitcher['gb'];
	        $fb = (int)$pitcher['fb'];
	        $iffb = (int)$pitcher['iffb'];
	        $ifh = (int)$pitcher['ifh'];
	        $hr = (int)$pitcher['hr'];
	        $k = (int)$pitcher['k'];
	        $bb = (int)$pitcher['bb'];
	        $error = (int)$pitcher['error'];
	        $hits = (int)$pitcher['hits'];
	        $pitches = (int)$pitcher['pitches'];
	        $ibb = (int)$pitcher['ibb'];
	        $er = (int)$pitcher['er'];
	        $sf = (int)$pitcher['sf'];
	        $hbp = (int)$pitcher['hbp'];

	        // 투수 시즌 정보 업데이트
	        $pitcher_sql = "UPDATE season_pitcher_info SET 
	                            game = game + 1,
	                            win = win + $win,
	                            lose = lose + $lose,
	                            r = r + $r,
	                            ip = ip + $ip,
	                            cg = cg + $cg,
	                            sho = sho + $sho,
	                            sv = sv + $sv,
	                            qs = qs + $qs,
	                            pa = pa + $pa,
	                            gb = gb + $gb,
	                            fb = fb + $fb,
	                            iffb = iffb + $iffb,
	                            ifh = ifh + $ifh,
	                            hr = hr + $hr,
	                            k = k + $k,
	                            bb = bb + $bb,
	                            error = error + $error,
	                            hits = hits + $hits,
	                            pitches = pitches + $pitches,
	                            ibb = ibb + $ibb,
	                            er = er + $er,
	                            sf = sf + $sf,
	                            hbp = hbp + $hbp
	                        WHERE player_sid = '$player_sid' AND season = '$year';";
	        $query_result = sql($pitcher_sql);
	        if ($query_result === false) {
	            nowexit(false, '기록 등록에 실패했습니다.');
	        }
	    } else {
	        // 투수 시즌 정보 신규 등록
	        $win = (int)$pitcher['win'];
	        $lose = (int)$pitcher['lose'];
	        $r = (int)$pitcher['r'];
	        $ip = (int)$pitcher['ip'];
	        $cg = (int)$pitcher['cg'];
	        $sho = (int)$pitcher['sho'];
	        $sv = (int)$pitcher['sv'];
	        $qs = (int)$pitcher['qs'];
	        $pa = (int)$pitcher['pa'];
	        $gb = (int)$pitcher['gb'];
	        $fb = (int)$pitcher['fb'];
	        $iffb = (int)$pitcher['iffb'];
	        $ifh = (int)$pitcher['ifh'];
	        $hr = (int)$pitcher['hr'];
	        $k = (int)$pitcher['k'];
	        $bb = (int)$pitcher['bb'];
	        $error = (int)$pitcher['error'];
	        $hits = (int)$pitcher['hits'];
	        $pitches = (int)$pitcher['pitches'];
	        $ibb = (int)$pitcher['ibb'];
	        $er = (int)$pitcher['er'];
	        $sf = (int)$pitcher['sf'];
	        $hbp = (int)$pitcher['hbp'];

	        // 투수 시즌 정보 신규 등록
	        $pitcher_sql = "INSERT INTO season_pitcher_info (player_sid, season, game, win, lose, r, ip, cg, sho, sv, qs, pa, gb, fb, iffb, ifh, hr, k, bb, error, hits, pitches, ibb, er, sf, hbp) 
	                            VALUES ('$player_sid', '$year', 1, $win, $lose, $r, $ip, $cg, $sho, $sv, $qs, $pa, $gb, $fb, $iffb, $ifh, $hr, $k, $bb, $error, $hits, $pitches, $ibb, $er, $sf, $hbp);";
			$query_result = sql($pitcher_sql);
			if(is_bool($query_result) === false){
				nowexit(false, '기록 등록에 실패했습니다.');
			}
			if($query_result === false){
				nowexit(false, '기록 등록에 실패했습니다.');
			}
		}
	}
	nowexit(true, '기록 등록을 완료했습니다.');
?>