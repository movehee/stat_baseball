<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//날짜 유효성 검사
	if(isset($_POST['game_date']) === false){
		nowexit(false, '경기 생성 데이터가 없습니다.');
	}
	if($_POST['game_date'] === '' || $_POST['game_date'] === null){
		nowexit(false, '경기 생성 데이터가 없습니다.');
	}
	$game_date = $_POST['game_date'];

	//홈팀 유효성 검사
	if(isset($_POST['home_team']) === false){
		nowexit(false, '경기 생성 데이터가 없습니다.');
	}
	if(is_array($_POST['home_team']) === false){
		nowexit(false, '경기 생성 데이터가 없습니다.');
	}
	if($_POST['home_team'] === '' || $_POST['home_team'] === null){
		nowexit(false, '경기 생성 데이터가 없습니다.');
	}
	$home_teams = $_POST['home_team'];

	//어웨이팀 유효성 검사
	if(isset($_POST['away_team']) === false){
		nowexit(false, '경기 생성 데이터가 없습니다.');
	}
	if(is_array($_POST['away_team']) === false){
		nowexit(false, '경기 생성 데이터가 없습니다.');
	}
	if($_POST['away_team'] === '' || $_POST['away_team'] === null){
		nowexit(false, '경기 생성 데이터가 없습니다.');
	}
	$away_teams = $_POST['away_team'];

	// 경기 등록
	for($i=0; $i<count($home_teams); $i++){
		// 중복 경기 확인
		$sql = "SELECT sid FROM game_info WHERE game_date = '$game_date' AND home_team_sid = '{$home_teams[$i]}' AND away_team_sid = '{$away_teams[$i]}';";
		$query_result = sql($sql);
		$query_result = select_process($query_result);
		if($query_result['output_cnt'] > 0){
			nowexit(false, '이미 존재하는 게임이 있습니다.');
		}

		// 경기 등록 sql
		$sql = "INSERT INTO game_info (game_date, home_team_sid, away_team_sid, home_team_score, away_team_score, game_result, note) VALUES ('$game_date', '{$home_teams[$i]}', '{$away_teams[$i]}', 0, 0, 0, '');";
		$query_result = sql($sql);
		// 등록 실패 시
		if(isset($query_result) === false){
			nowexit(false, '경기 등록에 실패했습니다.');
		}
	}
	nowexit(true, '경기 등록에 성공했습니다.');
?>