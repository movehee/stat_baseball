<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//게임sid 가져오기
	$game_sid = $_POST['game_sid'];
	//게임 날짜 가져오기
	$game_date = $_POST['game_date'];
	//홈팀 sid 가져오기
	$home_team_sid = $_POST['home_team_sid'];
	//어웨이팀 sid 가져오기
	$away_team_sid = $_POST['away_team_sid'];
	//리그 sid 가져오기
	$league_sid = $_POST['league_sid'];

	//유저 데이터 sql로 관리자 권한 sid 찾기
	$sql = "SELECT is_admin FROM user_data WHERE team_sid ='".__TEAM_SID__."';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$admin = (int)$query_result[$i]['is_admin'];
		}
	}
	//포지션 정보 가져오기
	$sql = "SELECT sid, name FROM position;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);
	if($query_result['output_cnt'] > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$player_position[$query_result[$i]['sid']] = $query_result[$i]['name'];
		}
	}

	//가져온 게임 sid로 게임 sql
	$sql = "SELECT home_team_sid, away_team_sid, game_date, home_team_score, away_team_score FROM game_info WHERE sid ='$game_sid';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$home_team_sid = $query_result[$i]['home_team_sid'];
			$away_team_sid = $query_result[$i]['away_team_sid'];
		}
	}

	// 홈팀의 이름 가져오기
	$sql = "SELECT sid, team_name FROM team_info WHERE sid = '$home_team_sid';";
	$query_result_home = sql($sql);
	$query_result_home = select_process($query_result_home);

	if($query_result_home['output_cnt'] > 0){
		$home_team_name = $query_result_home[0]['team_name'];
	}

	// 어웨이팀의 이름 가져오기
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

	$home_player_sid = array();
	if($home_team['output_cnt'] > 0){
		for($i=0; $i<$home_team['output_cnt']; $i++){
			$player_name[$home_team[$i]['sid']]	= $home_team[$i]['player_name'];
		}
	}

	//어웨이팀 선수명 가져오기
	$sql = "SELECT sid, player_name, team_sid, position_sid FROM player_info WHERE team_sid ='$away_team_sid';";
	$query_result = sql($sql);
	$away_team = select_process($query_result);

	$away_player_sid = array();
	if($away_team['output_cnt'] > 0){
		for($i=0; $i<$away_team['output_cnt']; $i++){
			$player_name[$away_team[$i]['sid']]	= $away_team[$i]['player_name'];
		}
	}

	// 홈팀 타자 배열 생성
	$home_batters = array();
	for($i=0; $i<$home_team['output_cnt']; $i++){
		if($home_team[$i]['position_sid'] < 10){
			$batter = array(
			'name' => '', 'pa' => '', 'ab' => '', 'hits' => '', 'double_hits' => '', 'triple_hits' => '', 'hr' => '', 'r' => '', 'rbi' => '', 'so' => '', 'bb' => '', 'hbp' => '', 'gb' => '', 'fb' => '', 'sf' => '', 'sh' => '', 'gdp' => '', 'sb' => '', 'error' => '', 'note' => ''
			);
			// 배열에 추가
			array_push($home_batters, $batter);
		}
	}
	// 어웨이 타자 배열 생성
	$away_batters = array();
	for($i=0; $i<$away_team['output_cnt']; $i++){
		if($away_team[$i]['position_sid'] < 10){
			$batter = array(
			'name' => '', 'pa' => '', 'ab' => '', 'hits' => '', 'double_hits' => '', 'triple_hits' => '', 'hr' => '', 'r' => '', 'rbi' => '', 'so' => '', 'bb' => '', 'hbp' => '', 'gb' => '', 'fb' => '', 'sf' => '', 'sh' => '', 'gdp' => '', 'sb' => '', 'error' => '', 'note' => ''
			);
			// 배열에 추가
			array_push($away_batters, $batter);
		}
	}
	//홈 투수 배열 생성
	$home_pitchers = array();
	for($i=0; $i<$home_team['output_cnt']; $i++){
		if($home_team[$i]['position_sid'] > 9){
			$pitcher = array(
			'win' => '', 'lose' => '', 'r' => '', 'ip' => '', 'cg' => '', 'sho' => '', 'sv' => '', 'qs' => '', 'pa' => '', 'gb' => '', 'fb' => '', 'iffb' => '', 'ifh' => '', 'hr' => '', 'k' => '', 'bb' => '', 'error' => '', 'note' => ''
			);
			// 배열에 추가
			array_push($home_pitchers, $batter);
		}
	}
	//어웨이 투수 배열 생성
	$away_pitchers = array();
	for($i=0; $i<$away_team['output_cnt']; $i++){
		if($away_team[$i]['position_sid'] > 9){
			$pitcher = array(
			'win' => '', 'lose' => '', 'r' => '', 'ip' => '', 'cg' => '', 'sho' => '', 'sv' => '', 'qs' => '', 'pa' => '', 'gb' => '', 'fb' => '', 'iffb' => '', 'ifh' => '', 'hr' => '', 'k' => '', 'bb' => '', 'error' => '', 'note' => ''
			);
			// 배열에 추가
			array_push($away_pitchers, $batter);
		}
	}
	//js로 json형식 정보 보내기
	echo '<script> var home_batters = ' . json_encode($home_batters) . '</script>';
	echo '<script> var away_batters = ' . json_encode($away_batters) . '</script>';
	echo '<script> var home_pitchers = ' . json_encode($home_pitchers) . '</script>';
	echo '<script> var away_pitchers = ' . json_encode($away_pitchers) . '</script>';
	echo '<script> var game_date = ' . json_encode($game_date) . '</script>';
	echo '<script> var game_sid = ' . json_encode($game_sid) . '</script>';
	echo '<script> var league_sid = ' . json_encode($league_sid) . '</script>';
	echo '<script> var home_team_sid = ' . json_encode($home_team_sid) . '</script>';
	echo '<script> var away_team_sid = ' . json_encode($away_team_sid) . '</script>';

