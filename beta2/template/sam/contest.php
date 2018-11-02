	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $view_title?></title>
		<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
		<script src="include/sortTable.js"></script>
		<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<?php require_once("contest-header.php");?>
			<section id="main">
				<center>
					<div>
						<h3>Contest<?php echo $view_cid?>  -  <?php echo $view_title ?></h3>
						<p><?php echo $view_description?></p>
						<div class="fb-like" data-href=<?php echo 'contest.php?cid='.$cid ; ?> data-layout="button_count" data-action="like" data-show-face="true" data-share="true" ></div>
						<br>Start Time: <font color=#993399><?php echo $view_start_time?></font>
						End Time: <font color=#993399><?php echo $view_end_time?></font><br>
						Current Time: <font color=#993399><span id=nowdate > <?php echo date("Y-m-d H:i:s")?></span></font>
						Status:<?php
						if ($now>$end_time) 
							echo "<span class=red>Ended</span>";
						else if ($now<$start_time) 
							echo "<span class=red>Not Started</span>";
						else 
							echo "<span class=red>Running</span>";
						?>&nbsp;&nbsp;
						<?php
						if ($view_private=='0') 
							echo "<span class=blue>Public</font>";
						else 
							echo "&nbsp;&nbsp;<span class=red>Private</font>"; 
						?>
						<br>
						[<a href='status.php?cid=<?php echo $view_cid?>'>Status</a>]
						[<a href='contestrank.php?cid=<?php echo $view_cid?>'>Standing</a>]
						[<a href='conteststatistics.php?cid=<?php echo $view_cid?>'>Statistics</a>]						
					</div>

					<table id='problemset' width='90%' border="1">
						<thead>
							<tr align=center class='toprow'>		
								
								<td width='10%'>AC</td>
								<td style="cursor:hand" onclick="sortTable('problemset', 1, 'int');" width=10%><A><?php echo $MSG_PROBLEM_ID?></A></td>
								<td width='60%'><?php echo $MSG_TITLE?></td>
								<td width='10%'><?php echo $MSG_SOURCE?></td>
								<td style="cursor:hand" onclick="sortTable('problemset', 4, 'int');" width='5%'><A><?php echo $MSG_AC?></A></td>
								<td style="cursor:hand" onclick="sortTable('problemset', 5, 'int');" width='5%'><A><?php echo $MSG_SUBMIT?></A></td>

							</tr>
						</thead>
						<tbody>
							<?php 
							$cnt=0;
							foreach($view_problemset as $row){
								if ($cnt) 
									echo "<tr class='oddrow' align='center'>";
								else
									echo "<tr class='evenrow' align='center'>";
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
				</center>
			</section>
		</div>

		<section id="foot">
			<?php require_once("oj-footer.php");?>
		</section>
	</body>

	<script>
		var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
		function clock(){
			var x,h,m,s,n,xingqi,y,mon,d;
			var x = new Date(new Date().getTime()+diff);
			y = x.getYear()+1900;
			if (y>3000) y-=1900;
			mon = x.getMonth()+1;
			d = x.getDate();
			xingqi = x.getDay();
			h=x.getHours();
			m=x.getMinutes();
			s=x.getSeconds();

			n=y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
      //alert(n);
      document.getElementById('nowdate').innerHTML=n;
      setTimeout("clock()",1000);
  } 
  clock();
</script>

</html>

