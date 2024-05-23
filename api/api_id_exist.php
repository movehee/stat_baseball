<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//주민번호 유효성 검사
	if(isset($_POST['id_num']) === false){
		nowexit(false, '등록된 주민번호가 없습니다.');
	}
	if($_POST['id_num'] === '' || $_POST['id_num'] === null){
		nowexit(false, '등록된 주민번호가 없습니다.');
	}
	if(strlen($_POST['id_num']) !== 13){
		nowexit(false, '주민번호는 13자리 숫자여야 합니다.');
	}
	if(is_numeric($_POST['id_num']) === false){
		nowexit(false, '주민번호는 숫자만 입력해주세요.');
	}
	$id_num = $_POST['id_num'];

	//주민번호를 조건으로 유저 데이터 테이블의 아이디 찾기
	$sql = "SELECT id FROM user_data WHERE id_num = '$id_num';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] === 0){
		nowexit(false, '존재하지 않는 아이디입니다.');
	}
	nowexit(true, '존재하는 아이디입니다.');
?>