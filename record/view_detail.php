<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	if(isset($_POST['game_sid']) === false){
		nowexit(false, '기록이 없습니다.');
	}
	//게임sid 가져오기
	$game_sid = $_POST['game_sid'];

	if(isset($_POST['game_date']) === false){
		nowexit(false, '기록이 없습니다.');
	}
	//게임 날짜 가져오기
	$game_date = $_POST['game_date'];

	if(isset($_POST['home_team_sid']) === false){
		nowexit(false, '기록이 없습니다.');
	}
	//홈팀 sid 가져오기
	$home_team_sid = $_POST['home_team_sid'];

	if(isset($_POST['away_team_sid']) === false){
		nowexit(false, '기록이 없습니다.');
	}
	//어웨이팀 sid 가져오기
	$away_team_sid = $_POST['away_team_sid'];

	if(isset($_POST['league_sid']) === false){
		nowexit(false, '기록이 없습니다.');
	}
	//리그 sid 가져오기
	$league_sid = $_POST['league_sid'];

	//포지션 정보 가져오기
	$sql = "SELECT sid, name FROM position;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$player_position[$query_result[$i]['sid']] = $query_result[$i]['name'];
		}
	}

	//홈팀의 이름 가져오기
	$sql = "SELECT sid, team_name FROM team_info WHERE sid = '$home_team_sid';";
	$query_result_home = sql($sql);
	$query_result_home = select_process($query_result_home);

	if($query_result_home['output_cnt'] > 0){
		$home_team_name = $query_result_home[0]['team_name'];
	}

	//어웨이팀의 이름 가져오기
	$sql = "SELECT sid, team_name FROM team_info WHERE sid = '$away_team_sid';";
	$query_result_away = sql($sql);
	$query_result_away = select_process($query_result_away);

	if($query_result_away['output_cnt'] > 0){
		$away_team_name = $query_result_away[0]['team_name'];
	}

	//홈팀 선수명 가져오기
	$sql = "SELECT sid, player_name, team_sid, position_sid FROM player_info WHERE team_sid ='$home_team_sid';";
	$query_result = sql($sql);
	$home_team = select_process($query_result);

	if($home_team['output_cnt'] > 0){
		for($i=0; $i<$home_team['output_cnt']; $i++){
			$player_name[$home_team[$i]['sid']]	= $home_team[$i]['player_name'];
			$position_name[$home_team[$i]['sid']] = $home_team[$i]['position_sid'];
		}
	}

	//어웨이팀 선수명 가져오기
	$sql = "SELECT sid, player_name, team_sid, position_sid FROM player_info WHERE team_sid ='$away_team_sid';";
	$query_result = sql($sql);
	$away_team = select_process($query_result);

	if($away_team['output_cnt'] > 0){
		for($i=0; $i<$away_team['output_cnt']; $i++){
			$player_name[$away_team[$i]['sid']]	= $away_team[$i]['player_name'];
			$position_name[$away_team[$i]['sid']] = $away_team[$i]['position_sid'];
		}
	}

	//홈팀 타자 스텟
	$sql="SELECT * FROM batter_game_stat WHERE game_sid = $game_sid AND home_away = '0';";
	$query_result = sql($sql);
	$home_batter_stat = select_process($query_result);

	//원정팀 타자 스텟
	$sql="SELECT * FROM batter_game_stat WHERE game_sid = $game_sid AND home_away = '1';";
	$query_result = sql($sql);
	$away_batter_stat = select_process($query_result);

	//홈팀 투수 스텟
	$sql="SELECT * FROM pitcher_game_stat WHERE game_sid = $game_sid AND home_away = '0';";
	$query_result = sql($sql);
	$home_pitcher_stat = select_process($query_result);

	//원정팀 투수 스텟
	$sql="SELECT * FROM pitcher_game_stat WHERE game_sid = $game_sid AND home_away = '1';";
	$query_result = sql($sql);
	$away_pitcher_stat = select_process($query_result);


