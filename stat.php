<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

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
	$sql = "SELECT sid, player_name, team_sid, position_sid FROM player_info;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	for($i=0; $i<$query_result['output_cnt']; $i++){
		//선수sid === 선수명 매핑
		$player_name[$query_result[$i]['sid']] = $query_result[$i]['player_name'];
		//선수sid === 팀sid 매핑
		$player_team[$query_result[$i]['sid']] = $query_result[$i]['team_sid'];
	}

	//포지션 sql
	$sql = "SELECT sid, name FROM position;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	for($i=0; $i<$query_result['output_cnt']; $i++){
		//포지션 매핑
		$position[$query_result[$i]['sid']] = $query_result[$i]['name'];
	}

	//리그 테이블 조회
	$sql = "SELECT sid, name FROM league_info;";
	$query_result = sql($sql);
	$league_result = select_process($query_result);

	for($i=0; $i<$league_result['output_cnt']; $i++){
		//리그sid === 리그명 매핑
		$league_name[$league_result[$i]['sid']] = $league_result[$i]['name'];
	}

	//최근 경기 테이블 조회
	$sql = "SELECT game_date, home_team_sid, away_team_sid, home_team_score, away_team_score FROM game_info ORDER BY game_date DESC LIMIT 5;";
	$query_result = sql($sql);
	$game_result = select_process($query_result);

	//토요 시즌 팀 순위 테이블 조회
	$sql = "SELECT team_sid, league_sid, season, game, win, lose, draw, ab, hits, hr, r, sb, sv, k, bb, ip, er FROM season_team_info WHERE league_sid = '1' AND season ='".__YEAR__."' ORDER BY win DESC LIMIT 5;";
	$query_result = sql($sql);
	$sat_result = select_process($query_result);

	for($i=0; $i<$sat_result['output_cnt']; $i++){
		$season = $sat_result[$i]['season'];
	}

	//일요 시즌 팀 순위 테이블 조회
	$sql = "SELECT team_sid, league_sid, season, game, win, lose, draw, ab, hits, hr, r, sb, sv, k, bb, ip, er FROM season_team_info WHERE league_sid = '2' AND season ='".__YEAR__."' ORDER BY win DESC LIMIT 5;";
	$query_result = sql($sql);
	$sun_result = select_process($query_result);

	//타자 시즌 데이터 테이블 조회
	$sql = "SELECT player_sid ,game, pa, ab, hits, double_hits, triple_hits, hr, r, rbi, bb, hbp, gb, fb, sf, sh, gdp, k, sb, error FROM season_batter_info WHERE season = '".__YEAR__."' ORDER BY game DESC LIMIT 10;";
	$query_result = sql($sql);
	$batter_result = select_process($query_result);

	//투수 시즌 데이터 테이블 조회
	$sql = "SELECT player_sid ,game, win, lose, r, ip, cg, sho, r, ip, sv, qs, pa, gb, fb, iffb, ifh, hr, k, bb,error, hits, pitches, ibb, er, sf, hbp FROM season_pitcher_info WHERE season = '".__YEAR__."' ORDER BY game DESC LIMIT 10;";
	$query_result = sql($sql);
	$pitcher_result = select_process($query_result);

?>
<title>스텟 베이스볼</title>
<section>
	<h3>최근 경기</h3>
	<a onclick="render('record/recent_matches')">더 보기</a>
	<table border="1">
		<thead>
			<tr>
				<th>경기 날짜</th>
				<th>HOME</th>
				<th>score</th>
				<th>vs</th>
				<th>score</th>
				<th>AWAY</th>
			</tr>
		</thead>
		<tbody>
		<?php for($i=0; $i<$game_result['output_cnt']; $i++){
			$game = $game_result[$i];
		?>
			<tr>
				<td><?= $game['game_date'] ?></td>
				<td><?= $team_name[$game['home_team_sid']] ?></td>
				<td><?= $game['home_team_score'] ?></td>
				<td>vs</td>
				<td><?= $game['away_team_score'] ?></td>
				<td><?= $team_name[$game['away_team_sid']] ?></td>
			</tr>
		<?php }?>
		</tbody>
	</table>
