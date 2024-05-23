<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//포지션 유효성 검사
	if(isset($_POST['position_sid']) === false){
		nowexit(false, '포지션 정보가 없습니다.');
	}
	if($_POST['position_sid'] === '' || $_POST['position_sid'] === null){
		nowexit(false, '포지션 정보가 없습니다.');
	}
	if(is_int((int)$_POST['position_sid']) === false){
		nowexit(false, '포지션 정보 값이 정수가 아닙니다.');
	}
	//포지션 정보 저장하기
	$position_sid = (int)$_POST['position_sid'];

	//포지션 정보에 따른 데이터 베이스 선택
	if($position_sid === 1){
		//타자 시즌 정보 가져오기
		$season_info = 'season_batter_info';
	}
	if($position_sid === 2){
		$season_info = 'season_pitcher_info';
	}
	//시즌 정보 가져오기(상수로 선언된)
	$sql = "SELECT * FROM $season_info WHERE season ='".__YEAR__."';";
	$query_result = sql($sql);
	$position_data = select_process($query_result);

	//포지션 데이터를 찾을 수 없으면 종료
	if($position_data['output_cnt'] === 0){
		nowexit(false, '데이터를 찾을 수 없습니다.');
	}

	//포지션 데이터의 숫자 값들을 정수로 변환
	for($i=0; $i<$position_data['output_cnt']; $i++){
		$data_row = $position_data[$i];
		if(is_array($data_row)){
			$keys = array_keys($data_row);
			$keys_cnt = count($keys);
			for($j=0; $j<$keys_cnt; $j++){
				$key = $keys[$j];
				if(is_numeric($data_row[$key])){
					$position_data[$i][$key] = intval($data_row[$key]);
				}
			}
		}
	}

	//js로 포지션 데이터 보내기
	$result['position_data'] = $position_data;
	$result['position_sid'] = $position_sid;

	nowexit(true, '데이터 불러오기에 성공했습니다.');
?>