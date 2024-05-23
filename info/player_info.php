<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';
	//유효성 검사
	//페이지
	$page = 1;
	if(isset($_POST['page']) === true){
		if($_POST['page'] !== '' && $_POST['page'] !== null){
			$page = $_POST['page'];
		}
	}
	//선수명
	$is_player_name = '';
	if(isset($_POST['is_player_name']) === true){
		if($_POST['is_player_name'] !== '' && $_POST['is_player_name'] !== null){
			$is_player_name = $_POST['is_player_name'];
		}
	}
	//팀명
	$is_team_name = '';
	if(isset($_POST['is_team_name']) === true){
		if($_POST['is_team_name'] !== '' && $_POST['is_team_name'] !== null){
			$is_team_name = $_POST['is_team_name'];
		}
	}
	//포지션
	$is_position = '';
	if(isset($_POST['is_position']) === true){
		if($_POST['is_position'] !== '' && $_POST['is_position'] !== null){
			$is_position = $_POST['is_position'];
		}
	}
	//비고
	$is_note = '';
	if(isset($_POST['is_note']) === true){
		if($_POST['is_note'] !== '' && $_POST['is_note'] !== null){
			$is_note = $_POST['is_note'];
		}
	}
	// 팀 테이블 조회
	$sql = "SELECT sid, team_name, logo_path FROM team_info";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			//값: 팀sid => 팀명
			$team_name[$query_result[$i]['sid']] = $query_result[$i]['team_name'];
			//값: 팀명 => 팀sid
			$team_name_sid[$query_result[$i]['team_name']] = $query_result[$i]['sid'];
			//값: 팀sid => 팀 로고
			$team_logo[$query_result[$i]['sid']] = $query_result[$i]['logo_path'];
		}
	}

	// 포지션 테이블 조회
	$sql = "SELECT sid, name FROM position;";
	$query_result = sql($sql);
	$position_data = select_process($query_result);

	for($i=0; $i<$position_data['output_cnt']; $i++){
		$position[$position_data[$i]['sid']] = $position_data[$i]['name'];
	}

	// 선수 테이블 조회
	$sql = "SELECT sid, player_name, team_sid, position_sid, registration_date, note FROM player_info WHERE 1=1";
	if($is_player_name !== ''){
		$sql .= " AND player_name LIKE '%$is_player_name%'";
	}
	if($is_team_name !== ''){
		$sql .= " AND team_sid LIKE '%$team_name_sid[$is_team_name]%'";
	}
	if($is_note !== ''){
		$sql .= " AND note LIKE '%$is_note%'";
	}
	$sql .= " GROUP BY sid ORDER BY player_name ASC";

	$total_sql = $sql.';';

	// page 처리
	// 시작페이지 
	$search_start = ($page - 1) * 10;
	// 페이지설정 범위를 sql문으로 작성
	$sql .= " LIMIT $search_start, 10";

	$sql .= ";";

	$query_result = sql($sql);
	$data = select_process($query_result);

	// ***** 페이징 처리를 위한 영역 *****
	// 값이 있는 조건 sql만 쿼리 수행
	$result_query = sql($total_sql);
	
	// 수행 결과의 행 갯수 구하기
	$pagging_cnt = mysqli_num_rows($result_query);

	//행갯수 10으로 나누고 소숫점 올림( 페이지바의 갯수)
	$pagging_cnt = ceil($pagging_cnt / 10);

	
	//시작페이지(현재페이지 / 10 소숫점내림 *10 +1)
	$start_page = (floor($page / 10) * 10) + 1;

	//마지막페이지(시작페이지 +9)
	$end_page = $start_page + 9;

	// 만약 마지막페이지가 페이지갯수보다 클 경우 마지막페이지 = 페이지 갯수
	if($end_page > $pagging_cnt){
		$end_page = $pagging_cnt;
	}

	// 페이징 화살표
	//이전
	$prev = 1;
	// 현재페이지 -1 1보다 작을 때 이전페이지는 1
	if($page - 1 > 0){
		$prev = $page - 1;
	}
	//다음 
	$next = $page + 1;
	// 다음페이지가 총페이지보다 클 경우 다음페이지는 총페이지
	if($next > $pagging_cnt){
		$next = $pagging_cnt;
	}
?>
<title>선수 정보</title>
<h2>선수 정보</h2>

<!-- 선수정보 검색 필드 -->
<article id='search_area'>
	<section id='searchoption_left'>
		<div class='group'>
			<span class='search_field'>선수명</span>
			<input type='text' id='player_name' placeholder='선수명' autocomplete='off' value='<?=$is_player_name?>' />
		</div>
		<div class='group'>
			<span class='search_field'>팀명</span>
			<input type='text' id='team_name' placeholder='팀명' autocomplete='off' value='<?=$is_team_name?>' />
		</div>
	</section>
	<section id='searchoption_right'>
		<div class='group'>
			<span class='search_field'>비고</span>
			<input type='text' id='note' placeholder='비고' autocomplete='off' value='<?=$is_note?>' />
		</div>
	</section>
</article>
<div style='clear:both;'></div>
<hr>
<div class='btn'>
	<button onclick='search();'>조회</button>
</div>
<div style='clear:both;'></div>
<!-- 선수정보 테이블 -->
<section id='table_area'>
	<table>
		<colgroup>
			<col width='200px'>
			<col width='100px'>
			<col width='200px'>
			<col width='100px'>
			<col width='100px'>
		</colgroup>
		<thead>
			<tr>
				<th>선수명</th>
				<th>팀</th>
				<th>포지션</th>
				<th>등록일</th>
				<th>비고</th>
			</tr>
		</thead>
		<!-- 선수목록 -->
		<tbody>
		<?php for($i=0; $i<$data['output_cnt']; $i++){
		?>
			<tr>
				<td onclick='detail(<?=$data[$i]['sid']?>);'><img src = "<?= $team_logo[$data[$i]['team_sid']] ?>"><?=$data[$i]['player_name']?></td>
				<td><?=$team_name[$data[$i]['team_sid']]?></td>
				<td><?=$position[$data[$i]['position_sid']]?></td>
				<td><?=$data[$i]['registration_date']?></td>
				<td><?=$data[$i]['note']?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<!-- 페이징 -->
	<ul id='pagging'>
		<li onclick='search(<?=$prev?>);'>이전</li>
		<?php for($i=$start_page; $i<=$end_page; $i++){ ?>
			<li <?=($i == $page) ? 'id="this_page"' : '' ?> onclick='search(<?=$i?>);'><?=$i?></li>
		<?php } ?>
		<li onclick='search(<?=$next?>);'>다음</li>
	</ul>
</section>