?>
<title>게임 기록</title>
<h2>게임 기록</h2><a><?=$game_date?></a>
<hr>
<div class='btn'>
	<?php 
	// admin이 -1일 경우에만 표시
	if($admin === -1){
	?>
	<button onclick="registration()">등록</button>
	<?php
	}
	?>
</div>
<div style='clear:both;'></div>
<!-- 홈 -->
<section id='home_team_records'>
<h3>홈팀 타자 기록</h3>
<!-- 수평 스크롤 -->
<div style="overflow-x: auto;">
<table border="1">
	<thead>
		<tr>
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
		<?php for($i=0; $i<=8; $i++){ ?>
		<tr>
			<td>
				<select key="home_batter_<?php echo $i; ?>_name" onchange="is_onchange(this, <?php echo $i; ?>)">
					<option selected disabled value="">선수를 선택하세요</option>
		<?php
			if($home_team['output_cnt'] > 0){
				for($j=0; $j<$home_team['output_cnt']; $j++){
					//포지션 10 이상은 투수
					if($home_team[$j]['position_sid'] < 10){
		?>
					<option value="<?= $home_team[$j]['sid']; ?>"><?= $player_name[$home_team[$j]['sid']];?>/<?=$player_position[$home_team[$j]['position_sid']]?></option>
		<?php
					}
				}
			}
		?>
				</select>
			</td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_pa"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_ab"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_hits"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_double_hits"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_triple_hits"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_hr"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_r"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_rbi"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_so"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_bb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_hbp"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_gb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_fb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_sf"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_sh"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_gdp"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_sb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_error"></td>
			<td><input type="text" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_batter_<?php echo $i; ?>_note"></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<h3>홈팀 투수 기록</h3>
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
			<th>비고</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i=0; $i<2; $i++){ ?>
		<tr>
			<td>
				<select key="home_pitcher_<?php echo $i; ?>_name" onchange="is_onchange(this, <?php echo $i; ?>)">
					<option selected disabled value="">선수를 선택하세요</option>
					<?php
						if($home_team['output_cnt'] > 0){
							for($j=0; $j<$home_team['output_cnt']; $j++){
								if($home_team[$j]['position_sid'] >= 10){
					?>
					<option value="<?= $home_team[$j]['sid']; ?>"><?= $player_name[$home_team[$j]['sid']]; ?>/<?=$player_position[$home_team[$j]['position_sid']]?></option>
					<?php
								}
							}
						}
					?>
				</select>
			</td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_win"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_lose"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_r"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_ip"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_cg"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_sho"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_sv"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_qs"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_pa"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_gb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_fb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_iffb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_ifh"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_hr"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_k"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_bb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_error"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_hits"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_pitches"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_ibb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_er"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_sf"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_hbp"></td>
			<td><input type="text" onchange="is_onchange(this, <?php echo $i; ?>);" key="home_pitcher_<?php echo $i; ?>_note"></td>
		</tr>
	</tbody>
	<?php } ?>
