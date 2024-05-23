<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//아이디 유효성 검사
	if(isset($_POST['id']) === false){
		nowexit(false, '등록된 아이디가 없습니다.');
	}
	if($_POST['id'] === '' || $_POST['id'] === null){
		nowexit(false, '등록된 아이디가 없습니다.');
	}
	$id = $_POST['id'];
	//주민번호 유효성검사
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

	//아이디와 주민번호를 조건으로 유저 테이블의 아이디 찾기
	$sql = "SELECT id FROM user_data WHERE id = '$id';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] === 0){
		nowexit(false, '존재하지 않는 아이디입니다.');
	}
	$sql = "SELECT id_num FROM user_data WHERE id_num = '$id_num';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] === 0){
		nowexit(false, '존재하지 않는 주민번호입니다.');
	}
	nowexit(true, '비밀번호 변경 페이지로 이동합니다.');

?>