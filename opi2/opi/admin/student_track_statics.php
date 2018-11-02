<script language="javascript" type="text/javascript" src="jquery.js"></script>
<script language="javascript" type="text/javascript" src="//cdn.datatables.net/1.10.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.6/css/jquery.dataTables.css">
<?php require("admin-header.php");
require_once("../include/set_get_key.php");
if (!(isset($_SESSION['administrator'])||isset($_SESSION['problem_master_editor']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}

if(isset($_GET['index'])){
	$id = intval($_GET['index']);
	$user =$_SESSION['user_id'];
	$room_name = mysql_real_escape_string(htmlspecialchars ($_GET['room_name']));
}

?>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#student_track').DataTable();
		$('#student_track tbody').on( 'click', 'tr', function () {
			$(this).toggleClass('selected');
		} );

		$('button').click( function () {
			for(var i=0;i<table.rows('.selected').data().length;i++){
				var dato = table.rows('.selected').data()[i];
				
				if (i+1 == table.rows('.selected').data().length){
					$('#student_list').val($('#student_list').val()+dato[2]);
				}else{
					$('#student_list').val($('#student_list').val()+dato[2]+",");
				}
			}
			alert($('#student_list').val());
		} );

	} );
</script>

<fieldset>
	<legend>Listado del curso <b><?php echo $room_name ?></b></legend> 
	<table id="student_track"  class="display" cellspacing="0" width="100%"> 
		<thead> 
			<tr> 
				<th>N</th> 
				<th>Nombre</th> 
				<th>Usuario</th> 
				<th>School</th>
				<th>AC</th>
				<th>WA</th>
				<th>TLE</th>
				<th>Total</th>
				<th>Contest AC</th>
				<th>Contest WA</th>
				<th>Contest TLE</th>
				<th>Contest Total</th>
			</tr> 
		</thead> 
		<tbody> 

			<?php
			$sql= "SELECT tu.user_id, us.nick, us.school, us.email FROM track_users AS tu, track AS t, users AS us WHERE tu.id_track='$id' and t.user_id =  '$user' AND tu.id_track = t.track_id AND us.user_id = tu.user_id";
			$result = mysql_query($sql) or die(mysql_error());
			$ac = $wa = $tle = 0;
			$cac = $cwa = $ctle = 0;
			for ($cont = 1;$row=mysql_fetch_object($result);$cont++){
				echo "<tr>";
				echo "<td>".$cont."</td>";
				echo "<td>".$row->nick."</td>";
				echo "<td>".$row->user_id."</td>";
				echo "<td>".$row->school."</td>";
				echo "<td> <a href=../status.php?user_id=$row->user_id&jresult=4>".$ac  = get_total($row->user_id,4)." </a></td>";
				echo "<td> <a href=../status.php?user_id=$row->user_id&jresult=6>".$wa  = get_total($row->user_id,6)." </a></td>";
				echo "<td> <a href=../status.php?user_id=$row->user_id&jresult=7>".$tle = get_total($row->user_id,7)." </a></td>";
				echo "<td> ".($ac+$wa+$tle)." </td>";
				echo "<td>  ".$cac  = get_total_ct($row->user_id,4)." </td>";
				echo "<td>  ".$cwa  = get_total_ct($row->user_id,6)." </td>";
				echo "<td>  ".$ctle = get_total_ct($row->user_id,7)." </td>";
				echo "<td>".($cac+$cwa+$ctle)." </td>";
				echo "</tr>";
			}
			?>
		</tbody> 
	</table>
	<?php
	function get_total($user,$veredict){
		$sql = "SELECT COUNT( DISTINCT problem_id ) 
		FROM  `solution` 
		WHERE  `user_id` ='$user'
		AND  `result` ='$veredict'
		ORDER BY  `problem_id` ASC";
		$result = mysql_query($sql) or die(mysql_error());
		$row=mysql_result($result, 0);
		return $row;
	}
	function get_total_ct($user,$veredict){
		$sql = "SELECT COUNT( DISTINCT ans.problem_id ) 
		FROM contest AS cont, solution AS ans
		WHERE cont.contest_id = ans.contest_id
		AND ans.result ='$veredict'
		AND ans.user_id =  '$user'";
		$result = mysql_query($sql) or die(mysql_error());
		$row=mysql_result($result, 0);
		return $row;
	}
	?>

