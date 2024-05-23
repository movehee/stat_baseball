<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	$player_sid = $_POST['player_sid'];

	//선택한 선수 정보 sql
	$sql = "SELECT player_name ,position_sid FROM player_info WHERE sid ='$player_sid';";
	$query_result = sql($sql);
	$player = select_process($query_result);

	//선수의 포지션 정보 가져오기
	$position_sid = $player[0]['position_sid'];

	//선수명 가져오기
	$player_name = $player[0]['player_name'];

	//포지션 정보 sql
	$sql = "SELECT name FROM position WHERE sid = '$position_sid';";
	$query_result = sql($sql);
	$position = select_process($query_result);

	$position_name = $position[0]['name'];

	//포지션sid 구분으로 시즌별 기록 가져오기
	$season_info = ($position_sid > 9) ? 'season_pitcher_info' : 'season_batter_info';
	$sql = "SELECT * FROM $season_info WHERE player_sid = '$player_sid' AND season = '".__YEAR__."';"; 
	$query_result = sql($sql);
	$data = select_process($query_result);

	//시즌 선택 sql
	$sql = "SELECT sid, season FROM $season_info WHERE player_sid = '$player_sid' ORDER BY season DESC;";
	$query_result = sql($sql);
	$season_data = select_process($query_result);

	if($season_data['output_cnt'] > 0){
		// 시즌 선택
		$season = $season_data[0]['season'] . " 시즌 기록";
	}else{
		$season = "시즌 기록이 없습니다.";
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

	if($position_sid > 9){
		//투수일 경우
		for($i=0; $i<$data['output_cnt']; $i++){
			if($data[$i]['ip'] === 0){
				$era = 0;
				$whip = 0;
			}
			//평균자책점
			$era = $data[$i]['er'] / $data[$i]['ip'];
			//잔루처리율
			$lob = ($data[$i]['hits'] + $data[$i]['bb'] + $data[$i]['hbp'] - $data[$i]['r']) / ($data[$i]['hits'] + $data[$i]['bb'] + $data[$i]['hbp'] - 1.4 * $data[$i]['hr']);
			//수비 무관 투구능력
			$fip = (12 * $data[$i]['hr'] + 3.2 * ($data[$i]['bb'] + $data[$i]['hbp']) - 2.5 * $data[$i]['k']) / ($data[$i]['ip'] + $league_C);
			//이닝당 출루 허용률
			$whip = ($data[$i]['hits'] + $data[$i]['bb']) / $data[$i]['ip'];
			//내야 뜬공 비율
			$if_fly = $data[$i]['iffb'] / $data[$i]['fb'];
			//뜬공 중 홈런 비율
			$f_hr = $data[$i]['hr'] / $data[$i]['fb'];
		}
	}else{
		//타자일 경우
		for($i=0; $i<$data['output_cnt']; $i++){
			//타수가 0인 경우 설정
			if($data[$i]['ab'] === 0){
				$avg = 0;
				$iso = 0;
			}
			//타율
			$avg = $data[$i]['hits'] / $data[$i]['ab'];
			//출루율
			$obp = ($data[$i]['hits'] + $data[$i]['bb'] + $data[$i]['hbp']) / ($data[$i]['ab'] + $data[$i]['bb'] + $data[$i]['hbp'] + $data[$i]['sf']);
			//장타율
			$slg = ($data[$i]['hits'] + 2 * $data[$i]['double_hits'] + 3 * $data[$i]['triple_hits'] + 4 * $data[$i]['hr']) / $data[$i]['ab'];
			//출루율+장타율
			$ops = $obp + $slg;
			//인플레이 타율
			$babip = ($data[$i]['hits'] - $data[$i]['hr']) / ($data[$i]['pa'] - $data[$i]['hr'] - $data[$i]['k'] + $data[$i]['sf']);
			//순장타율
			$iso = $slg - $avg;
		}
	}

	echo '<script>var position_sid = '.json_encode($position_sid).';</script>';
	echo '<script>var league_C = '.json_encode($league_C).';</script>';
?>
<title>선수 기록</title>
<h2><?=$player_name?> 선수 기록</h2>
<div>
	<strong>포지션: </strong><?=$position_name?></br>
</div>

<!-- 시즌별 기록 표시 -->
<h3 id='season_id'><?=$season?></h3>
<select key='season_key' id='season_key' onchange="select_season(this);">
	<option value="" selected disabled>시즌선택</option>
	<?php
	for($i=0; $i<$season_data['output_cnt']; $i++){
	?>
	<option value='<?=$season_data[$i]['sid']?>'><?=$season_data[$i]['season']?>시즌	</option>
	<?php
	}
	?>
</select>
<?php if($data['output_cnt'] > 0){
		//투수 일 경우
		if($position_sid > 9){
?>
<table border="1" id='grid_table'>
	<colgroup>
		<col width='50px' />
		<col width='50px' />
		<col width='50px' />
		<col width='50px' />
		<col width='50px' />
		<col width='50px' />
		<col width='50px' />
		<col width='50px' />
		<col width='60px' />
		<col width='50px' />
		<col width='50px' />
		<col width='50px' />
		<col width='50px' />
		<col width='50px' />
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
			<th>출장</th>
			<th>승리</th>
			<th>패배</th>
			<th>
				ERA
				<span class="tooltip">평균자책점<br>투수의 9이닝당 자책점</span>
			</th>
			<th>실점</th>
			<th>이닝</th>
			<th>완투</th>
			<th>완봉</th>
			<th>세이브</th>
			<th>
				QS
				<span class="tooltip">퀄리티 스타트<br>선발 투수가 6이닝 이상을 3자책점 이하로 막은 경우</span>
			</th>
			<th>상대<br>타자</th>
			<th>땅볼</th>
			<th>뜬공</th>
			<th>내야<br>뜬공</th>
			<th>내야<br>안타</th>
			<th>피홈런</th>
			<th>삼진</th>
			<th>볼넷</th>
			<th>에러</th>
			<th>
				LOB%
				<span class="tooltip">잔루처리율<br>출루한 주자가 득점을 하지 못한 비율</span>
			</th>
			<th>
				FIP
				<span class="tooltip">수비무관 평균자책<br>운과 수비력을 제외한 투수가 가진 고유의 능력만을 평가하는 지표</span>
			</th>
			<th>
				WHIP
				<span class="tooltip">이닝당 출루허용률<br>피안타 수와 볼넷 수의 합을 투구 이닝으로 나눈 수치</span>
			</th>
			<th>
				IFFB%
				<span class="tooltip">내야 뜬공 비율<br>In Field Fly Ball의 약어</span>
			</th>
			<th>
				HR/FB
				<span class="tooltip">뜬공/홈런 비</span>
			</th>
		<?php
			//타자 일 경우
			}else{
		?>
<table border="1" id='grid_table'>
	<colgroup>
		<col width='35px' />
		<col width='35px' />
		<col width='35px' />
		<col width='35px' />
		<col width='50px' />
		<col width='50px' />
		<col width='45px' />
		<col width='45px' />
		<col width='45px' />
		<col width='45px' />
		<col width='55px' />
		<col width='45px' />
		<col width='45px' />
		<col width='60px' />
		<col width='45px' />
		<col width='55px' />
		<col width='30px' />
		<col width='50px' />
		<col width='30px' />
		<col width='30px' />
		<col width='30px' />
		<col width='60px' />
		<col width='70px' />
		<col width='70px' />
	</colgroup>
	<thead>
		<tr>
			<th>출장</th>
			<th>타석</th>
			<th>타수</th>
			<th>안타</th>
			<th>2루타</th>
			<th>3루타</th>
			<th>홈런</th>
			<th>득점</th>
			<th>타점</th>
			<th>볼넷</th>
			<th>사사구</th>
			<th>땅볼</th>
			<th>뜬공</th>
			<th>희생<br>플라이</th>
			<th>희생<br>번트</th>
			<th>병살타</th>
			<th>삼진</th>
			<th>도루</th>
			<th>
				AVG
				<span class="tooltip">타율<br>안타수 / 타수 </span>
			</th>
			<th>
				OBP
				<span class="tooltip">출루율<br>타석에 나왔을 때 아웃을 당하지 않고 주자로 살아남는 확률</span>
			</th>
			<th>
				SLG
				<span class="tooltip">장타율<br>1타수당 평균 몇 개의 베이스를 얻어 낼 수 있는가를 측정하는 지표</span>
			</th>
			<th>
				OPS
				<span class="tooltip">출루율+장타율</span>
			</th>
			<th>
				BABIP
				<span class="tooltip">인플레이 타율<br>인플레이 타구중 안타가 된 비율<br>(파울, 희생 번트, 홈런을 제외)</span>
			</th>
			<th>
				ISO
				<span class="tooltip">순장타율<br>장타율 - 타율</span>
			</th>
			<?php
			}
			?>
		</tr>
	</thead>
	<tbody>
		<tr>
		<?php
		// 투수인 경우
		if($position_sid > 9){
			for($i=0; $i<$data['output_cnt']; $i++){
		?>
			<td><?=$data[$i]['game']?></td>
			<td><?=$data[$i]['win']?></td>
			<td><?=$data[$i]['lose']?></td>
			<td><?=number_format($era, 2)?></td>
			<td><?=$data[$i]['r']?></td>
			<td><?=$data[$i]['ip']?></td>
			<td><?=$data[$i]['cg']?></td>
			<td><?=$data[$i]['sho']?></td>
			<td><?=$data[$i]['sv']?></td>
			<td><?=$data[$i]['qs']?></td>
			<td><?=$data[$i]['pa']?></td>
			<td><?=$data[$i]['gb']?></td>
			<td><?=$data[$i]['fb']?></td>
			<td><?=$data[$i]['iffb']?></td>
			<td><?=$data[$i]['ifh']?></td>
			<td><?=$data[$i]['hr']?></td>
			<td><?=$data[$i]['k']?></td>
			<td><?=$data[$i]['bb']?></td>
			<td><?=$data[$i]['error']?></td>
			<td><?=number_format($lob, 3)?></td>
			<td><?=number_format($fip, 3)?></td>
			<td><?=number_format($whip, 3)?></td>
			<td><?=number_format($if_fly, 3)?>%</td>
			<td><?=number_format($f_hr, 3)?>%</td>
		<?php
			}
		}else{
			// 타자인 경우
			for($i=0; $i<$data['output_cnt']; $i++){
		?>
			<td><?=$data[$i]['game']?></td>
			<td><?=$data[$i]['pa']?></td>
			<td><?=$data[$i]['ab']?></td>
			<td><?=$data[$i]['hits']?></td>
			<td><?=$data[$i]['double_hits']?></td>
			<td><?=$data[$i]['triple_hits']?></td>
			<td><?=$data[$i]['hr']?></td>
			<td><?=$data[$i]['r']?></td>
			<td><?=$data[$i]['rbi']?></td>
			<td><?=$data[$i]['bb']?></td>
			<td><?=$data[$i]['hbp']?></td>
			<td><?=$data[$i]['gb']?></td>
			<td><?=$data[$i]['fb']?></td>
			<td><?=$data[$i]['sf']?></td>
			<td><?=$data[$i]['sh']?></td>
			<td><?=$data[$i]['gdp']?></td>
			<td><?=$data[$i]['k']?></td>
			<td><?=$data[$i]['sb']?></td>
			<td><?=number_format($avg, 3)?></td>
			<td><?=number_format($obp, 3)?></td>
			<td><?=number_format($slg, 3)?></td>
			<td><?=number_format($ops, 3)?></td>
			<td><?=number_format($babip, 3)?></td>
			<td><?=number_format($iso, 3)?></td>
		<?php
			}
		}
		?>
		</tr>
	</tbody>
</table>
<?php }else{ ?>
    <p>기록이 없습니다.</p>
<?php } ?>