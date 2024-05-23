<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';
?>

<script>
	//아이디 안정성 검사 함수
	function validateId(id){
		//영문자+숫자의 8 ~ 12자 확인 정규식
		var regex = /^[a-zA-Z0-9]{8,12}$/;
		//test() 매서드 이용 주어진 문자열이 정규식과 일치하는지 regex에 넣어서 확인(bool값 반환)
		return regex.test(id);
	}
	//주민번호 안정성 검사 함수
	function validateIdNum(id_num_f, id_num_b){
		//주민등록번호 합치기
		var id_num = id_num_f + id_num_b;

		//숫자가 13자리인지 확인
		if(id_num.length !== 13){
			alert('주민번호는 13자리여야 합니다.');
			return false;
		}
		// 앞자리가 숫자인지 확인
		if (!/^[0-9]+$/.test(id_num_f)) {
			alert('주민등록번호 앞자리는 숫자로만 이루어져야 합니다.');
			return false;
		}
		// 뒷자리가 숫자 또는 '*'로 이루어져 있는지 확인
		if (!/^[0-9*]+$/.test(id_num_b)) {
			alert('주민등록번호 뒷자리는 숫자 또는 *로 이루어져야 합니다.');
			return false;
		}    	
	}
	//아이디 찾기 함수
	function find_id(){
		let id_num_f = $('#id_num_f').val();
		let id_num_b = $('#id_num_b').val();

		//주민번호
		if(id_num_f === '' || id_num_b === ''){
			alert('주민번호를 입력하세요.');
			return false;
		}
		if(validateIdNum(id_num_f, id_num_b) === false){
			alert('주민번호의 양식을 맞춰야 합니다.');
			return false;
		}

		//api로 보낼 데이터
		let senddata = new Object();
		senddata.id_num = id_num_f + id_num_b;

		api('api_id_exist', senddata, function(output){
			if(output.is_success){
				render('id_exist', senddata);
			}
			alert(output.msg);
		});
	}
	//비밀번호 찾기 함수
	function find_pw(){
		let id = $('#id_search').val();
		let id_num_f = $('#id_num_f_2').val();
		let id_num_b = $('#id_num_b_2').val();

		//아이디
		if(id === ''){
			alert('아이디를 입력하세요.');
			return false;
		}
		if(validateId(id) === false){
			alert('아이디는 영문자와 숫자로 이루어진 8 ~ 12자여야 합니다.');
			return false;
		}

		//주민번호
		if(id_num_f === '' || id_num_b === ''){
			alert('주민번호를 입력하세요.');
			return false;
		}
		if(validateIdNum(id_num_f, id_num_b) === false){
			alert('주민번호의 양식을 맞춰야 합니다.');
			return false;
		}

		//api로 보낼 데이터
		let senddata = new Object();
		senddata.id = id;
		senddata.id_num = id_num_f + id_num_b;

		api('api_pw_exist', senddata, function(output){
			if(output.is_success){
				render('pw_change', senddata);
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
#search_section {
	background-color: #ffffff;
	padding: 20px;
	border-radius: 10px;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	width: 450px;
	text-align: center;
}

#search_section h2 {
	margin-bottom: 20px;
	font-size: 1.5em;
}

#search_section input[type='text'],
#search_section input[type='password'] {
	width: calc(50% - 10px);
	padding: 10px;
	margin: 10px 5px 10px 0;
	border: 1px solid #ccc;
	border-radius: 5px;
}

#search_section input[type='text']:last-child,
#search_section input[type='password']:last-child {
	margin-right: 0;
}

#search_section button{
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

#search_section button:hover{
	background-color: #0056b3;
}
</style>
<title>아이디 / 비밀번호 찾기</title>
<div id='search_section'>
	<h2>아이디/ 비밀번호 찾기</h2>
	<input type='text' id='id_num_f' placeholder='주민번호를 입력하세요' autocomplete='off'>-<input type='password' id='id_num_b' autocomplete='off'>
	<button onclick="find_id();">아이디 찾기</button>
	<br/>
	<br/>
	<input type='text' id='id_search' placeholder='아이디를 입력하세요' autocomplete='off'>
	<br/>
	<input type='text' id='id_num_f_2' placeholder='주민번호를 입력하세요' autocomplete='off'>-<input type='password' id='id_num_b_2' autocomplete='off'>
	<button onclick="find_pw();">비밀번호 찾기</button>
</div>