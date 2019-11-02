<table width="90%">
<h2>Concursos en ejecucion</h2>
Date time:<span id="nowdate">
</span>
<tr class="toprow" align=center>
<td width="10%">ID
<td width="50%">Name
<td width="30%">Status
<td width="10%">Private</tr>
	<tbody>
			<?php 
			$cnt=0;
			foreach($view_contest as $row){
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
</center>

<script>
<?php date_default_timezone_set("America/La_Paz"); ?>
var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
//alert(diff);
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
