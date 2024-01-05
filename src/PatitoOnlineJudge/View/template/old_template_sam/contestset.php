<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php echo $view_title?></title>
	<link rel=stylesheet href='./template/<?php echo $OJ_TEMPLATE?>/<?php echo isset($OJ_CSS)?$OJ_CSS:"hoj.css" ?>' type='text/css'>
	  <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>

</head>
<body>
	<div id="wrapper">
		<?php require_once("oj-header.php");?>
		<section id="main">			
			<center>
				<table width="90%">
					<h2>Contest List</h2>
					ServerTime:<span id="nowdate"> </span>
					<tr class="toprow" align="center">
						<td width="10%">ID</td>
						<td width="50%">Name</td>
						<td width="30%">Status</td>
						<td width="10%">Private</td>
					</tr>
					<tbody>
						<?php 
						$cnt=0;
						foreach($view_contest as $row){
							if ($cnt) 
								echo "<tr class='oddrow'>";
							else
								echo "<tr class='evenrow'>";
							foreach($row as $table_cell){
								echo "<td>"; echo "\n";
								echo "\t".$table_cell;
								echo "</td>"; echo "\n";
							}
							echo "</tr>"; echo "\n";

							$cnt=1-$cnt;
						}
						?>
<!-- RETO CTF -->
<!-- Este es la bandera (flag) del reto: abcfer385kf79b3c46510a6dcef9dda -->
					</tbody>

				</table>
			</center>

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
					document.getElementById('nowdate').innerHTML=n;
					setTimeout("clock()",1000);
				} 
				clock();
			</script>
		</section>
	</div>
	<section id="foot">
		<?php require_once("oj-footer.php");?>
	</section>
</body>
</html>

