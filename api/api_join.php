<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//아이디
	if(isset($_POST['id']) === false){
		nowexit(false,'아이디 값이 없습니다.');
	}
	if($_POST['id'] === '' || $_POST['id'] === null){
		nowexit(false,'아이디 값이 없습니다.');
	}
	if(ctype_alnum($_POST['id']) === false){
		nowexit(false, '아이디는 영문자와 숫자로만 이루어져야 합니다.');
	}
	if(strlen($_POST['id']) >12 || strlen($_POST['id']) < 8){
		nowexit(false, '아이디는 8 ~ 12자여야 합니다.');
	}
	$id = $_POST['id'];
	
	//비밀번호
	if(isset($_POST['pw']) === false){
		nowexit(false,'비밀번호 값이 없습니다.');
	}
	if($_POST['pw'] === '' || $_POST['pw'] === null){
		nowexit(false,'비밀번호 값이 없습니다.');
	}
	if(ctype_alnum($_POST['pw']) === false){
		nowexit(false,'비밀번호는 영문자와 숫자로 이루어져야 합니다.');
	}
	if(strlen($_POST['pw']) >12 || strlen($_POST['pw']) < 8){
		nowexit(false, '비밀번호는 8 ~ 12자여야 합니다.');
	}
	$pw = $_POST['pw'];

	//주민번호
	if(isset($_POST['id_num']) === false){
		nowexit(false, '주민번호 값이 없습니다.');
	}
	if($_POST['id_num'] === ''|| $_POST['id_num'] === null){
		nowexit(false, '주민번호 값이 없습니다.');
	}
	if(strlen($_POST['id_num']) !== 13){
		nowexit(false, '주민번호는 13자리 숫자여야 합니다.');
	}
	if(is_numeric($_POST['id_num']) === false){
		nowexit(false, '주민번호는 숫자만 입력해주세요.');
	}
	$id_num = $_POST['id_num'];

	//아이디 중복 검사
	$sql = "SELECT sid FROM user_data WHERE id = '$id';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);
	if($query_result['output_cnt'] > 0){
		nowexit(false, '이미 사용중인 아이디입니다.');
	}

	//주민번호 중복 검사
	$sql = "SELECT sid FROM user_data WHERE id_num ='$id_num';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);
	if($query_result['output_cnt'] > 0){
		nowexit(false, '이미 등록되어있는 주민번호입니다.');
	}

	//회원 등록
	$sql = "INSERT INTO user_data(id, pw, id_num, is_admin, team_sid) VALUES ('$id', '$pw', '$id_num', '0', '0');";
	$query_result = sql($sql);
	if(is_bool($query_result) === false){
		nowexit(false, '회원가입을 실패했습니다.');
	}

	nowexit(true, '회원가입에 성공했습니다.');

?>