<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';
?>

<script>
	//아이디 안정성 검사 함수
	function validateId(id){
		//영문자+숫자의 8 ~ 12자 확인 정규식
		var regex = /^[a-zA-Z0-9]{8,12}$/;
		//test() 매서드 이용해서 정규식과 일치하는지 확인
		return regex.test(id);
	}
	//비밀번호 안정성 검사 함수
	function validatePw(pw){
		//소문자, 숫자 포함하는지 + 8 ~ 12 확인 정규식
		var regex = /^(?=.*[a-z])(?=.*\d)[a-zA-Z0-9]{8,12}$/;
		//test() 매서드 이용 정규식과 일치하는지 확인
		return regex.test(pw);
	}

	//입력 필드 초기화 함수
	function clear_field(){
		document.getElementById('id').value = '';
		document.getElementById('pw').value = '';
	}

	//로그인 함수
	function login(){
		let id = document.getElementById('id').value;
		let pw = document.getElementById('pw').value;

		//유효성 검사
		if(id === ''){
			alert('아이디를 입력하세요');
			return false;
		}
		if(validateId(id) === false){
			alert('아이디는 영문자와 숫자로 이루어진 8 ~ 12자여야 합니다.');
			return false;
		}
		if(pw === ''){
			alert('비밀번호를 입력하세요');
		}

		if(validatePw(pw) === false){
			alert('비밀번호는 소문자, 숫자를 포함한 8 ~ 12자 이내여야 합니다.');
			return false;
		}

		//샌드데이터로 api에 보낼 정보
		senddata = new Object();
		senddata.id = id;
		senddata.pw = pw;

		api('api_login', senddata, function(output){
			if(output.is_success){
				render('stat');
			}
		});
		//입력된거 초기화
		clear_field();
		return null;
	}

	//엔터 눌렀을 때 로그인 하는 함수
	function enterLogin(event){
		//13번 === 엔터
		if(window.event.keyCode === 13){
			login();
		}
	}
</script>
<style>

/* home 요소를 중앙에 배치 */
#home {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 100px;
}
/* 로그인 폼 스타일 */
#login-form {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 350px;
    text-align: center;
}

#login-form input[type='text'],
#login-form input[type='password'] {
    width: calc(100% - 20px);
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

#login-form input[type='submit'] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: none;
    border-radius: 5px;
    background-color: #007BFF;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

#login-form input[type='submit']:hover {
    background-color: #0056b3;
}

/* 링크 스타일 */
#login-form a {
    display: inline-block;
    margin: 10px 0;
    color: #007BFF;
    text-decoration: none;
    cursor: pointer;
}

#login-form a:hover {
    text-decoration: underline;
}

</style>
<title>로그인</title>
<div id='home'>
	<h2>로그인</h2>
	<div id='login-form'>
		<input type='text' name='id' id='id' placeholder='아이디' onkeypress='enterLogin()' />
		<br/>
		<input type='password' name='pw' id='pw' placeholder='비밀번호' onkeypress='enterLogin()' />
		<br/>
		<input type='submit' onclick='login();' value='로그인' />
		<br/>
		<a onclick='render("join_select");'>회원가입</a>
		<a onclick='render("id_search");'>아이디/비밀번호 찾기</a>
	</div>
</div>