<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//포지션 조회
	$sql = "SELECT sid, name FROM position;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	$position = array();
	for($i=0; $i<$query_result['output_cnt']; $i++){
		$temp = array();

		$temp['position_sid'] = $query_result[$i]['sid'];
		$temp['position'] = $query_result[$i]['name'];

		array_push($position, $temp);
	}
	$position_cnt = count($position);

	//팀 조회
	$sql = "SELECT sid, team_name FROM team_info;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	$team = array();
	for($i=0; $i<$query_result['output_cnt']; $i++){
		$temp = array();

		$temp['team_sid'] = $query_result[$i]['sid'];
		$temp['team_name'] = $query_result[$i]['team_name'];

		array_push($team, $temp);
	}
	$team_cnt = count($team);
?>

<script>
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

	//회원가입 함수
	function join(){
		let id = $('#id').val();
		let pw = $('#pw').val();
		let pw_check = $('#pw_check').val();
		let id_num_f = $('#id_num_f').val(); //주민번호 앞자리
		let id_num_b = $('#id_num_b').val(); //주민번호 뒷자리
		let team = $('team').val();

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
		//팀 선택 여부
		if(team === ''){
			alert('팀을 선택하세요.');
			return false;
		}

		//api로 보낼 데이터
		let senddata = new Object();
		senddata.id = id;
		senddata.pw = pw;
		senddata.id_num = id_num_f + id_num_b; //주민번호 합치기
		senddata.team = team;

		api('api_join_admin', senddata, function(output){
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
#admin_join {
	background-color: #ffffff;
	padding: 20px;
	border-radius: 10px;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	width: 450px;
	text-align: center;
}

#admin_join h2 {
	margin-bottom: 20px;
	font-size: 1.5em;
}

#admin_join input[type='text'],
#admin_join input[type='password'] {
	width: 100%;
	padding: 10px;
	margin: 10px 5px 10px 0;
	border: 1px solid #ccc;
	border-radius: 5px;
	margin-right: 0;
}

#admin_join button{
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

#admin_join button:hover{
	background-color: #0056b3;
}

#num_insert {
	display: flex;
	align-items: center;
}

#num_insert input[type='text'],
#num_insert input[type='password'] {
	width: calc(50% - 5px);
	margin-right: 5px;
}

/* 셀렉트박스 스타일 */
#team {
	width: 100%;
	padding: 5px;
	margin: 5px 0;
	border: 1px solid #ccc;
	border-radius: 5px;
	background-color: #fff;
	color: #333;
	font-size: 12px;
}
</style>
<title>관리자 회원가입</title>
<div id='admin_join'>
	<h2>관리자 회원가입<h2>
	<div>
		<input type='text' id='id' placeholder="아이디" />
		<br/>
		<input type='password' id='pw' placeholder="비밀번호" />
		<br/>
		<input type='password' id='pw_check' placeholder="비밀번호 확인" />
		<br/>
		<div id='num_insert'>
			<input type='text' id='id_num_f' placeholder="주민등록번호" />-
			<input type='password' id='id_num_b' />
		</div>
		<br/>
		<select id='team' name='team'>
			<option value="" selected disabled>팀 선택</option>
			<?php for($i=0; $i<$team_cnt; $i++){ ?>
			<option value="<?=$team[$i]['team_sid']?>">
				<?=$team[$i]['team_name']?>
			</option>
		<?php }?>
		</select>
		<br/>
		<button onclick="join()" />회원가입</button>
	</div>
</div>