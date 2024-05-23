<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//아이디/비밀번호 찾기 페이지에서 넘어온 아이디 주민번호 유효성검사
	if(isset($_POST['id']) === false){
		nowexit(false, '아이디 값이 없습니다.');
	}
	if($_POST['id'] === '' || $_POST['id'] === null){
		nowexit(false, '아이디 값이 없습니다.');
	}
	if(ctype_alnum($_POST['id']) === false){
		nowexit(false, '아이디는 영문자와 숫자로만 이루어져야 합니다.');
	}
	if(strlen($_POST['id']) >12 || strlen($_POST['id']) < 8){
		nowexit(false, '아이디는 8 ~ 12자여야 합니다.');
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

	echo '<script>var id = "'.$id.'";</script>';
	echo '<script>var id_num = "'.$id_num.'";</script>';
?>
<script>
	//비밀번호 안정성 검사 함수
	function validatePw(pw){
		//소문자, 숫자 포함하는지 + 8 ~ 12 확인 정규식
		var regex = /^(?=.*[a-z])(?=.*\d)[a-zA-Z0-9]{8,12}$/;
		//test() 매서드 이용 정규식과 일치하는지 확인
		return regex.test(pw);
	}
	//비밀번호 변경 함수
	function pw_change(){
		let pw = $('#pw').val();
		let pw_check = $('#pw_check').val();

		//유효성 검사
		if(pw === ''){
			alert('비밀번호를 입력하세요.');
			return false;
		}
		if(validatePw(pw) === false){
			alert('비밀번호는 소문자, 숫자를 포함한 8 ~ 12자 이내여야 합니다.');
			return false;
		}
		if(pw !== pw_check){
			alert('비밀번호가 일치하지 않습니다.');
			return false;
		}
		//senddata로 api에 데이터 전송
		let senddata = new Object();
		senddata.id = id;
		senddata.id_num = id_num;
		senddata.pw = pw;

		api('api_pw_change', senddata, function(output){
			if(output.is_success){
				render('index');
			}
			alert(output.msg);
		});
	}


</script>
<style>
/* 기본 설정 */
body {
	background-color: #f5f5f5;
	display: flex;
	justify-content: center;
	align-items: center;
	height: 100vh;
	margin: 0;
}

/* 아이디/비밀번호 찾기 섹션 스타일 */
#new_pw {
	background-color: #ffffff;
	padding: 20px;
	border-radius: 10px;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	width: 450px;
	text-align: center;
}

#new_pw h2 {
	margin-bottom: 20px;
	font-size: 1.5em;
}

#new_pw input[type='password'] {
	width: 100%;
	padding: 10px;
	margin: 10px 5px 10px 0;
	border: 1px solid #ccc;
	border-radius: 5px;
	margin-right: 0;
}

#new_pw button{
	width: 100%;
	padding: 10px;
	margin-top: 20px;
	border: none;
	border-radius: 5px;
	background-color: #007BFF;
	color: white;
	font-size: 16px;
	cursor: pointer;
}

#new_pw button:hover{
	background-color: #0056b3;
}
</style>
<title>비밀번호 변경</title>
<div id='new_pw'>
	<h2>새 비밀번호 입력</h2>
	<div>
		<input type='password' id='pw' placeholder="새 비밀번호 입력" autocomplete="off">
		<br/>
		<input type='password' id='pw_check' placeholder="비밀번호 확인" autocomplete="off">
		<br/>
		<button onclick="pw_change()">비밀번호 변경</button>
	</div>
</div>