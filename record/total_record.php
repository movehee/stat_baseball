<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	// 포지션 테이블 조회
	$sql = "SELECT sid, name FROM position;";
	$query_result = sql($sql);
	$position_data = select_process($query_result);

	for($i=0; $i<$position_data['output_cnt']; $i++){
		$position[$position_data[$i]['sid']] = $position_data[$i]['name'];
	}
	// 팀 테이블 조회
	$sql = "SELECT sid, team_name FROM team_info";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			//값: 팀sid => 팀명
			$team_name[$query_result[$i]['sid']] = $query_result[$i]['team_name'];
			//값: 팀명 => 팀sid
			$team_name_sid[$query_result[$i]['team_name']] = $query_result[$i]['sid'];
		}
	}

	//선수 테이블 조회
	$sql = "SELECT sid, player_name, team_sid, position_sid FROM player_info;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result['output_cnt'] > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$player_name[$query_result[$i]['sid']]	= $query_result[$i]['player_name'];
			$position_name[$query_result[$i]['sid']] = $query_result[$i]['position_sid'];
		}
	}

	//시즌 테이블 조회
	$sql = "SELECT DISTINCT season FROM season_team_info WHERE season ORDER BY season DESC;";
	$query_result = sql($sql);
	$season_result = select_process($query_result);

	//시즌 타자 누적 기록
	$sql = "SELECT player_sid ,SUM(game) AS total_game, SUM(pa) AS total_pa , SUM(ab) AS total_ab , SUM(hits) AS total_hits , SUM(double_hits) AS total_double_hits , SUM(triple_hits) AS total_triple_hits , SUM(hr) AS total_hr , SUM(r) AS total_r , SUM(rbi) AS total_rbi , SUM(bb) AS total_bb , SUM(hbp) AS total_hbp , SUM(gb) AS total_gb , SUM(fb) AS total_fb , SUM(sf) AS total_sf , SUM(sh) AS total_sh , SUM(gdp) AS total_gdp , SUM(k) AS total_k , SUM(sb) AS total_sb , SUM(error) AS total_error FROM season_batter_info GROUP BY player_sid LIMIT 50;";
	$query_result = sql($sql);
	$total_batter = select_process($query_result);

	//시즌 투수 누적 기록
	$sql = "SELECT player_sid, SUM(game) AS total_game, SUM(win) AS total_win, SUM(lose) AS total_lose, SUM(r) AS total_r, SUM(ip) AS total_ip, SUM(cg) AS total_cg, SUM(sho) AS total_sho, SUM(sv) AS total_sv, SUM(qs) AS total_qs, SUM(pa) AS total_pa, SUM(gb) AS total_gb, SUM(fb) AS total_fb, SUM(iffb) AS total_iffb, SUM(ifh) AS total_ifh, SUM(hr) AS total_hr, SUM(k) AS total_k, SUM(bb) AS total_bb, SUM(error) AS total_error, SUM(hits) AS total_hits, SUM(pitches) AS total_pitches, SUM(ibb) AS total_ibb, SUM(er) AS total_er, SUM(sf) AS total_sf, SUM(hbp) AS total_hbp FROM season_pitcher_info GROUP BY player_sid LIMIT 50;";
	$query_result = sql($sql);
	$total_pitcher = select_process($query_result);

	//선수별 타율
	$avg = array();
	for($i=0; $i<$total_batter['output_cnt']; $i++){
		$batter = $total_batter[$i];
		if($batter['total_ab'] > 0){
			$avg[$batter['player_sid']] = $batter['total_hits'] / $batter['total_ab'];
		}else{
			$avg[$batter['player_sid']] = 0;
		}
	}

	//선수별 출루율
	$obp = array();
	for($i=0; $i<$total_batter['output_cnt']; $i++){
		$batter = $total_batter[$i];
		$plate_appearances = $batter['total_ab'] + $batter['total_bb'] + $batter['total_hbp'] + $batter['total_sf'];
		if($plate_appearances > 0){
			$obp[$batter['player_sid']] = ($batter['total_hits'] + $batter['total_bb'] + $batter['total_hbp']) / $plate_appearances;
		}else{
			$obp[$batter['player_sid']] = 0;
		}
	}

	//선수별 장타율
	$slg = array();
	for($i=0; $i<$total_batter['output_cnt']; $i++){
		$batter = $total_batter[$i];
		$total_bases = $batter['total_hits'] + $batter['total_double_hits'] * 2 + $batter['total_triple_hits'] * 3 + $batter['total_hr'] * 4;
		if($batter['total_ab'] > 0){
			$slg[$batter['player_sid']] = $total_bases / $batter['total_ab'];
		}else{
			$slg[$batter['player_sid']] = 0;
		}
	}

	//선수별 OPS
	$ops = array();
	for($i=0; $i<$total_batter['output_cnt']; $i++){
		$batter = $total_batter[$i];
		$ops[$batter['player_sid']] = $obp[$batter['player_sid']] + $slg[$batter['player_sid']];
	}

	//선수별 BABIP
	$babip = array();
	for($i=0; $i<$total_batter['output_cnt']; $i++){
		$batter = $total_batter[$i];
		if($batter['total_ab'] > 0){
			$babip[$batter['player_sid']] = ($batter['total_hits'] - $batter['total_hr']) / ($batter['total_ab'] - $batter['total_hr'] - $batter['total_k'] + $batter['total_sf']);
		}else{
			$babip[$batter['player_sid']] = 0;
		}
	}

	//선수별 방어율
	$era = array();
	for($i=0; $i<$total_pitcher['output_cnt']; $i++){
		$pitcher = $total_pitcher[$i];
		if($pitcher['total_ip'] > 0){
			$era[$pitcher['player_sid']] = ($pitcher['total_er'] * 9) / ($pitcher['total_ip'] / 3);
		}else{
			$era[$pitcher['player_sid']] = 0;
		}
	}

	//선수별 WHIP
	$whip = array();
	for($i=0; $i<$total_pitcher['output_cnt']; $i++){
		$pitcher = $total_pitcher[$i];
		if($pitcher['total_ip'] > 0){
			$whip[$pitcher['player_sid']] = ($pitcher['total_hits'] + $pitcher['total_bb']) / ($pitcher['total_ip'] / 3);
		}else{
			$whip[$pitcher['player_sid']] = 0;
		}
	}

	//선수별 FIP
	$fip = array();
	for($i=0; $i<$total_pitcher['output_cnt']; $i++){
		$pitcher = $total_pitcher[$i];
		if($pitcher['total_ip'] > 0){
			$fip[$pitcher['player_sid']] = (($pitcher['total_hr'] * 13) + (($pitcher['total_bb'] + $pitcher['total_hbp']) * 3) - ($pitcher['total_k'] * 2)) / ($pitcher['total_ip'] / 3) + 3.2;
		}else{
			$fip[$pitcher['player_sid']] = 0;
		}
	}

