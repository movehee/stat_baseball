<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//시즌 유효성 검사
	if(isset($_POST['season']) === false){
		nowexit(false, '시즌 정보가 없습니다.');
	}
	if(is_int((int)$_POST['season']) === false){
		nowexit(false, '시즌 정보가 없습니다.');
	}

	//시즌 정보 저장하기
	$season = $_POST['season'];

	// 포지션 테이블 조회
	$sql = "SELECT sid, name FROM position;";
	$query_result = sql($sql);
	$position_data = select_process($query_result);

	for($i=0; $i<$position_data['output_cnt']; $i++){
		$position[$position_data[$i]['sid']] = $position_data[$i]['name'];
	}

	//선수명 가져오기
	$sql = "SELECT sid, player_name, team_sid, position_sid FROM player_info;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$player_name[$query_result[$i]['sid']]	= $query_result[$i]['player_name'];
			$position_name[$query_result[$i]['sid']] = $query_result[$i]['position_sid'];
		}
	}

	//시즌별로 타자 누적 성적 불러오기
	$sql = "SELECT player_sid ,game, pa, ab, hits, double_hits, triple_hits, hr, r, rbi, bb, hbp, gb, fb, sf, sh, gdp, k, sb, error FROM season_batter_info WHERE season = '$season';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	//타자 기록 저장
	$batter_data = array();
	if($query_result['output_cnt'] > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$batter = $query_result[$i];
			$temp = array();
			$temp['name'] = $player_name[$batter['player_sid']];
			$temp['position'] = $position[$position_name[$batter['player_sid']]];
			$temp['game'] = $batter['game'];
			$temp['pa'] = $batter['pa'];
			$temp['ab'] = $batter['ab'];
			$temp['hits'] = $batter['hits'];
			$temp['double_hits'] = $batter['double_hits'];
			$temp['triple_hits'] = $batter['triple_hits'];
			$temp['hr'] = $batter['hr'];
			$temp['r'] = $batter['r'];
			$temp['rbi'] = $batter['rbi'];
			$temp['bb'] = $batter['bb'];
			$temp['hbp'] = $batter['hbp'];
			$temp['gb'] = $batter['gb'];
			$temp['fb'] = $batter['fb'];
			$temp['sf'] = $batter['sf'];
			$temp['sh'] = $batter['sh'];
			$temp['gdp'] = $batter['gdp'];
			$temp['k'] = $batter['k'];
			$temp['sb'] = $batter['sb'];
			$temp['error'] = $batter['error'];

			array_push($batter_data, $temp);
		}
	}

	//시즌별로 투수 누적 성적 불러오기
	$sql = "SELECT player_sid ,game, win, lose, r, ip, cg, sho, r, ip, sv, qs, pa, gb, fb, iffb, ifh, hr, k, bb,error, hits, pitches, ibb, er, sf, hbp FROM season_pitcher_info WHERE season = '$season';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	//투수 기록 저장
	$pitcher_data = array();
	if($query_result['output_cnt'] > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$pitcher = $query_result[$i];
			$temp = array();
			$temp['name'] = $player_name[$pitcher['player_sid']];
			$temp['position'] = $position[$position_name[$pitcher['player_sid']]];
			$temp['game'] = $pitcher['game'];
			$temp['win'] = $pitcher['win'];
			$temp['lose'] = $pitcher['lose'];
			$temp['r'] = $pitcher['r'];
			$temp['ip'] = $pitcher['ip'];
			$temp['cg'] = $pitcher['cg'];
			$temp['sho'] = $pitcher['sho'];
			$temp['sv'] = $pitcher['sv'];
			$temp['qs'] = $pitcher['qs'];
			$temp['pa'] = $pitcher['pa'];
			$temp['gb'] = $pitcher['gb'];
			$temp['fb'] = $pitcher['fb'];
			$temp['iffb'] = $pitcher['iffb'];
			$temp['ifh'] = $pitcher['ifh'];
			$temp['hr'] = $pitcher['hr'];
			$temp['k'] = $pitcher['k'];
			$temp['bb'] = $pitcher['bb'];
			$temp['error'] = $pitcher['error'];
			$temp['hits'] = $pitcher['hits'];
			$temp['pitches'] = $pitcher['pitches'];
			$temp['ibb'] = $pitcher['ibb'];
			$temp['er'] = $pitcher['er'];
			$temp['sf'] = $pitcher['sf'];
			$temp['hbp'] = $pitcher['hbp'];

			array_push($pitcher_data, $temp);
		}
	}

	$result['batter_data'] = $batter_data;
	$result['pitcher_data'] = $pitcher_data;

	nowexit(true, '데이터를 불러왔습니다.');
?>