?>
<title>경기 상세보기</title>
<h2>경기 상세보기</h2>
<?php if ($home_batter_stat['output_cnt'] > 0) { ?>
<section id='home_team_records'>
<h3><?= $home_team_name?> 타자 기록</h3>
<!-- 수평 스크롤 -->
<div style="overflow-x: auto;">
<table border="1">
	<thead>
		<tr>
			<th>타순</th>
			<th>선수명</th>
			<th>타석</th>
			<th>타수</th>
			<th>안타</th>
			<th>2루타</th>
			<th>3루타</th>
			<th>홈런</th>
			<th>득점</th>
			<th>타점</th>
			<th>삼진</th>
			<th>볼넷</th>
			<th>사구</th>
			<th>땅볼아웃</th>
			<th>뜬공아웃</th>
			<th>희생플라이</th>
			<th>희생번트</th>
			<th>병살타</th>
			<th>도루</th>
			<th>실책</th>
			<th>노트</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		for($i=0; $i<$home_batter_stat['output_cnt']; $i++){ 
			$batter = $home_batter_stat[$i];
	?>
		<tr>
			<td><?= $i + 1 ?></td>
			<td onclick='player(<?= $batter['player_sid']?>)'><?= $player_name[$batter['player_sid']] ?>(<?= $player_position[$position_name[$batter['player_sid']]] ?>)</td>
			<td><?= $batter['pa'] ?></td>
			<td><?= $batter['ab'] ?></td>
			<td><?= $batter['hits'] ?></td>
			<td><?= $batter['double_hits'] ?></td>
			<td><?= $batter['triple_hits'] ?></td>
			<td><?= $batter['hr'] ?></td>
			<td><?= $batter['r'] ?></td>
			<td><?= $batter['rbi'] ?></td>
			<td><?= $batter['so'] ?></td>
			<td><?= $batter['bb'] ?></td>
			<td><?= $batter['hbp'] ?></td>
			<td><?= $batter['gb'] ?></td>
			<td><?= $batter['fb'] ?></td>
			<td><?= $batter['sf'] ?></td>
			<td><?= $batter['sh'] ?></td>
			<td><?= $batter['gdp'] ?></td>
			<td><?= $batter['sb'] ?></td>
			<td><?= $batter['error'] ?></td>
			<td><?= $batter['note'] ?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<h3><?= $home_team_name ?> 투수 기록</h3>
	<table border="1">
		<thead>
			<tr>
				<th>선수명</th>
				<th>승</th>
				<th>패</th>
				<th>실점</th>
				<th>이닝</th>
				<th>완투</th>
				<th>완봉</th>
				<th>세이브</th>
				<th>퀄리티스타트</th>
				<th>상대타자 수</th>
				<th>땅볼</th>
				<th>뜬공</th>
				<th>내야 뜬공</th>
				<th>내야 안타</th>
				<th>피홈런</th>
				<th>삼진</th>
				<th>볼넷</th>
				<th>실책</th>
				<th>피안타</th>
				<th>투구 수</th>
				<th>고의사구</th>
				<th>자책점</th>
				<th>희생플라이</th>
				<th>사사구</th>
				<th>노트</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		for($i=0; $i<$home_pitcher_stat['output_cnt']; $i++){ 
			$pitcher = $home_pitcher_stat[$i];
		?>
			<tr>
				<td onclick='player(<?= $pitcher['player_sid']?>)'><?= $player_name[$pitcher['player_sid']] ?>(<?= $player_position[$position_name[$pitcher['player_sid']]] ?>)</td>
				<td><?= $pitcher['win'] ?></td>
				<td><?= $pitcher['lose'] ?></td>
				<td><?= $pitcher['r'] ?></td>
				<td><?= $pitcher['ip'] ?></td>
				<td><?= $pitcher['cg'] ?></td>
				<td><?= $pitcher['sho'] ?></td>
				<td><?= $pitcher['sv'] ?></td>
				<td><?= $pitcher['qs'] ?></td>
				<td><?= $pitcher['pa'] ?></td>
				<td><?= $pitcher['gb'] ?></td>
				<td><?= $pitcher['fb'] ?></td>
				<td><?= $pitcher['iffb'] ?></td>
				<td><?= $pitcher['ifh'] ?></td>
				<td><?= $pitcher['hr'] ?></td>
				<td><?= $pitcher['k'] ?></td>
				<td><?= $pitcher['bb'] ?></td>
				<td><?= $pitcher['error'] ?></td>
				<td><?= $pitcher['hits'] ?></td>
				<td><?= $pitcher['pitches'] ?></td>
				<td><?= $pitcher['ibb'] ?></td>
				<td><?= $pitcher['er'] ?></td>
				<td><?= $pitcher['sf'] ?></td>
				<td><?= $pitcher['hbp'] ?></td>
				<td><?= $pitcher['note'] ?></td>
			</tr>
		<?php 
		}
		?>
		</tbody>
	</table>
</div>
</section>
<!-- 어웨이 -->
<section id='away_team_records'>
<h3><?= $away_team_name?> 타자 기록</h3>
<!-- 수평 스크롤 -->
<div style="overflow-x: auto;">
<table border="1">
	<thead>
		<tr>
			<th>타순</th>
			<th>선수명</th>
			<th>타석</th>
			<th>타수</th>
			<th>안타</th>
			<th>2루타</th>
			<th>3루타</th>
			<th>홈런</th>
			<th>득점</th>
			<th>타점</th>
			<th>삼진</th>
			<th>볼넷</th>
			<th>사구</th>
			<th>땅볼아웃</th>
			<th>뜬공아웃</th>
			<th>희생플라이</th>
			<th>희생번트</th>
			<th>병살타</th>
			<th>도루</th>
			<th>실책</th>
			<th>노트</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	for($i=0; $i<$away_batter_stat['output_cnt']; $i++){ 
		$batter = $away_batter_stat[$i];
	?>
		<tr>
			<td><?= $i + 1 ?></td>
			<td onclick='player(<?= $batter['player_sid']?>)'><?= $player_name[$batter['player_sid']] ?>(<?= $player_position[$position_name[$batter['player_sid']]] ?>)</td>
			<td><?= $batter['pa'] ?></td>
			<td><?= $batter['ab'] ?></td>
			<td><?= $batter['hits'] ?></td>
			<td><?= $batter['double_hits'] ?></td>
			<td><?= $batter['triple_hits'] ?></td>
			<td><?= $batter['hr'] ?></td>
			<td><?= $batter['r'] ?></td>
			<td><?= $batter['rbi'] ?></td>
			<td><?= $batter['so'] ?></td>
			<td><?= $batter['bb'] ?></td>
			<td><?= $batter['hbp'] ?></td>
			<td><?= $batter['gb'] ?></td>
			<td><?= $batter['fb'] ?></td>
			<td><?= $batter['sf'] ?></td>
			<td><?= $batter['sh'] ?></td>
			<td><?= $batter['gdp'] ?></td>
			<td><?= $batter['sb'] ?></td>
			<td><?= $batter['error'] ?></td>
			<td><?= $batter['note'] ?></td>
		</tr>
	<?php }?>
	</tbody>
</table>
<h3><?= $away_team_name?> 투수 기록</h3>
	<table border="1">
		<thead>
			<tr>
				<th>선수명</th>
				<th>승</th>
				<th>패</th>
				<th>실점</th>
				<th>이닝</th>
				<th>완투</th>
				<th>완봉</th>
				<th>세이브</th>
				<th>퀄리티스타트</th>
				<th>상대타자 수</th>
				<th>땅볼</th>
				<th>뜬공</th>
				<th>내야 뜬공</th>
				<th>내야 안타</th>
				<th>피홈런</th>
				<th>삼진</th>
				<th>볼넷</th>
				<th>실책</th>
				<th>피안타</th>
				<th>투구 수</th>
				<th>고의사구</th>
				<th>자책점</th>
				<th>희생플라이</th>
				<th>사사구</th>
				<th>노트</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		for($i=0; $i<$away_pitcher_stat['output_cnt']; $i++){ 
			$pitcher = $away_pitcher_stat[$i];
		?>
			<tr>
				<td onclick='player(<?= $pitcher['player_sid']?>)'><?= $player_name[$pitcher['player_sid']] ?>(<?= $player_position[$position_name[$pitcher['player_sid']]] ?>)</td>
				<td><?= $pitcher['win'] ?></td>
				<td><?= $pitcher['lose'] ?></td>
				<td><?= $pitcher['r'] ?></td>
				<td><?= $pitcher['ip'] ?></td>
				<td><?= $pitcher['cg'] ?></td>
				<td><?= $pitcher['sho'] ?></td>
				<td><?= $pitcher['sv'] ?></td>
				<td><?= $pitcher['qs'] ?></td>
				<td><?= $pitcher['pa'] ?></td>
				<td><?= $pitcher['gb'] ?></td>
				<td><?= $pitcher['fb'] ?></td>
				<td><?= $pitcher['iffb'] ?></td>
				<td><?= $pitcher['ifh'] ?></td>
				<td><?= $pitcher['hr'] ?></td>
				<td><?= $pitcher['k'] ?></td>
				<td><?= $pitcher['bb'] ?></td>
				<td><?= $pitcher['error'] ?></td>
				<td><?= $pitcher['hits'] ?></td>
				<td><?= $pitcher['pitches'] ?></td>
				<td><?= $pitcher['ibb'] ?></td>
				<td><?= $pitcher['er'] ?></td>
				<td><?= $pitcher['sf'] ?></td>
				<td><?= $pitcher['hbp'] ?></td>
				<td><?= $pitcher['note'] ?></td>
			</tr>
		<?php 
		}
		?>
		</tbody>
	</table>
</div>
</section>
<?php } else { ?>
<p> 경기 업데이트 전 입니다.</p>
<?php } ?>