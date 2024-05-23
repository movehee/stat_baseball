<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//리그 선택 sql
	$sql = "SELECT sid, name FROM league_info;";
	$query_result = sql($sql);
	$league_data = select_process($query_result);

	//팀 이름 sql
	$sql = "SELECT sid, team_name, logo_path FROM team_info;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	for($i=0; $i<$query_result['output_cnt']; $i++){
		//팀sid === 팀명 매핑
		$team_name[$query_result[$i]['sid']] = $query_result[$i]['team_name'];
		//팀sid === 팀 로고 매핑
		$team_logo[$query_result[$i]['sid']] = $query_result[$i]['logo_path'];
	}

	//시즌 정보 sql
	$sql = "SELECT season FROM season_team_info WHERE season='".__YEAR__."';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	for($i=0; $i<$query_result['output_cnt']; $i++){
		$season = $query_result[$i]['season'];
	}

	echo '<script>var team_name = '.json_encode($team_name).';</script>';
	echo '<script>var team_logo = '.json_encode($team_logo).';</script>';
?>
<title>시즌 팀 순위</title>
<h2><?=$season?>시즌 팀 순위</h2>
<select key='league_key' id='league_key' onchange="select_league(this)">
	<option value="" selected disabled>리그선택</option>
	<?php
	for($i=0; $i<$league_data['output_cnt']; $i++){ ?>
	<option value='<?=$league_data[$i]['sid']?>'><?=$league_data[$i]['name']?></option>
	<?php } ?>
</select>

<div style='clear:both;'></div>
<hr />

<secton id="team_section" class="team_container">
	<div class="team_table">
		<div class="team_row header">
			<div class="team_cell">순위</div>
			<div class="team_cell">팀 이름</div>
			<div class="team_cell">경기</div>
			<div class="team_cell">승점</div>
			<div class="team_cell">승리</div>
			<div class="team_cell">무승부</div>
			<div class="team_cell">패배</div>
			<div class="team_cell">승률</div>
			<div class="team_cell">타수</div>
			<div class="team_cell">안타</div>
			<div class="team_cell">2루타</div>
			<div class="team_cell">3루타</div>
			<div class="team_cell">홈런</div>
			<div class="team_cell">득점</div>
			<div class="team_cell">도루</div>
			<div class="team_cell">이닝</div>
			<div class="team_cell">삼진</div>
			<div class="team_cell">볼넷</div>
			<div class="team_cell">세이브</div>
			<div class="team_cell">실책</div>
			<div class="team_cell">방어율</div>
			<div class="team_cell">타율</div>
		</div>
		<div>
			리그를 선택하세요.
		</div>
	</div>
</secton>