<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//시즌 유효성 검사
	if(isset($_POST['season_sid']) === false){
		nowexit(false, '시즌 정보가 없습니다.');
	}
	if($_POST['season_sid'] === '' || $_POST['season_sid'] === null){
		nowexit(false, '시즌 정보가 없습니다.');
	}
	if(is_int((int)$_POST['season_sid']) === false){
		nowexit(false, '시즌 정보 값이 정수가 아닙니다.');
	}
	//시즌 정보 저장하기
	$season_sid = $_POST['season_sid'];
	
	//포지션 유효성 검사
	if(isset($_POST['position_sid']) === false){
		nowexit(false, '포지션 정보가 없습니다.');
	}
	if($_POST['position_sid'] === '' || $_POST['position_sid'] === null){
		nowexit(false, '포지션 정보가 없습니다.');
	}
	if(is_int((int)$_POST['position_sid']) === false){
		nowexit(false, '포지션 정보가 정수가 아닙니다.');
	}
	$position_sid = $_POST['position_sid'];

	//포지션에 따른 데이터 베이스 선택
	$season_info = ($position_sid > 9) ? 'season_pitcher_info' : 'season_batter_info';
	//시즌 정보 가져오기
	$sql = "SELECT * FROM $season_info WHERE sid = $season_sid;";
	$query_result = sql($sql);
	$season_data = select_process($query_result);

	//시즌 데이터를 찾을 수 없으면 종료
	if($season_data['output_cnt'] === 0){
		nowexit(false, '데이터를 찾을 수 없습니다.');
	}

	// 시즌 데이터의 숫자 값들을 정수로 변환
	$data_row = $season_data[0];
	if(is_array($data_row)){
		$keys = array_keys($data_row);
		$keys_cnt = count($keys);
		for($i=0; $i<$keys_cnt; $i++){
			$key = $keys[$i];
			if(is_numeric($data_row[$key])){
				$season_data[0][$key] = intval($data_row[$key]);
			}
		}
	}

	//js로 시즌 데이터 보내기
	$result['season_data'] = $season_data;

	nowexit(true, '데이터 불러오기에 성공했습니다.');
?>