</section>
<section>
	<h3><?= $season ?>시즌 타자 순위</h3>	
	<a onclick="render('rank/player_rank')">더 보기</a>
	<table border="1">
		<thead>
			<tr>
				<th>선수</th>
				<th>경기수</th>
				<th>타수</th>
				<th>안타</th>
				<th>2루타</th>
				<th>3루타</th>
				<th>홈런</th>
				<th>타점</th>
				<th>볼넷</th>
				<th>병살타</th>
				<th>삼진</th>
				<th>도루</th>
				<th>실책</th>
				<th>타율</th>
				<th>출루율</th>
				<th>장타율</th>
				<th>OPS</th>
			</tr>
		</thead>
		<tbody>
		<?php for($i = 0; $i < $batter_result['output_cnt']; $i++){ 
			$batter = $batter_result[$i];

			// 파생 데이터 계산
			$avg = $batter['ab'] > 0 ? $batter['hits'] / $batter['ab'] : 0;
			$obp = ($batter['ab'] + $batter['bb'] + $batter['hbp'] + $batter['sf']) > 0 ? ($batter['hits'] + $batter['bb'] + $batter['hbp']) / ($batter['ab'] + $batter['bb'] + $batter['hbp'] + $batter['sf']) : 0;
			$total_bases = $batter['hits'] + (2 * $batter['double_hits']) + (3 * $batter['triple_hits']) + (4 * $batter['hr']);
			$slg = $batter['ab'] > 0 ? $total_bases / $batter['ab'] : 0;
			$ops = $obp + $slg;
		?>
			<tr>
				<td><img src = "<?= $team_logo[$player_team[$batter['player_sid']]] ?>"><?= $player_name[$batter['player_sid']] ?></td>
				<td><?= $batter['game'] ?></td>
				<td><?= $batter['ab'] ?></td>
				<td><?= $batter['hits'] ?></td>
				<td><?= $batter['double_hits'] ?></td>
				<td><?= $batter['triple_hits'] ?></td>
				<td><?= $batter['hr'] ?></td>
				<td><?= $batter['rbi'] ?></td>
				<td><?= $batter['bb'] ?></td>
				<td><?= $batter['gdp'] ?></td>
				<td><?= $batter['k'] ?></td>
				<td><?= $batter['sb'] ?></td>
				<td><?= $batter['error'] ?></td>
				<td><?= number_format($avg, 3) ?></td>
				<td><?= number_format($obp, 3) ?></td>
				<td><?= number_format($slg, 3) ?></td>
				<td><?= number_format($ops, 3) ?></td>
			</tr>
		<?php }?>
		</tbody>
	</table>
