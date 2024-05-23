//아이디 안정성 검사 함수
function validateId(id){
	//영문자+숫자의 8 ~ 12자 확인 정규식
	var regex = /^[a-zA-Z0-9]{8,12}$/;
	//test() 매서드 이용 주어진 문자열이 정규식과 일치하는지 regex에 넣어서 확인(bool값 반환)
	return regex.test(id);
}

//비밀번호 안정성 검사 함수
function validatePw(pw){
	//소문자, 숫자 포함하는지 + 8 ~ 12 확인 정규식
	var regex = /^(?=.*[a-z])(?=.*\d)[a-zA-Z0-9]{8,12}$/;
	//test() 매서드 이용 정규식과 일치하는지 확인
	return regex.test(pw);
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

//엔터로 회원가입 함수
function enterJoin(event){
	if(window.event.keyCode === 13){
		join();
	}
}

$(document).ready(function(){
	// 무소속 체크박스 변화 감지
	$('#no_team_checkbox').change(function(){
		// 체크박스 선택 시
		if($(this).is(':checked')){
			// 팀 선택 셀렉트 바의 값을 0설정, disabled로 변경
			$('#team').val('0').prop('disabled', true);
			// 팀 선택 시 비밀번호 입력창 숨기기
			$('#team_password').hide();
		}else{
			// 체크박스가 선택되지 않았을 때 팀 선택 셀렉트바 값 초기화 후 활성화
			$('#team').val('').prop('disabled', false);
			// 팀 선택 시 비밀번호 입력창 보이기
			$('#team_password').show();
		}
	});
	// 팀 선택 셀렉트 박스의 변경 이벤트 리스너
	$('#team').change(function(){
		var selectedTeamId = $(this).val(); // 선택된 팀의 ID 가져오기
		if(selectedTeamId !== ''){
			// 팀 선택 시 비밀번호 입력창 보이기
			$('#team_password').show();
		}else{
			// 팀 선택 셀렉트 박스가 초기화되었을 때 비밀번호 입력창 숨기기
			$('#team_password').hide();
		}
	});
});

// 회원가입 함수
function join(){
	// 아이디, 비밀번호, 주민번호 등의 입력값 가져오기
	let id = $('#id').val();
	let pw = $('#pw').val();
	let pw_check = $('#pw_check').val();
	let id_num_f = $('#id_num_f').val(); // 주민번호 앞자리
	let id_num_b = $('#id_num_b').val(); // 주민번호 뒷자리

	//선택된 팀의 ID 가져오기
	let team = ($('#no_team_checkbox').is(':checked')) ? '0' : $('#team').val();

	//팀의 비밀번호가 맞는지 확인
	if(team > 0){
		if($('#team_password').val() !== '1234'){
			alert('올바른 팀 비밀번호를 입력하세요.');
			return false;
		}
	}
	//회원가입 유효성 검사
	//아이디
	if(id === ''){
		alert('아이디를 입력하세요.');
		return false;
	}
	if(validateId(id) === false){
		alert('아이디는 영문자와 숫자로 이루어진 8 ~ 12자여야 합니다.');
		return false;
	}
	//비밀번호
	if(pw === ''){
		alert('비밀번호를 입력하세요.');
		return false;
	}
	if(validatePw(pw) === false){
		alert('비밀번호는 소문자, 숫자를 포함한 8 ~ 12자 이내여야 합니다.');
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
	//비밀번호와 비밀번호 확인 일치 여부
	if(pw !== pw_check){
		alert('비밀번호가 일치하지 않습니다.');
		return false;
	}

	//api로 보낼 데이터
	let senddata = new Object();
	senddata.id = id;
	senddata.pw = pw;
	senddata.id_num = id_num_f + id_num_b; //주민번호 합치기
	senddata.team = team;

	api('api_join', senddata, function(output){
		if(output.is_success){
			render('index');
		}
		alert(output.msg);
	});
}