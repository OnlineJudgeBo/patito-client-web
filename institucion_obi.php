<?php
require_once("./include/db_info.inc.php");

if(!empty($_POST["key"])) {

$query ="SELECT * FROM colegios WHERE nombre like '%".$_POST["key"]."%'";
$result = mysql_query($query);

if(!empty($result)) {
?>
<ul id="obi-list">
<?php
while($row = mysql_fetch_object($result)){
?>
<li onClick="selectCountry('<?php echo $row->nombre; ?>','<?php echo $row->id_colegio; ?>');">
<?php echo $row->nombre; ?></li>
<?php } ?>
</ul>
<?php } } ?>
