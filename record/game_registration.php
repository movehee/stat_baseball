<?php
	define('__CORE_TYPE__', 'view');
	include $_SERVER['DOCUMENT_ROOT'].'/function/core.php';

	//리그 선택 sql
	$sql = "SELECT sid, name FROM league_info;";
	$query_result = sql($sql);
	$league = select_process($query_result);

?>
<title>경기 등록</title>
<h2>경기 등록 화면</h2>
<section id="game_section" class="game_container">
	<select key="league_key" id="league_key" onchange="select_league(this)">
		<option selected disabled>리그 선택</option>
	<?php
		for($i=0; $i<$league['output_cnt']; $i++){
	?>
		<option value="<?=$league[$i]['sid']?>"><?=$league[$i]['name']?></option>
	<?php
	}
	?>
	</select>
	<input id="game_date" type="date" />
	<button onclick="registration();">경기 등록</button>
	<hr>
	<table id="game_table">
		<thead>
			<tr>
				<th>HOME</th>
				<th></th>
				<th>AWAY</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>리그를 선택하세요.</td>
			</tr>
		</tbody>
	</table>
</section>