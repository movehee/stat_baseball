<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//아이디 유효성 검사
	if(isset($_POST['id']) === false){
		nowexit(false, '아이디 값이 없습니다.');
	}
	if($_POST['id'] === '' || $_POST['id'] === null){
		nowexit(false, '아이디 값이 없습니다.');
	}
	if(ctype_alnum($_POST['id']) === false){
		nowexit(false,'아이디는 영문자와 숫자로 이루어져야 합니다.');
	}
	if(mb_strlen($_POST['id']) >12 || mb_strlen($_POST['id']) < 8){
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
	if(mb_strlen($_POST['pw']) >12 || mb_strlen($_POST['pw']) < 8){
		nowexit(false, '비밀번호는 8 ~ 12자여야 합니다.');
	}
	$pw = $_POST['pw'];

	//유저 데이터 테이블 조회
	$sql = "SELECT pw, team_sid FROM user_data WHERE id = '$id';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] === 0){
		nowexit(false, '존재하지 않는 아이디 입니다.');
	}
	if($query_result[0]['pw'] !== $pw){
		nowexit(false, '패스워드가 일치하지 않습니다.');
	}

	//아이디와 팀sid를 세션으로 저장(비밀번호x)
	$_SESSION['id'] = $id;
	$_SESSION['team_sid'] = $query_result[0]['team_sid'];

	nowexit(true, '로그인 되었습니다.');
?>