</table>
</section>
<!-- 어웨이 -->
<section id='away_team_records'>
<h3>어웨이팀 타자 기록</h3>
<!-- 수평 스크롤 -->
<div style="overflow-x: auto;">
<table border="1">
	<thead>
		<tr>
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
	<?php for($i=0; $i<=8; $i++){ ?>
		<tr>
			<td>
				<select key="away_batter_<?php echo $i; ?>_name" onchange="is_onchange(this, <?php echo $i; ?>)">
					<option selected disabled value="">선수를 선택하세요</option>
					<?php
						if($away_team['output_cnt'] > 0){
							for($j=0; $j<$away_team['output_cnt']; $j++){
							// 포지션 10 이상은 투수
								if($away_team[$j]['position_sid'] < 10){
					?>
					<option value="<?= $away_team[$j]['sid']; ?>"><?= $player_name[$away_team[$j]['sid']]; ?>/<?=$player_position[$away_team[$j]['position_sid']]?></option>
					<?php
								}
							}
						}
					?>
				</select>
			</td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_pa"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_ab"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_hits"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_double_hits"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_triple_hits"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_hr"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_r"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_rbi"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_so"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_bb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_hbp"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_gb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_fb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_sf"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_sh"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_gdp"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_sb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_error"></td>
			<td><input type="text" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_batter_<?php echo $i; ?>_note"></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<h3>어웨이팀 투수 기록</h3>
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
			<th>비고</th>
		</tr>
	</thead>
	<tbody>
	<?php for($i=0; $i<2; $i++){ ?>
		<tr>
			<td>
				<select key="away_pitcher_<?php echo $i; ?>_name" onchange="is_onchange(this, <?php echo $i; ?>)">
					<option selected disabled value="">선수를 선택하세요</option>
					<?php
						if($away_team['output_cnt'] > 0){
							for($j=0; $j<$away_team['output_cnt']; $j++){
								if($away_team[$j]['position_sid'] >= 10){
						?>
					<option value="<?= $away_team[$j]['sid']; ?>"><?= $player_name[$away_team[$j]['sid']]; ?>/<?=$player_position[$away_team[$j]['position_sid']]?></option>
					<?php
								}
							}
						}
					?>
				</select>
			</td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_win"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_lose"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_r"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_ip"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_cg"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_sho"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_sv"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_qs"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_pa"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_gb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_fb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_iffb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_ifh"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_hr"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_k"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_bb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_error"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_hits"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_pitches"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_ibb"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_er"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_sf"></td>
			<td><input type="number" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_hbp"></td>
			<td><input type="text" onchange="is_onchange(this, <?php echo $i; ?>);" key="away_pitcher_<?php echo $i; ?>_note"></td>
		</tr>
	</tbody>
	<?php } ?>
</table>
</section>
