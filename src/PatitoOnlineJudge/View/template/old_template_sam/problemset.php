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
		<section id="main">
			<h3 align='center'>
				<?php
				for ($i=1;$i<=$view_total_page;$i++){
					if ($i>1) echo '&nbsp;';
					if ($i==$page) echo "<span class=red>$i</span>";
					else echo "<a href='problemset.php?page=".$i."'>".$i."</a>";
				}
				?>

			</h3>
			<center>
				<table>
					<tr align='center' class='evenrow'>
						<td width='5'></td>
						<td width='50%' colspan='1'>
							<form class=form-search action=problem.php>
								Problem ID<input class="input-small search-query" type='text' name='id' size=5 style="height:24px">
								<button class="btn btn-mini" type='submit'  >Ir</button>
							</form>
						</td>
						<td width='50%' colspan='1'>
							<form class="form-search">
								<input style="height:24px" type="text" name=search class="input-large search-query">
								<button type="submit" class="btn btn-mini">
									<?php echo $MSG_SEARCH?>
								</button>
							</form>
						</td>
					</tr>
				</table>
				
				<table id='listproblemset' width='90%'class='table table-striped' cellspacing="0" cellpadding="0">
					<thead>
						<tr class='toprow'>
							<th width='5'></th>
							<th width='120'  ><span><A><?php echo $MSG_PROBLEM_ID?></A></span></th>
							<th><span><?php echo $MSG_TITLE?></span></th>
							<th width='10%'><span><?php echo $MSG_SOURCE?></span></th>
							<th width=60 ><span><?php echo $MSG_AC?></span></th>
							<th width=60 ><span><?php echo $MSG_SUBMIT?></span></th>
							

						</tr>
					</thead>
					<tbody>
						<?php 
						$cnt=0;
						foreach($view_problemset as $row){
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
						$('#listproblemset').tablesorter(); 
					});
				</script>
			</center>
		</section>
	</div>
	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
</html>
