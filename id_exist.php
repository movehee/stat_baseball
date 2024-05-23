<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//받아온 주민번호 유효성 검사
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

	$sql = "SELECT id FROM user_data WHERE id_num = '$id_num';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	$id = array();
	if($query_result['output_cnt'] > 0){
		$id = $query_result[0]['id'];
	}
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

/* 아이디/비밀번호 찾기 섹션 스타일 */
#find_id {
	background-color: #ffffff;
	padding: 20px;
	border-radius: 10px;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	width: 450px;
	text-align: center;
}

#find_id h2 {
	margin-bottom: 20px;
	font-size: 1.5em;
}

#find_id input[type='password'] {
	width: 100%;
	padding: 10px;
	margin: 10px 5px 10px 0;
	border: 1px solid #ccc;
	border-radius: 5px;
	margin-right: 0;
}

#find_id button{
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

#find_id button:hover{
	background-color: #0056b3;
}
</style>
<title>아이디 찾기</title>
<div id='find_id'>
	<h2>아이디 찾기</h2>
	<p>찾은 아이디: <?php echo $id; ?></p>
	<br/>
	<button onclick="render('index')">로그인하기</button>
</div>