</section>
<section>
	<h3><?= $season ?>시즌 투수 순위</h3>	
	<a onclick="render('rank/player_rank')">더 보기</a>
	<table border="1">
		<thead>
			<tr>
				<th>선수</th>
				<th>경기수</th>
				<th>승</th>
				<th>패</th>
				<th>세이브</th>
				<th>이닝</th>
				<th>삼진</th>
				<th>볼넷</th>
				<th>피홈런</th>
				<th>실책</th>
				<th>자책점</th>
				<th>WHIP</th>
				<th>K/9</th>
				<th>BB/9</th>
			</tr>
		</thead>
		<tbody>
		<?php for($i=0; $i<$pitcher_result['output_cnt']; $i++){ 
			$pitcher = $pitcher_result[$i];
			$era = $pitcher['ip'] > 0 ? ($pitcher['er'] * 9) / $pitcher['ip'] : 0;
			$whip = $pitcher['ip'] > 0 ? ($pitcher['bb'] + $pitcher['hits']) / $pitcher['ip'] : 0;
			$k_9 = $pitcher['ip'] > 0 ? ($pitcher['k'] * 9) / $pitcher['ip'] : 0;
			$bb_9 = $pitcher['ip'] > 0 ? ($pitcher['bb'] * 9) / $pitcher['ip'] : 0;
		?>
			<tr>
				<td><img src = "<?= $team_logo[$player_team[$pitcher['player_sid']]] ?>"><?= $player_name[$pitcher['player_sid']] ?></td>
				<td><?= $pitcher['game'] ?></td>
				<td><?= $pitcher['win'] ?></td>
				<td><?= $pitcher['lose'] ?></td>
				<td><?= $pitcher['sv'] ?></td>
				<td><?= $pitcher['ip'] ?></td>
				<td><?= $pitcher['k'] ?></td>
				<td><?= $pitcher['bb'] ?></td>
				<td><?= $pitcher['hr'] ?></td>
				<td><?= $pitcher['error'] ?></td>
				<td><?= number_format($era, 2) ?></td>
				<td><?= number_format($whip, 2) ?></td>
				<td><?= number_format($k_9, 2) ?></td>
				<td><?= number_format($bb_9, 2) ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</section>
<section>
	<h3><?= $season ?>시즌 <?= $league_name[$sat_result[0]['league_sid']] ?> 팀 순위</h3>
	<a onclick="render('rank/team_rank')">더 보기</a>
	<table border="1">
		<thead>
			<tr>
				<th>순위</th>
				<th>팀명</th>
				<th>승</th>
				<th>무</th>
				<th>패</th>
				<th>안타</th>
				<th>홈런</th>
				<th>득점</th>
				<th>도루</th>
				<th>세이브</th>
				<th>삼진</th>
				<th>볼넷</th>
				<th>이닝</th>
				<th>타율</th>
				<th>자책점</th>
			</tr>
		</thead>
		<tbody>
		<?php for($i=0; $i<$sat_result['output_cnt']; $i++){ 
			$sat = $sat_result[$i];
			$avg = $sat['ab'] > 0 ? $sat['hits'] / $sat['ab'] : 0;
			$era = $sat['ip'] > 0 ? ($sat['er'] * 9) / $sat['ip'] : 0;
		?>
			<tr>
				<td><?= $i + 1 ?></td>
				<td><?= $team_name[$sat['team_sid']] ?></td>
				<td><?= $sat['win'] ?></td>
				<td><?= $sat['draw'] ?></td>
				<td><?= $sat['lose'] ?></td>
				<td><?= $sat['hits'] ?></td>
				<td><?= $sat['hr'] ?></td>
				<td><?= $sat['r'] ?></td>
				<td><?= $sat['sb'] ?></td>
				<td><?= $sat['sv'] ?></td>
				<td><?= $sat['k'] ?></td>
				<td><?= $sat['bb'] ?></td>
				<td><?= $sat['ip'] ?></td>
				<td><?= number_format($avg, 3) ?></td>
				<td><?= number_format($era, 2) ?></td>		
			</tr>
		<?php } ?>
		</tbody>
	</table>
</section>
<section>
	<h3><?= $season ?>시즌 <?= $league_name[$sun_result[0]['league_sid']] ?> 팀 순위</h3>
	<a onclick="render('rank/team_rank')">더 보기</a>
	<table border="1">
		<thead>
			<tr>
				<th>순위</th>
				<th>팀명</th>
				<th>승</th>
				<th>무</th>
				<th>패</th>
				<th>안타</th>
				<th>홈런</th>
				<th>득점</th>
				<th>도루</th>
				<th>세이브</th>
				<th>삼진</th>
				<th>볼넷</th>
				<th>이닝</th>
				<th>타율</th>
				<th>자책점</th>
			</tr>
		</thead>
		<tbody>
		<?php for($i=0; $i<$sun_result['output_cnt']; $i++){
			$sun = $sun_result[$i];
			$avg = $sun['ab'] > 0 ? $sun['hits'] / $sun['ab'] : 0;
			$era = $sun['ip'] > 0 ? ($sun['er'] * 9) / $sun['ip'] : 0;

		?>
			<tr>
				<td><?= $i + 1 ?></td>
				<td><?= $team_name[$sun['team_sid']] ?></td>
				<td><?= $sun['win'] ?></td>
				<td><?= $sun['draw'] ?></td>
				<td><?= $sun['lose'] ?></td>
				<td><?= $sun['hits'] ?></td>
				<td><?= $sun['hr'] ?></td>
				<td><?= $sun['r'] ?></td>
				<td><?= $sun['sb'] ?></td>
				<td><?= $sun['sv'] ?></td>
				<td><?= $sun['k'] ?></td>
				<td><?= $sun['bb'] ?></td>
				<td><?= $sun['ip'] ?></td>
				<td><?= number_format($avg, 3) ?></td>
				<td><?= number_format($era, 2) ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</section>