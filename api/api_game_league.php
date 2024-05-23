<?php
	//관리자 권한으로 경기 등록 시 리그 선택 api
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	if(isset($_POST['league_key']) === false){
		nowexit(false, '리그 값이 없습니다.');
	}
	if($_POST['league_key'] === '' || $_POST['league_key'] === null){
		nowexit(false, '리그 값이 없습니다.');
	}
	if(is_int((int)$_POST['league_key']) === false){
		nowexit(false, '리그 정보 값이 정수가 아닙니다.');
	}
	//리그 정보 저장하기
	$league_key = $_POST['league_key'];

	$sql = "SELECT * FROM team_info WHERE league_sid = '$league_key';";
	$query_result = sql($sql);
	$league_data = select_process($query_result);

	if($league_data['output_cnt'] === 0){
		nowexit(false, '데이터를 찾을 수 없습니다.');
	}

	$result['league_data'] = $league_data;

	nowexit(true, '데이터 불러오기에 성공했습니다.');
?>