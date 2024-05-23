<?php
	define('__CORE_TYPE__', 'api');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//세션 값 제거
	unset($_SESSION['id']);
	unset($_SESSION['team_sid']);

	nowexit(true, '로그아웃 되었습니다.');
?>