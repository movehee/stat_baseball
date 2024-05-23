//로그아웃 함수
function logout(){
	api('api_logout',{}, function(output){
		if(output.is_success){
			render('index');
			alert('로그아웃 되었습니다.');
		}
	});
	return null;
}
