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

	//선수 sql
	$sql = "SELECT sid, player_name, team_sid FROM player_info;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	for($i=0; $i<$query_result['output_cnt']; $i++){
		//선수sid === 선수명 매핑
		$player_name[$query_result[$i]['sid']] = $query_result[$i]['player_name'];
		//선수sid === 팀sid 매핑
		$player_team[$query_result[$i]['sid']] = $query_result[$i]['team_sid'];
	}

	//포지션 sql === 여기서 season_player_info로 한번에 만들고 implode로 구분해서 컬럼을 꺼냈으면 어땠을까? === 그냥 따로따로 구분해서 하는게 더 편할듯?
	$sql = "SELECT sid, name FROM position;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	$batter = array();
	$pitcher = array();
	for($i=0; $i<$query_result['output_cnt']; $i++){
		if($query_result[$i]['sid'] < 10){
			$batter[$i] = $query_result[$i]['sid'];
		}else{
			$pitcher[$i] = $query_result[$i]['sid'];
		}
		$position_name[$query_result[$i]['sid']] = $query_result[$i]['name'];
	}

	$season_info = '';
	if($batter){
		$season_info = 'season_batter_info';
	}else{
		$season_info = 'season_pitcher_info';
	}
	$sql = "SELECT * FROM $season_info WHERE season = '".__YEAR__."';"; 
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	for($i=0; $i<$query_result['output_cnt']; $i++){
		$season = $query_result[$i]['season'];
	}

	//fip를 위한 리그 상수 구하기 === 각 정보들 전부 더한거 구하기
	$sql = "SELECT sid, SUM(team_rs) AS total_runs, SUM(team_ip) AS total_ip, SUM(team_k) AS total_k, SUM(team_bb) AS total_bb, SUM(team_hbp) AS total_hbp, SUM(team_hr) AS total_hr FROM team_info GROUP BY sid;";
	$query_result = sql($sql);
	$team_data = select_process($query_result);

	//리그 기록 0 초기화
	$league_runs = 0;
	$league_ip = 0;
	$league_k = 0;
	$league_bb = 0;
	$league_hbp = 0;
	$league_hr = 0;
	$league_C_val = 0;
	if($team_data['output_cnt'] > 0){
		for($i=0; $i<$team_data['output_cnt']; $i++){
			$league_runs += $team_data[$i]['total_runs'];
			$league_ip += $team_data[$i]['total_ip'];
			$league_k += $team_data[$i]['total_k'];
			$league_bb += $team_data[$i]['total_bb'];
			$league_hbp += $team_data[$i]['total_hbp'];
			$league_hr += $team_data[$i]['total_hr'];
		}
	}
	//이닝이 0인 경우 리그 평균 자책점 0으로 설정
	if($league_ip === 0){
		$league_era = 0;
	}else{
		//이닝이 0이 아닌 경우 계산 실행
		//리그 평균 득점 계산
		$league_era = $league_runs / $league_ip;
		//리그 상수 C 계산(fip 계산시 사용)
		$league_C_val = ($league_era - ((12 * $league_hr + 3.2 * ($league_bb + $league_hbp) - 2 * $league_k) / $league_ip));
	}
	$league_C = round($league_C_val, 3);

	echo '<script>var team_name = '.json_encode($team_name).';</script>';
	echo '<script>var player_team = '.json_encode($player_team).';</script>';
	echo '<script>var player_name = '.json_encode($player_name).';</script>';
	echo '<script>var team_logo = '.json_encode($team_logo).';</script>';
	echo '<script>var league_C = '.json_encode($league_C).';</script>';
?>
<title>시즌 선수 순위</title>
<h2><?=$season?>시즌 선수 순위</h2>
<h3 id='position' onclick="click_position(1)">타자</h3>
<h3 id='position' onclick="click_position(2)">투수</h3>

<section id="team_section" class="team_container">
	<!-- 헤더 부분 onclick에서 계산식들은 sortStat()으로 통일 -->
	<table id="team_table">
		<thead class="team_row header">
			<tr>
				<th>순위</th>
				<th>팀명</th>
				<th>선수명</th>
				<th onclick="sortGames()">경기</th>
				<th onclick="sortAbs()">타수</th>
				<th onclick="sortHits()">안타</th>
				<th onclick="sortDouble()">2루타</th>
				<th onclick="sortTriple()">3루타</th>
				<th onclick="sortHomeRuns()">홈런</th>
				<th onclick="sortRbi()">타점</th>
				<th onclick="sortR()">득점</th>
				<th onclick="sortBb()">볼넷</th>
				<th onclick="sortSb()">도루</th>
				<th onclick="sortStat()">타율</th>
				<th onclick="sortStat()">출루율</th>
				<th onclick="sortStat()">장타율</th>
				<th onclick="sortStat()">출루율+장타율</th>
			</tr>
		</thead>
		<tbody id="batter_section">
			<tr>
				<td>포지션을 선택해주세요.</td>
			</tr>
		</tbody>
		<thead class="team_row header">
			<tr>
				<th>순위</th>
				<th>팀명</th>
				<th>선수명</th>
				<th onclick="sortGames()">경기</th>
				<th onclick="sortStat()">평균자책점</th>
				<th onclick="sortWin()">승리</th>
				<th onclick="sortLose()">패배</th>
				<th onclick="sortR()">실점</th>
				<th onclick="sortIp()">이닝</th>
				<th onclick="sortCg()">완투</th>
				<th onclick="sortSho()">완봉</th>
				<th onclick="sortSv()">세이브</th>
				<th onclick="sortQs()">퀄리티스타트</th>
				<th onclick="sortK()">삼진</th>
				<th onclick="sortBb()">볼넷</th>
				<th onclick="sortStat()">삼진/볼넷비율</th>
				<th onclick="sortStat()">이닝당 출루허용률</th>
			</tr>
		<tbody id="pitcher_section">
			<tr>
				<td>포지션을 선택해주세요.</td>
			<tr>
		</tbody>
	</table>
</section>