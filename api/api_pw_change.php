<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//sendddata로 넘어온 데이터 유효성 검사
	//아이디
	if(isset($_POST['id']) === false){
		nowexit(false,'아이디 값이 없습니다.');
	}
	if($_POST['id'] === '' || $_POST['id'] === null){
		nowexit(false, '아이디 값이 없습니다.');
	}
	if(ctype_alnum($_POST['id']) === false){
		nowexit(false, '아이디는 영문자와 숫자로만 이루어져야 합니다.');
	}
	if(mb_strlen($_POST['id']) > 12 || mb_strlen($_POST['id']) < 8){
		nowexit(false, '아이디 값은 8 ~ 12자 사이여야 합니다.');
	}
	$id = $_POST['id'];
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
	//변경할 비밀번호
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

	$sql = "UPDATE user_data SET pw='$pw' WHERE id='$id' AND id_num='$id_num';";
	$query_result = sql($sql);
	if(is_bool($query_result) === false){
		nowexit(false, '비밀번호 변경을 실패했습니다.');
	}
	if($query_result === false){
		nowexit(false, '비밀번호 변경을 실패했습니다.');
	}
	nowexit(true, '비밀번호 변경에 성공했습니다.');
?>