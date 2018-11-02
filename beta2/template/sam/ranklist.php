<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
	<script type="text/javascript" src="include/jquery-latest.js"></script> 
	<script type="text/javascript" src="include/jquery.tablesorter.js"></script>
</head>
<body>
	<div id="wrapper">
		<?php require_once("oj-header.php");?>
		<section id="main" style="width:80%;">
			<table align="center" width="90%">
				<thead>
					<tr>
						<td colspan="3" align="left">
							<form action="userinfo.php">
								<?php echo $MSG_USER?>
								<input name="user">
								<input type="submit" value="Go">
							</form>
						</td>
						<td colspan="3" align="right">
							<a href=ranklist.php?scope=d>Dia</a>
							<a href=ranklist.php?scope=w>Semana</a>
							<a href=ranklist.php?scope=m>Mes</a>
							<a href=ranklist.php?scope=y>Año</a>
						</td>
					</tr>
				</thead>
			</table>
			<table id ="tablerank" name="tablerank" align="center" width="90%" cellspacing="0" cellpadding="0">
				<thead>
					<tr class='toprow'>
						<th width="5%" align="center">
							<b><spam><?php echo $MSG_Number?></spam></b>
						</th>
						<th width="10%" align="center">
							<b><spam><?php echo $MSG_USER?></spam></b>
						</th>
						<td width="55%" align="center">
							<b><spam><?php echo $MSG_NICK?></spam></b>
						</th>
						<th width="10%" align="center">
							<b><spam><?php echo $MSG_AC?></spam></b>
						</th>
						<th width="10%" align="center">
							<b><spam><?php echo $MSG_SUBMIT?></spam></b>
						</th>
						<th width="10%" align="center">
							<b><spam><?php echo $MSG_RATIO?></spam></b>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$cnt=0;
					foreach($view_rank as $row){
						if ($cnt) 
							echo "<tr class='oddrow'>";
						else
							echo "<tr class='evenrow'>";
						foreach($row as $table_cell){
							echo "<td>";
							echo "\t".$table_cell;
							echo "</td>";
						}

						echo "</tr>";

						$cnt=1-$cnt;
					}
					?>
				</tbody>		
			</table>
			<script type="text/javascript">
				$(function(){
					$('#tablerank').tablesorter(); 
				});
			</script>
			<?php 
			echo "<center>";
			for($i = 0; $i <$view_total ; $i += $page_size) {
				echo "<a href='./ranklist.php?start=" . strval ( $i ).($scope?"&scope=$scope":"") . "'>";
				echo strval ( $i + 1 );
				echo "-";
				echo strval ( $i + $page_size );
				echo "</a>&nbsp;";
				if ($i % 250 == 200)
					echo "<br>";
			}
			echo "</center>";

			?>

		</section>
	</div><!--end wrapper-->
	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
</html>






