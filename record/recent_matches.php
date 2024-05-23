<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//유효성 검사
	$page = 1;
	if(isset($_POST['page']) === true){
		if($_POST['page'] !== '' && $_POST['page'] !== null){
			$page = $_POST['page'];
		}
	}
	//경기 날짜 검색
	$is_start_date = '';
	if(isset($_POST['start_date']) === true){
		if($_POST['start_date'] !== '' && $_POST['start_date'] !== null){
			$is_start_date = $_POST['start_date'];
		}
	}
	//경기 날짜 검색
	$is_end_date = '';
	if(isset($_POST['end_date']) === true){
		if($_POST['end_date'] !== '' && $_POST['end_date'] !== null){
			$is_end_date = $_POST['end_date'];
		}
	}

	//유저 데이터 sql로 관리자 권한 sid 찾기
	$sql = "SELECT is_admin FROM user_data WHERE team_sid ='".__TEAM_SID__."';";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	if($query_result > 0){
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$admin = (int)$query_result[$i]['is_admin'];
		}
	}

	//========================================================================
	//팀명 조회
	$sql = "SELECT sid, team_name, league_sid FROM team_info;";
	$query_result = sql($sql);
	$query_result = select_process($query_result);

	//팀 이름 배열 설정
	$team_name = array();
	$league = array();
	for($i=0; $i<$query_result['output_cnt']; $i++){
		$team_name[$query_result[$i]['sid']] = $query_result[$i]['team_name'];
		$league[$query_result[$i]['sid']] = $query_result[$i]['league_sid'];
	}

	//검색한 날짜가 있을 경우
	$date_sql = "SELECT DISTINCT DATE(game_date) as game_date FROM game_info";
	if($is_start_date !== '' && $is_end_date !== ''){
		$date_sql .= " WHERE `game_date` BETWEEN '$is_start_date 00:00:01' AND '$is_end_date 23:59:59'";
	}
	$date_sql .= " ORDER BY `game_date` DESC";
	$query_result = sql($date_sql);
	$query_result = select_process($query_result);

	//모든 날짜 개수(페이징)
	$total_dates = $query_result['output_cnt'];

	//페이징
	$items_per_page = 10;
	$start_index = ($page - 1) * $items_per_page;
	$pagging_cnt = ceil($total_dates / $items_per_page);
	
	$start_page = (floor(($page - 1) / 10) * 10) + 1;
	
	$end_page = $start_page + 9;
	if($end_page > $pagging_cnt){
		$end_page = $pagging_cnt;
	}

	$date_limit_sql = $date_sql . " LIMIT $start_index, $items_per_page";
	$query_result = sql($date_limit_sql);
	$query_result = select_process($query_result);

	$page_dates = array();
	for($i=0; $i<$query_result['output_cnt']; $i++){
		array_push($page_dates, $query_result[$i]['game_date']);
	}
	$date_str = "'" . implode("','", $page_dates) . "'";

	//현재 페이지 게임 날짜 가져오기
	$game_sql = "SELECT sid, game_date, home_team_sid, away_team_sid, home_team_score, away_team_score FROM game_info WHERE DATE(game_date) IN ($date_str) ORDER BY `game_date` DESC";
	$query_result = sql($game_sql);
	$query_result = select_process($query_result);

?>
<title>최근 경기</title>
<h2>경기 기록</h2>
<article id='search_area'>
	<input type="date" id="start_date" name="start_date">
	<label>~</label>
	<input type="date" id="end_date" name="end_date">
	<button onclick='search()'>조회</button>
</article>
<div style='clear:both;'></div>
<hr>
<div class='btn'>
	<?php 
	// admin이 -1일 경우에만 표시
	if($admin === -1){
	?>
	<button onclick="render('record/game_registration');">경기 등록</button>
	<?php
	}
	?>
</div>
<div style='clear:both;'></div>
<section id='table_area'>
	<table id='grid_table' border="1">
		<thead>
			<tr>
				<th>날짜</th>
				<th>HOME</th>
				<th>score</th>
				<th>VS</th>
				<th>score</th>
				<th>AWAY</th>
				<th>상세보기</th>
				<?php 
					// 관리자 권한일 때만 기록 등록 버튼 표시
					if($admin === -1){
				?>
				<th>기록등록</th>
				<?php 
					}
				?>
			</tr>
		</thead>
		<tbody>
		<?php
		// 이전 게임 날짜 초기화
		$prev_game_date = null;
		for($i=0; $i<$query_result['output_cnt']; $i++){
			$game = $query_result[$i];
			$home_team_name = $team_name[$game['home_team_sid']];
			$away_team_name = $team_name[$game['away_team_sid']];
			$league_sid = $league[$game['home_team_sid']];
			
			// 현재 게임 날짜와 이전 게임 날짜가 다른 경우 rowspan 설정
			if($game['game_date'] !== $prev_game_date){
				// rowspan 계산
				$rowspan = 1;
				for($j=$i+1; $j<$query_result['output_cnt']; $j++){
					if($query_result[$j]['game_date'] === $game['game_date']){
						$rowspan++;
					}else{
						break;
					}
				}
		?>
			<tr>
				<td rowspan="<?= $rowspan ?>" class="game-date" data-sid="<?=$game['sid']?>"><?= $game['game_date'] ?></td>
				<td><?= $home_team_name ?></td>
				<td><?= $game['home_team_score'] ?></td>
				<td>VS</td>
				<td><?= $game['away_team_score'] ?></td>
				<td><?= $away_team_name ?></td>
				<td onclick="view(<?=$game['sid']?>, '<?=$game['game_date']?>','<?=$game['home_team_sid']?>','<?=$game['away_team_sid']?>', '<?=$league_sid?>')">상세 보기</td>
				<?php 
					// 관리자 권한일 때만 기록 등록 버튼 표시
					if($admin === -1){
				?>
				<td onclick="detail(<?=$game['sid']?>, '<?=$game['game_date']?>','<?=$game['home_team_sid']?>','<?=$game['away_team_sid']?>')">기록 등록</td>
				<?php 
					}
				?>
			</tr>
		<?php
			// 이전 게임 날짜 업데이트
			$prev_game_date = $game['game_date'];
			}else{
		?>
			<tr>
				<td><?= $home_team_name ?></td>
				<td><?= $game['home_team_score'] ?></td>
				<td>VS</td>
				<td><?= $game['away_team_score'] ?></td>
				<td><?= $away_team_name ?></td>
				<td onclick="view(<?=$game['sid']?>, '<?=$game['game_date']?>','<?=$game['home_team_sid']?>','<?=$game['away_team_sid']?>', '<?=$league_sid?>')">상세 보기</td>
				<?php 
					// 관리자 권한일 때만 기록 등록 버튼 표시
					if($admin === -1){
					?>
				<td id='league_sid' style='display: none;'><?=$league_sid?></td>
				<td onclick="detail(<?=$game['sid']?>, '<?=$game['game_date']?>','<?=$game['home_team_sid']?>','<?=$game['away_team_sid']?>', '<?=$league_sid?>')">기록 등록</td>
				<?php
				}
				?>
			</tr>
			<?php
				}
			}
			?>
		</tbody>
	</table>
	<ul id='pagging'>
		<li onclick='search(<?=$prev?>);'>이전</li>
		<?php for($i=$start_page; $i<=$end_page; $i++){ ?>
			<li <?=($i == $page) ? 'id="this_page"' : '' ?> onclick='search(<?=$i?>);'><?=$i?></li>
		<?php } ?>
		<li onclick='search(<?=$next?>);'>다음</li>
	</ul>
</section>
