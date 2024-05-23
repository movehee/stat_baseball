<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';
	//페이지
	$page = 1;
	if(isset($_POST['page']) === true){
		if($_POST['page'] !== '' && $_POST['page'] !== null){
			$page = $_POST['page'];
		}
	}
	//팀명
	$is_team_name = '';
	if(isset($_POST['is_team_name']) === true){
		if($_POST['is_team_name'] !== '' && $_POST['is_team_name'] !== null){
			$is_team_name = $_POST['is_team_name'];
		}
	}

	// 리그 정보 조회
	$league_sql = "SELECT sid, name FROM league_info;";
	$query_result = sql($league_sql);
	$league_data = select_process($query_result);

	// 팀 정보 테이블 조회
	$sql = "SELECT * FROM team_info";
	// 검색 필터 적용
	if($is_team_name !== ''){
		$sql .= " WHERE team_name like '%$is_team_name%';";
	}
	$query_result = sql($sql);
	$team = select_process($query_result);

	for($i=0; $i<$team['output_cnt']; $i++){
		$team_participant[$i] = $team[$i]['sid'];
	}
	$team_participant = count($team_participant);

	echo '<script>var team_participant = '.json_encode($team_participant).';</script>';
?>
<title>팀 정보</title>
<h2>팀 정보</h2>
<label><?=$team_participant?>팀 참가중</label>
<article id='search_area'>
	<section id='searchoption_left'>
		<div class='group'>
			<span class='search_field'>팀명</span>
			<input type='text' id='is_team_name' placeholder='팀명' autocomplete='off' value='<?=$is_team_name?>'/>
		</div>
	</section>
</article>
<div class='btn'>
	<button onclick='search();'>조회</button>
</div>

<div style='clear:both;'></div>
<hr />

<select key='league_key' id='league_key' onchange="select_team(this)">
	<option vlaue="" selected disabled>리그선택</option>
	<?php
	for($i=0; $i<$league_data['output_cnt']; $i++){
	?>
	<option value='<?=$league_data[$i]['sid']?>'><?=$league_data[$i]['name']?></option>
	<?php
	}
	?>
</select>
<div style='clear:both;'></div>

<section id="team_section" class="team_container">
	<table>
		<tbody>
			<tr class="team_column">
				<?php
					for($i=0; $i<$team['output_cnt']; $i++){
						 if(isset($team[$i])){
							$team_record = 0;
							if($team[$i]['team_record_win'] + $team[$i]['team_record_lose'] + $team[$i]['team_record_draw'] > 0){
								$team_record = $team[$i]['team_record_win'] / ($team[$i]['team_record_win'] + $team[$i]['team_record_lose'] + $team[$i]['team_record_draw']);
								$team_record = number_format($team_record, 3);
							}
				?>
					<td class="team_item">
						<!-- 이떄는 logo_path 컬럼을 추가하기 전이라 short_name을 사용했다. 이미지를 불러오려면 하나하나 적었어야했지만 이후 컬럼 추가 이후는 이 방법이 굉장히 불편하다고 생각했다. -->
						<div class="team_logo <?= $team[$i]['short_name']?>"></div>
						<div class="team_info">
							<div><?= $team[$i]['team_name']?></div>
							<div>리그 1위 횟수 : <?= $team[$i]['winner']?></div>
							<div><?= $team[$i]['team_record_win']?>승 <?= $team[$i]['team_record_draw']?>무 <?= $team[$i]['team_record_lose']?>패(<?=$team_record?>)</div>
						</div>
					</td>
				<?php
					}
				}
				?>
			</tr>
		</tbody>
	</table>
</section>
