<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//리그 유효성 검사
	if(isset($_POST['league_sid']) === false){
		nowexit(false, '리그 정보가 없습니다.');
	}
	if($_POST['league_sid'] === '' || $_POST['league_sid'] === null){
		nowexit(false, '리그 정보가 없습니다.');
	}
	if(is_int((int)$_POST['league_sid']) === false){
		nowexit(false, '리그 정보 값이 정수가 아닙니다.');
	}
	//리그 정보 저장하기
	$league_sid = $_POST['league_sid'];

	//리그 정보 sql(리그 sid 기준)
	$sql = "SELECT * FROM team_info WHERE league_sid = '$league_sid';";
	$query_result = sql($sql);
	$league_data = select_process($query_result);

	//시즌 정보 sql(현 시점 기준)
	$sql = "SELECT * FROM season_team_info WHERE season ='".__YEAR__."';";
	$query_result = sql($sql);
	$season_data = select_process($query_result);

	//리그 데이터를 찾을 수 없으면 종료
	if($league_data['output_cnt'] === 0){
		nowexit(false, '데이터를 찾을 수 없습니다.');
	}
	//시즌 데이터를 찾을 수 없으면 종료
	if($season_data['output_cnt'] === 0){
		nowexit(false, '데이터를 찾을 수 없습니다.');
	}

	//js로 리그 데이터 보내기
	$result['league_data'] = $league_data;
	//js로 시즌 데이터 보내기
	$result['season_data'] = $season_data;

	nowexit(true, '데이터 불러오기에 성공했습니다.');
?>