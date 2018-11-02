	<?php 
	$cnt=0;
	foreach($view_contest as $row){
		echo "<div id='contest_front'>";
		foreach($row as $table_cell){
			echo "\t".$table_cell;
		}
		echo "</div><br>";
	}
	?>
	<script>
		<?php date_default_timezone_set("America/La_Paz"); ?>
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