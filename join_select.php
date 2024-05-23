<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';
?>
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

/* 회원가입 찾기 섹션 스타일 */
#join_select {
	background-color: #ffffff;
	padding: 20px;
	border-radius: 10px;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	width: 450px;
	text-align: center;
}

#join_select h2 {
	margin-bottom: 20px;
	font-size: 1.5em;
}

#join_select button{
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

#join_select button:hover{
	background-color: #0056b3;
}
</style>
<title>회원가입</title>
<div id='join_select'>
	<h2>회원가입을 환영합니다.</h2>
	<div>
		<button onclick='render("join")'>일반 사용자</button>
		<button onclick='render("join_admin")'>팀 관리자</button>
	</div>
</div>