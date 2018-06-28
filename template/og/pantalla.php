<script language=Javascript>
var ancho=screen.width;
var alto=screen.height;
</script>
<?
if (empty($_POST['PantallaAlto']))
{
echo 'entro sin datos la primera vez.<br>Vengo de:'.$_SERVER['REQUEST_URI'];
echo "<form name=formulario method=POST action=".$PHP_SELF.">";
echo "<input type=hidden name=PantallaAncho>";
echo "<input type=hidden name=PantallaAlto>";
echo "<input type=hidden name=URI value=".$_SERVER['REQUEST_URI'].">";
echo "</form>";
echo "<script language=Javascript> document.formulario.PantallaAncho.value=ancho; document.formulario.PantallaAlto.value=alto;";
echo "document.formulario.submit() ";
echo "</script>";
}
else
{
$_SESSION['PantallaAlto'] = $_POST['PantallaAlto'];
$_SESSION['PantallaAncho'] = $_POST['PantallaAncho'];
}
?>