?>
<title>통산 기록</title>
<h2>통산 기록</h2>
<select key='total_season' id='total_season' onchange="select_season(this)">
	<option value="" selected disabled>시즌선택</option>
	<?php for($i=0; $i<$season_result['output_cnt']; $i++){ 
			echo "<option value = '{$season_result[$i]['season']}'>{$season_result[$i]['season']}</option>";
	} ?>
</select>
<div style='clear:both;'></div>
<hr>
<div class='btn'>
	<button onclick="total_record()">통산기록</button>
</div>
<div style='clear:both;'></div>
<!-- 타자섹션 -->
<h3>타자</h3>
<section id="batter_section" class="scrollable_section">
	<table id="batter_table" border="1">
		<colgroup>
			<col width='60px' />
			<col width='100px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='60px' />
			<col width='60px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='60px' />
			<col width='50px' />
			<col width='60px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
			<col width='50px' />
		</colgroup>
		<thead>
			<tr>
				<th>선수명</th>
				<th>포지션</th>
				<th>경기</th>
				<th>타석</th>
				<th>안타</th>
				<th>2루타</th>
				<th>3루타</th>
				<th>홈런</th>
				<th>득점</th>
				<th>타점</th>
				<th>볼넷</th>
				<th>사구</th>
				<th>땅볼</th>
				<th>뜬공</th>
				<th>희생<br>플라이</th>
				<th>희생<br>번트</th>
				<th>병살타</th>
				<th>삼진</th>
				<th>도루</th>
				<th>실책</th>
				<th>타율</th>
				<th>출루율</th>
				<th>장타율</th>
				<th>OPS</th>
				<th>BABIP</th>
			</tr>
		</thead>
		<tbody>
		<?php for($i=0; $i<$total_batter['output_cnt']; $i++){
			$batter = $total_batter[$i];
		?>
			<tr>
				<td><?= $player_name[$batter['player_sid']] ?></td>
				<td><?= $position[$position_name[$batter['player_sid']]] ?></td>
				<td><?= $batter['total_game'] ?></td>
				<td><?= $batter['total_pa'] ?></td>
				<td><?= $batter['total_hits'] ?></td>
				<td><?= $batter['total_double_hits'] ?></td>
				<td><?= $batter['total_triple_hits'] ?></td>
				<td><?= $batter['total_hr'] ?></td>
				<td><?= $batter['total_r'] ?></td>
				<td><?= $batter['total_rbi'] ?></td>
				<td><?= $batter['total_bb'] ?></td>
				<td><?= $batter['total_hbp'] ?></td>
				<td><?= $batter['total_gb'] ?></td>
				<td><?= $batter['total_fb'] ?></td>
				<td><?= $batter['total_sf'] ?></td>
				<td><?= $batter['total_sh'] ?></td>
				<td><?= $batter['total_gdp'] ?></td>
				<td><?= $batter['total_k'] ?></td>
				<td><?= $batter['total_sb'] ?></td>
				<td><?= $batter['total_error'] ?></td>
				<td><?= number_format($avg[$batter['player_sid']],3) ?></td>
				<td><?= number_format($obp[$batter['player_sid']],3) ?></td>
				<td><?= number_format($slg[$batter['player_sid']],3) ?></td>
				<td><?= number_format($ops[$batter['player_sid']],3) ?></td>
				<td><?= number_format($babip[$batter['player_sid']],3) ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</section>
<!-- 투수섹션 -->
<h3>투수</h3>
<section id="pitcher_section" class="scrollable_section">
	<table id="pitcher_table" border="1">
		<colgroup>
			<col width='38px' />
			<col width='67px' />
			<col width='30px' />
			<col width='30px' />
			<col width='30px' />
			<col width='30px' />
			<col width='40px' />
			<col width='30px' />
			<col width='30px' />
			<col width='40px' />
			<col width='50px' />
			<col width='30px' />
			<col width='30px' />
			<col width='45px' />
			<col width='35px' />
			<col width='35px' />
			<col width='35px' />
			<col width='35px' />
			<col width='35px' />
			<col width='40px' />
			<col width='40px' />
			<col width='35px' />
			<col width='40px' />
			<col width='40px' />
			<col width='30px' />
			<col width='38px' />
			<col width='40px' />
			<col width='40px' />
			<col width='40px' />
		</colgroup>
		<thead>
			<tr>
				<th>선수명</th>
				<th>포지션</th>
				<th>경기</th>
				<th>승</th>
				<th>패</th>
				<th>실점</th>
				<th>이닝</th>
				<th>완투</th>
				<th>완봉</th>
				<th>세이브</th>
				<th>퀄리티<br>스타트</th>
				<th>땅볼</th>
				<th>뜬공</th>
				<th>내야<br>플라이</th>
				<th>내야<br>안타</th>
				<th>홈런</th>
				<th>삼진</th>
				<th>볼넷</th>
				<th>실책</th>
				<th>피안타</th>
				<th>투구수</th>
				<th>고의<br>사구</th>
				<th>자책점</th>
				<th>희생<br>플라이</th>
				<th>사구</th>
				<th>사사구</th>
				<th>방어율</th>
				<th>WHIP</th>
				<th>FIP</th>
			</tr>
		</thead>
		<tbody>
		<?php for($i=0; $i<$total_pitcher['output_cnt']; $i++){
			$pitcher = $total_pitcher[$i];
		?>
			<tr>
				<td><?= $player_name[$pitcher['player_sid']] ?></td>
				<td><?= $position[$position_name[$pitcher['player_sid']]] ?></td>
				<td><?= $pitcher['total_game'] ?></td>
				<td><?= $pitcher['total_win'] ?></td>
				<td><?= $pitcher['total_lose'] ?></td>
				<td><?= $pitcher['total_r'] ?></td>
				<td><?= number_format($pitcher['total_ip'],1) ?></td>
				<td><?= $pitcher['total_cg'] ?></td>
				<td><?= $pitcher['total_sho'] ?></td>
				<td><?= $pitcher['total_sv'] ?></td>
				<td><?= $pitcher['total_qs'] ?></td>
				<td><?= $pitcher['total_gb'] ?></td>
				<td><?= $pitcher['total_fb'] ?></td>
				<td><?= $pitcher['total_iffb'] ?></td>
				<td><?= $pitcher['total_ifh'] ?></td>
				<td><?= $pitcher['total_hr'] ?></td>
				<td><?= $pitcher['total_k'] ?></td>
				<td><?= $pitcher['total_bb'] ?></td>
				<td><?= $pitcher['total_error'] ?></td>
				<td><?= $pitcher['total_hits'] ?></td>
				<td><?= $pitcher['total_pitches'] ?></td>
				<td><?= $pitcher['total_ibb'] ?></td>
				<td><?= $pitcher['total_er'] ?></td>
				<td><?= $pitcher['total_sf'] ?></td>
				<td><?= $pitcher['total_hbp'] ?></td>
				<td><?= $pitcher['total_bb'] ?></td>
				<td><?= number_format($era[$pitcher['player_sid']],2) ?></td>
				<td><?= number_format($whip[$pitcher['player_sid']],2) ?></td>
				<td><?= number_format($fip[$pitcher['player_sid']],2) ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</section>